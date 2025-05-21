<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use App\Models\EmailTemplateLang;
use App\Models\UserEmailTemplate;
use App\Models\Languages;
use App\Models\NotificationTemplateLangs;
use App\Models\NotificationTemplates;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EmailTemplateController extends Controller
{

    public function index()
    {
        if (Auth::user()->isAbleTo('email-template manage')) {
            $emailTemplates = NotificationTemplates::where('type','mail')->get();
            return view('email_templates.index', compact('emailTemplates'));
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('email-template edit')) {
            $emailTemplate = NotificationTemplates::find($id);
            if ($emailTemplate) {
                $emailTemplate->from = $request->from;
                $emailTemplate->save();
                return redirect()->back()->with('success', __('The email template details are updated successfully'));
            } else {
                return redirect()->back()->with('error', 'Email Template Not Found.');
            }
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }



    public function manageEmailLang($id, $lang = 'en')
    {
        if (Auth::user()->isAbleTo('email-template view')) {
            $languages         = languages();
            $emailTemplate = NotificationTemplates::where('id', '=', $id)->first();
            if ($emailTemplate) {
                $currEmailTempLang = NotificationTemplateLangs::where('parent_id', '=', $id)->where('lang', $lang)->first();
                if ($currEmailTempLang) {
                    if (!isset($currEmailTempLang) || empty($currEmailTempLang)) {
                        $currEmailTempLang       = NotificationTemplateLangs::where('parent_id', '=', $id)->where('lang', 'en')->first();
                        $currEmailTempLang->lang = $lang;
                    }
                    return view('email_templates.show', compact('emailTemplate', 'languages', 'currEmailTempLang'));
                } else {
                    return redirect()->back()->with('error', 'Email Template Not Found.');
                }
            } else {
                return redirect()->back()->with('error', 'Email Template Not Found.');
            }
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }


    // Used For Store Email Template Language Wise
    public function storeEmailLang(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('email-template edit')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'subject' => 'required',
                    'content' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $emailLangTemplate = NotificationTemplateLangs::where('parent_id', '=', $id)->where('lang', '=', $request->lang)->first();
            // if record not found then create new record else update it.
            if (empty($emailLangTemplate)) {
                $emailLangTemplate            = new NotificationTemplateLangs();
                $emailLangTemplate->parent_id = $id;
                $emailLangTemplate->lang      = $request['lang'];
                $emailLangTemplate->subject   = $request['subject'];
                $emailLangTemplate->content   = $request['content'];
                $emailLangTemplate->save();
            } else {
                $emailLangTemplate->subject = $request['subject'];
                $emailLangTemplate->content = $request['content'];
                $emailLangTemplate->save();
            }

            return redirect()->route(
                'manage.email.language',
                [
                    $id,
                    $request->lang,
                ]
            )->with('success', __('Email Template Detail successfully updated.'));
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }

    // Used For Update Status owner Wise.

    public function updateStatus(Request $request)
    {
        if ($request->has('mail_noti')) {
            foreach ($request->mail_noti as $key => $notification) {
                // Define the data to be updated or inserted
                $data = [
                    'name' => $key,
                    'created_by' => creatorId(),
                ];
                // Check if the record exists, and update or insert accordingly
                Settings::updateOrInsert($data, ['value' => $notification]);
            }
        }
        companySettingCacheForget(creatorId());

        return redirect()->back()->with('success', __('Status successfully updated!'));
    }
}
