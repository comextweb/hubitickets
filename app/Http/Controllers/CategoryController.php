<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use App\Models\UserCatgory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->isAbleTo('category manage')) {
            $categories = Category::where('created_by', creatorId())->get();
            $categoryTree = getChildreCategory($categories);
            return view('admin.category.index', compact('categoryTree'));
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }


    public function create()
    {
        if (Auth::user()->isAbleTo('category create')) {
            $categories = Category::where('created_by', creatorId())->get();
            $categoryTree = buildCategoryTree($categories);
            $settings = getCompanyAllSettings();
            return view('admin.category.create', compact('settings', 'categoryTree'));
        } else {
            return response()->json(['error' => 'Permission Denied'], 401);
        }
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->isAbleTo('category create')) {
            $validation = Validator::make($request->all(), [
                'category_name' => 'required',
                'color' => 'required',
            ]);

            if ($validation->fails()) {
                $message = $validation->getMessageBag()->first();
                return redirect()->back()->with('error', __($message));
            }

            $category = new Category();
            $category->name = $request->category_name;
            $slug = generateUniqueSlug($category->name, $category);
            $category->color = $request->color;
            $category->slug = $slug;
            $category->parent_id = $request->parent_id ?? 0;
            $category->created_by = creatorId();
            $category->save();
            return redirect()->route('admin.category')->with('success', __('Category created successfully'));
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }

    public function edit($id)
    {
        if (Auth::user()->isAbleTo('category edit')) {
            $category = Category::find($id);
            if ($category) {
                $categories = Category::where('created_by', creatorId())->get();
                $categoryTree = buildCategoryTree($categories);
                $settings = getCompanyAllSettings();
                return view('admin.category.edit', compact('categories', 'categoryTree', 'settings', 'category'));
            } else {
                return response()->json(['error' => 'Category Not Found.'], 401);
            }
        } else {
            return response()->json(['error' => 'Permission Denied.'], 401);
        }
    }


    public function update(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('category edit')) {
            $category = Category::find($id);
            if ($category) {
                $validation = Validator::make($request->all(), [
                    'category_name' => 'required',
                    'color' => 'required',
                ]);

                if ($validation->fails()) {
                    $message = $validation->getMessageBag()->first();
                    return redirect()->back()->with('error', __($message));
                }

                $category->name = $request->category_name;
                $category->color = $request->color;
                $category->parent_id = $request->parent_id ?? 0;
                $category->save();
                return redirect()->route('admin.category')->with('success', __('Category Updated successfully'));
            } else {
                return redirect()->back()->with('error', 'Category Not Found.');
            }
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }
    public function destroy($id)
    {
        if (Auth::user()->isAbleTo('category delete')) {
            $category = Category::find($id);
            if ($category) {
                $checkChild = Category::where('parent_id', $category->id)->count();
                if ($checkChild > 0) {
                    return redirect()->route('admin.category')->with('error', __('Please Delete The SubCategory Of ' . $category->name . ' Category.'));                    
                }
                $checkTicket = Ticket::where('category_id', $category->id)->count();
                if ($checkTicket > 0) {
                    return redirect()->route('admin.category')->with('error', __('Please Delete The Ticket Of ' . $category->name . ' Category.'));                    
                }
                $category->delete();
                return redirect()->back()->with('success', 'Category Deleted Successfully.');
            } else {
                return redirect()->back()->with('error', 'Category Not Found.');
            }
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }
}
