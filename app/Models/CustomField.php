<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomField extends Model
{
    protected $fillable = [
        'name',
        'type',
        'placeholder',
        'width',
        'order',
        'status',
        'is_required',
        'custom_id',
        'created_by',
    ];

    public static $fieldTypes = [
        'text' => 'Text',
        'email' => 'Email',
        'number' => 'Number',
        'date' => 'Date',
        'textarea' => 'Textarea',
        'file' => 'File',
        'select' => 'Select',
        'checkbox' => 'Checkbox',
        'radio' => 'Radio',
    ];

    public static $fieldWidth = [
        '3' => '25%',
        '4' => '33%',
        '6' => '50%',
        '8' => '66%',
        '12' => '100%',
    ];

    public static function saveData($obj, $data)
    {

        if(!empty($data) && count($data) > 0)
        {
            $RecordId = $obj->id;
            foreach($data as $fieldId => $value)
            {
                if(is_array($value))
                {
                    $value = implode(',',$value);
                }
                if (isset($value) && is_a($value, \Illuminate\Http\UploadedFile::class)) {
                    $errors = [];
                    $dir        = ('tickets/' . $RecordId);
                    $filenameWithExt = $value->getClientOriginalName();
                    $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension       = $value->getClientOriginalExtension();

                    $fileNameToStore = $filename . '_' . time() . '.' . $extension;

                    $path = multipleFileUpload($value, 'file', $fileNameToStore, $dir);
                    if ($path['flag'] == 1) {
                        $value = $path['url'];
                    } elseif ($path['flag'] == 0) {
                        $errors = __($path['msg']);
                        return redirect()->back()->with('error', __($errors));
                    }
                }
                if(!empty($fieldId) && !empty($value))
                {
                    $CustomFieldValue = CustomFieldValue::where('record_id' , $RecordId)->where('field_id' , $fieldId)->first();
                    if(empty($CustomFieldValue))
                    {
                        $CustomFieldValue            = new CustomFieldValue();
                    }
                    $CustomFieldValue->record_id = $RecordId;
                    $CustomFieldValue->field_id  = $fieldId;
                    $CustomFieldValue->value     = $value;
                    $CustomFieldValue->save();
                }
            }
        }
    }

    public function getData($obj , $id)
    {
        if($obj != null)
        {
            return CustomFieldValue::select(
                [
                    'custom_field_values.value',
                    'custom_fields.id',
                ]
            )->join('custom_fields', 'custom_field_values.field_id', '=', 'custom_fields.id')->where('record_id', '=', $obj->id)->where('custom_fields.id' , $id)->value('custom_field_values.value');
        }
    }
   
    public function field_value()
    {
        if($this->id == 4)
        {
            $priority = Category::get()->pluck('name')
            ->map(function($name) {
                return ['field_value' => $name];
            });

            return json_encode($priority);
        }
        else if($this->id == 5)        
        {
            $priority = Priority::get()->pluck('name')
            ->map(function($name) {
                return ['field_value' => $name];
            });

            return json_encode($priority);
        }
    }
}
