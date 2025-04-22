<?php

namespace App\Http\Controllers;

use App\Models\CustomField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CustomFieldController extends Controller
{
    public function index()
    {
        if (Auth::user()->isAbleTo('custom field manage')) {
            $customFields = CustomField::orderBy('order')->get();
            return view('admin.custom-field.index', compact('customFields'));
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }

    public function create()
    {
        if (Auth::user()->isAbleTo('custom field create')) {
            return view('admin.custom-field.create');
        } else {
            return response()->json(['error' => 'Permission Denied.'], 401);
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('custom field create')) {
            $validator = Validator::make($request->all(), [
                'name'        => 'required',
                'placeholder' => 'required',
                'type'        => 'required',
                'width'       => 'required',
                'is_required' => 'required'
            ]);

            if ($validator->fails()) {
                $message = $validator->getMessageBag();
                return redirect()->back()->with('error', $message->first());
            }
            $order      = CustomField::where('created_by', creatorId())->get()->count();

            $customField              = new CustomField();
            $customField->name        = $request->name;
            $customField->type        = $request->type;
            $customField->placeholder = $request->placeholder;
            $customField->width       = $request->width;
            $customField->fieldValue  = json_encode($request->field_value);
            $customField->is_required = $request->is_required;
            $customField->order       = $order + 1;
            $customField->created_by  = creatorId();
            $customField->save();
            return redirect()->route('admin.custom-field.index')->with('success',  __('Custom Field created successfully'));
        } else {
            return redirect()->back()->with('error', 'Permission Denied');
        }
    }

    public function edit($id)
    {
        if (Auth::user()->isAbleTo('custom field edit')) {
            $customField = CustomField::find($id);
            if ($customField) {
                $settings = getCompanyAllSettings();
                return view('admin.custom-field.edit', compact('customField', 'settings'));
            } else {
                return response()->json(['error' => 'Custom Field Not Found.'], 401);
            }
        } else {
            return response()->json(['error' => 'Permission Denied.'], 401);
        }
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('custom field edit')) {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'placeholder' => 'required',
                'placeholder' => 'required',
                'type' => 'required',
                'width' => 'required',
                'is_required' => 'required'
            ]);


            if ($validator->fails()) {
                $message = $validator->getMessageBag();
                return redirect()->back()->with('error', $message->first());
            }

            $customField = CustomField::where('id', $id)->first();
            if ($customField) {
                $customField->name        = $request->name;
                $customField->type        = $request->type;
                $customField->placeholder = $request->placeholder;
                $customField->width       = $request->width;
                $customField->fieldValue  = json_encode($request->field_value);
                $customField->is_required = $request->is_required;
                $customField->save();
                return redirect()->route('admin.custom-field.index')->with('success', __('Custom Field Updated Successfully.'));
            } else {
                return redirect()->back()->with('error', 'FAQ Not Found.');
            }
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }

    public function destroy($id)
    {
        if (Auth::user()->isAbleTo('custom field delete')) {
            $customField = CustomField::find($id);
            if ($customField) {
                $customField->delete();
                return redirect()->route('admin.custom-field.index')->with('success', __('Custom Field deleted Successfully.'));
            } else {
                return redirect()->back()->with('error', 'FAQ Not Found.');
            }
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }

    public function order(Request $request)
    {
        $post = $request->all();
        foreach ($post['order'] as $key => $item) {
            $status        = CustomField::where('id', '=', $item)->first();
            if($status)
            {
                $status->order = $key;
                $status->save();
            }            
        }

        return response()->json(['success' => true, 'message' => __('Order updated successfully')]);
    }
}
