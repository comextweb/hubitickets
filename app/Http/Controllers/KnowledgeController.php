<?php

namespace App\Http\Controllers;

use App\Events\CreateKnowledgeBase;
use App\Events\DestroyKnowledgeBase;
use App\Events\UpdateKnowledgeBase;
use App\Models\Knowledge;
use Illuminate\Http\Request;
use App\Models\Knowledgebasecategory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class KnowledgeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->isAbleTo('knowledgebase manage')) {

            $knowledges = Knowledge::get();

            return view('admin.knowledge.index', compact('knowledges'));
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }

    public function create()
    {
        $user = Auth::user();
        if ($user->isAbleTo('knowledgebase create')) {
            $category = Knowledgebasecategory::get();
            $settings = getCompanyAllSettings();
            return view('admin.knowledge.create', compact('category', 'settings'));
        } else {
            return response()->json(['error' => 'Permission Denied.'], 401);
        }
    }


    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('knowledgebase create')) {
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'description' => 'required',
                'category' => 'required'
            ]);

            if ($validator->fails()) {
                $message = $validator->getMessageBag();
                return redirect()->back()->with('error', $message->first());
            }
            $knowledge = new  Knowledge();
            $knowledge->title = $request->title;
            $knowledge->description = $request->description;
            $knowledge->category = $request->category;
            $knowledge->save();
            event(new CreateKnowledgeBase($request, $knowledge));

            return redirect()->route('admin.knowledge')->with('success',  __('Knowledge created successfully'));
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }

    public function show($id)
    {
        if (Auth::user()->isAbleTo('knowledgebase show')) {
            $knowledge = Knowledge::with('getCategoryInfo')->find($id);
            if ($knowledge) {
                return view('admin.knowledge.show', compact('knowledge'));
            } else {
                return response()->json(['error' => 'Knowledgebase Not Found.'], 401);
            }
        } else {
            return response()->json(['error' => 'Permission Denied.'], 401);
        }
    }


    public function edit($id)
    {
        if (Auth::user()->isAbleTo('knowledgebase edit')) {
            $knowledge = Knowledge::find($id);
            if ($knowledge) {
                $settings = getCompanyAllSettings();
                $category = Knowledgebasecategory::get();
                return view('admin.knowledge.edit', compact('knowledge', 'category', 'settings'));
            } else {
                return response()->json(['error' => 'Knowledgebase Not Found.'], 401);
            }
        } else {
            return response()->json(['error' => 'Permission Denied.'], 401);
        }
    }


    public function update(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('knowledgebase edit')) {
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'description' => 'required',
                'category' => 'required'
            ]);

            if ($validator->fails()) {
                $message = $validator->getMessageBag();
                return redirect()->back()->with('error', $message->first());
            }

            $knowledge = Knowledge::where('id', $id)->first();
            if ($knowledge) {
                $knowledge->title = $request->title;
                $knowledge->description = $request->description;
                $knowledge->category = $request->category;
                $knowledge->save();
                event(new UpdateKnowledgeBase($request, $knowledge));

                return redirect()->route('admin.knowledge')->with('success', __('Knowledge updated successfully'));
            } else {
                return redirect()->back()->with('error', 'Knowledge Not Found.');
            }
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }


    public function destroy($id)
    {
        if (Auth::user()->isAbleTo('knowledgebase delete')) {
            $knowledge = Knowledge::find($id);
            if ($knowledge) {
                event(new DestroyKnowledgeBase($knowledge));
                $knowledge->delete();
                return redirect()->route('admin.knowledge')->with('success', __('Knowledge deleted successfully'));
            } else {
                return redirect()->back()->with('error', 'Knowledge Not Found.');
            }
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }
}
