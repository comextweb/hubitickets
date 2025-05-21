<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CustomField;
use App\Models\User;

class CustomFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $status = 0;
        $admin = User::where('type', 'admin')->first();

        $fields = [
            [
                "name" => __("Name"),
                "type" => "text",
                "placeholder" => __("Name"),
                "width" => "6",
                "custom_id" => "1",
            ],
            [
                "name" => __("Email"),
                "type" => "email",
                "placeholder" => __("Email"),
                "width" => "6",
                "custom_id" => "2",
            ],
            [
                "name" => __("Subject"),
                "type" => "text",
                "placeholder" => __("Subject"),
                "width" => "12",
                "custom_id" => "3",
            ],
            [
                "name" => __("Category"),
                "type" => "select",
                "placeholder" => __("Select Category"),
                "width" => "6",
                "custom_id" => "4",
            ],
            [
                "name" => __("Priority"),
                "type" => "select",
                "placeholder" => __("Select Priority"),
                "width" => "6",
                "custom_id" => "5",
            ],
            [
                "name" => __("Description"),
                "type" => "textarea",
                "placeholder" => __("Description"),
                "width" => "12",
                "custom_id" => "6",
            ],
            [
                "name" => __("Attachments"),
                "type" => "file",
                "placeholder" => __("You can select multiple files"),
                "width" => "12",
                "custom_id" => "7",
                "is_required" => "0",
            ],

        ];


        foreach ($fields as $order => $field) {
            $checkField = CustomField::where('name', $field['name'])->exists();
            if (!$checkField) {
                $customField              = new CustomField();
                $customField->name        = $field['name'];
                $customField->type        = $field['type'];
                $customField->placeholder = $field['placeholder'];
                $customField->width       = $field['width'];
                $customField->order       = $order;
                $customField->status      = $status;
                $customField->is_required = isset($field['is_required']) ? $field['is_required'] : 1;
                $customField->created_by  = $admin->id;
                $customField->save();
            }
            $customField = CustomField::where('name', $field['name'])->first();
            if ($customField) {
                $customField->custom_id = $field['custom_id'];
                $customField->save();
            }
        }


        // Remove Mobile Number Field From Old Client Database 
        $oldMobileNumber = CustomField::where('id', 7)->where('name', 'Mobile No')->first();
        if ($oldMobileNumber) {
            $oldMobileNumber->delete();
        }
    }
}
