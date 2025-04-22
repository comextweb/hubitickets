<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class FaqController extends Controller
{

    public function index()
    {
        if (Auth::user()->isAbleTo('faq manage')) {
            $faqs = Faq::get();
            return view('admin.faq.index', compact('faqs'));
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }



    public function create()
    {
        if (Auth::user()->isAbleTo('faq create')) {
            $settings = getCompanyAllSettings();
            return view('admin.faq.create', compact('settings'));
        } else {
            return response()->json(['error' => 'Permission Denied.'], 401);
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('faq create')) {
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'description' => 'required'
            ]);

            if ($validator->fails()) {
                $message = $validator->getMessageBag();
                return redirect()->back()->with('error', $message->first());
            }
            $faq = new FAQ();
            $faq->title = $request->title;
            $faq->description = $request->description;
            $faq->save();
            return redirect()->route('admin.faq')->with('success',  __('Faq created successfully'));
        } else {
            return redirect()->back()->with('error', 'Permission Denied');
        }
    }

    public function show($id)
    {
        if (Auth::user()->isAbleTo('faq show')) {
            $faq = FAQ::find($id);
            if ($faq) {
                return view('admin.faq.show', compact('faq'));
            } else {
                return redirect()->back()->with('error', 'FAQ Not Found.');
            }
        } else {
            return response()->json(['error' => 'Permission Denied.'], 401);
        }
    }

    public function edit($id)
    {
        if (Auth::user()->isAbleTo('faq edit')) {
            $faq = Faq::find($id);
            if ($faq) {
                $settings = getCompanyAllSettings();
                return view('admin.faq.edit', compact('faq','settings'));
            } else {
                return response()->json(['error' => 'FAQ Not Found.'], 401);
            }
        } else {
            return response()->json(['error' => 'Permission Denied.'], 401);
        }
    }


    public function update(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('faq edit')) {
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'description' => 'required'
            ]);

            if ($validator->fails()) {
                $message = $validator->getMessageBag();
                return redirect()->back()->with('error', $message->first());
            }

            $faq = Faq::where('id', $id)->first();
            if ($faq) {
                $faq->title = $request->title;
                $faq->description = $request->description;
                $faq->save();
                return redirect()->route('admin.faq')->with('success', __('Faq Updated Successfully.'));
            } else {
                return redirect()->back()->with('error', 'FAQ Not Found.');
            }
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }

    public function destroy($id)
    {
        if (Auth::user()->isAbleTo('faq delete')) {
            $faq = Faq::find($id);
            if ($faq) {
                $faq->delete();
                return redirect()->route('admin.faq')->with('success', __('Faq deleted Successfully.'));
            } else {
                return redirect()->back()->with('error', 'FAQ Not Found.');
            }
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }
}
