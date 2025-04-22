<?php

namespace App\Http\Controllers;

use App\Events\CreateKnowledgeBaseCategory;
use App\Events\DestroyKnowledgeBaseCategory;
use App\Events\UpdateKnowledgeBaseCategory;
use Illuminate\Http\Request;
use App\Models\Knowledgebasecategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class KnowledgebaseCategoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->isAbleTo('knowledgebase-category manage')) {
            $knowledges_category = Knowledgebasecategory::get();
            return view('admin.knowledgecategory.index', compact('knowledges_category'));
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }

    public function create()
    {
        if (Auth::user()->isAbleTo('knowledgebase-category create')) {
            $settings = getCompanyAllSettings();
            return view('admin.knowledgecategory.create', compact('settings'));
        } else {
            return response()->json(['error' => 'Permission Denied'], 401);
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('knowledgebase-category create')) {
            $validator = Validator::make($request->all(), [
                'title' => 'required',
            ]);

            if ($validator->fails()) {
                $message = $validator->getMessageBag();
                return redirect()->back()->with('error', $message->first());
            }
            $knowledgebasecategory = new Knowledgebasecategory();
            $knowledgebasecategory->title = $request->title;
            $knowledgebasecategory->save();
            event(new CreateKnowledgeBaseCategory($request, $knowledgebasecategory));

            return redirect()->route('admin.knowledgecategory')->with('success',  __('KnowledgeBase Category created successfully'));
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }

    public function edit($id)
    {
        if (Auth::user()->isAbleTo('knowledgebase-category edit')) {
            $knowledge_category = Knowledgebasecategory::find($id);
            if ($knowledge_category) {
                $settings = getCompanyAllSettings();
                return view('admin.knowledgecategory.edit', compact('knowledge_category', 'settings'));
            } else {
                return redirect()->back()->with('error', 'KnowledgeBase Not Found.');
            }
        } else {
            return response()->json(['error' => 'Permission Denied'], 401);
        }
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('knowledgebase-category edit')) {
            $knowledge_category = Knowledgebasecategory::where('id', $id)->first();
            if ($knowledge_category) {
                $knowledge_category->title = $request->title;
                $knowledge_category->save();
                event(new UpdateKnowledgeBaseCategory($request, $knowledge_category));
                return redirect()->route('admin.knowledgecategory')->with('success', __('KnowledgeBase Category updated successfully'));
            } else {
                return redirect()->back()->with('error', 'KnowledgeBase Not Found.');
            }
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }

    public function destroy($id)
    {
        if (Auth::user()->isAbleTo('knowledgebase-category delete')) {
            $knowledge_category = Knowledgebasecategory::with('knowledgebase')->find($id);
            if ($knowledge_category) {
                if ($knowledge_category->knowledgebase->count() == 0) {
                    event(new DestroyKnowledgeBaseCategory($knowledge_category));
                    $knowledge_category->delete();
                    return redirect()->route('admin.knowledgecategory')->with('success', __('KnowledgeBase Category deleted successfully'));
                } else {
                    return redirect()->back()->with('error', 'Please Delete ' . $knowledge_category->title . ' Category KnowledgeBase First.');
                }
            } else {
                return redirect()->back()->with('error', 'KnowledgeBase Category Not Found.');
            }
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }
}
