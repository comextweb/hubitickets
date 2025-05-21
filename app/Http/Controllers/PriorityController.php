<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Priority;
use App\Models\Policies;
use Illuminate\Support\Facades\Auth;

class PriorityController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->isAbleTo('priority manage')) {
            $priority = Priority::where('created_by', Auth::user()->id)->get();
            return view('admin.priority.index', compact('priority'));
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }

    public function create()
    {
        if (Auth::user()->isAbleTo('priority create')) {
            return view('admin.priority.create');
        } else {
            return response()->json(['error' => 'Permission Denied'], 401);
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('priority create')) {
            $validation = [
                'name' => 'required|string|max:255',
                'content' => 'required|string|max:255',
            ];
            $priority = new Priority();
            $priority->name = $request->name;
            $priority->color = $request->color;
            $priority->created_by = creatorId();
            $priority->save();

            return redirect()->route('admin.priority.index')->with('success', __('Priority created successfully'));
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }

    public function edit($id)
    {
        if (Auth::user()->isAbleTo('priority edit')) {
            $priority = Priority::find($id);
            if ($priority) {
                return view('admin.priority.edit', compact('priority'));
            } else {
                return response()->json(['error' => 'Priority Not Found.'], 401);
            }
        } else {
            return response()->json(['error' => 'Permission Denied.'], 401);
        }
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('priority edit')) {
            $priority = Priority::find($id);
            $priority->name = $request->name;
            $priority->color = $request->color;
            $priority->save();
            return redirect()->route('admin.priority.index')->with('success', __('Priority updated successfully'));
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }

    public function destroy($id)
    {
        if (Auth::user()->isAbleTo('priority delete')) {
            $priority = Priority::find($id);
            if ($priority) {
                $priority->delete();
                return redirect()->back()->with('success', __('Priority Deleted Successfully'));
            } else {
                return redirect()->back()->with('error', 'Priority Not Found.');
            }
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }
}
