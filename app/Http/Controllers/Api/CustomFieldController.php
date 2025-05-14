<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomField;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomFieldController extends Controller
{
    use ApiResponser;

    public function getCoustomField()
    {
        $customFields = CustomField::where('id', '>', '7')->get();

        $data = [
            'custom_fields' => $customFields,
        ];

        return $this->success($data);
    }

    public function CustomFields(Request $request)
    {
        $customfield = CustomField::where('created_by', $request->user_id)->get();

        $data = [
            'custom_field' => $customfield
        ];

        return $this->success($data);
    }

    public function storeCustomFields(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name'        => 'required|string|max:100',
                'placeholder' => 'required|string|max:100',
                'type'        => 'required|string|max:100',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            $data     = [];
            return $this->error($data, $messages->first(), 200);
        }

        $order      = CustomField::where('created_by', creatorId())->get()->count();

        $post = [
            'name'        => $request->name,
            'type'        => $request->type,
            'placeholder' => $request->placeholder,
            'width'       => isset($request->width) ? $request->width : '6',
            'fieldValue'  => json_encode($request->field_value),
            'is_required' => isset($request->is_required) ? $request->is_required : '1',
            'order'       => $order + 1,
            'created_by'  => creatorId(),
        ];

        $customfield = CustomField::create($post);

        $data = [
            'custom_field' => $customfield
        ];

        return $this->success($data);
    }

    public function updateCustomFields(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name'        => 'required|string|max:100',
                'placeholder' => 'required|string|max:100',
                'type'        => 'required|string|max:100',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            $data     = [];
            return $this->error($data, $messages->first(), 200);
        }

        $customfield = CustomField::find($request->id);

        if ($customfield) {
            $post = [
                'name'        => $request->name,
                'type'        => $request->type,
                'placeholder' => $request->placeholder,
                'width'       => isset($request->width) ? $request->width : '6',
                'fieldValue'  => json_encode($request->field_value),
                'is_required' => isset($request->is_required) ? $request->is_required : '1',
                'created_by'  => $request->user_id,
            ];

            $customfield->update($post);

            $data = [
                'custom_field' => $customfield
            ];
            return $this->success($data);
        } else {
            $message = "CustomField does not exist";
            return $this->error([], $message, 200);
        }
    }

    public function destroyCustomFields(Request $request)
    {
        if ($request->id <= 8) {
            $message = "You can not delete default customfield";
            return $this->error([], $message, 200);
        }
        $customfield = CustomField::find($request->id);

        $data = [
            'custom_field' => [],
        ];

        if ($customfield) {
            $customfield->delete();
            return $this->success($data);
        } else {
            $message = "CustomField does not exist";
            return $this->error($data, $message, 200);
        }
    }
}
