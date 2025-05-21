<?php

namespace App\Http\Controllers;

use App\Facades\AddonFacade;
use App\Models\NotificationTemplateLangs;
use App\Models\NotificationTemplates;
use App\Models\Utility;
use App\Models\Languages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NotificationTemplatesController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->isAbleTo('notification-template manage')) {
            $notifications = NotificationTemplates::where('type', '!=', 'mail')->get()->groupBy('type');
            return view('notification_templates.index', compact('notifications'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function manageNotificationLang($id, $lang = 'en')
    {
        if (Auth::user()->isAbleTo('notification-template view')) {
            $notification_template     = NotificationTemplates::where('id', $id)->first();
            if ($notification_template) {
                $languages         = languages();
                $settings = getCompanyAllSettings();
                $curr_noti_tempLang = NotificationTemplateLangs::where('parent_id', '=', $notification_template->id)->where('lang', $lang)->where('created_by', '=', creatorId())->first();
                if ($curr_noti_tempLang) {
                    $templateVariables = json_decode($curr_noti_tempLang->variables, true);
                    $notification_templates = NotificationTemplates::all();
                    return view('notification_templates.show', compact('notification_template', 'notification_templates', 'curr_noti_tempLang', 'languages', 'settings', 'templateVariables'));
                } else {
                    return redirect()->back()->with('error', __('Current Notification Language Not Found.'));
                }
            } else {
                return redirect()->back()->with('error', __('Notification Template Not Found.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('notification-template edit')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'content' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $NotiLangTemplate = NotificationTemplateLangs::where('parent_id', '=', $id)->where('lang', '=', $request->lang)->where('created_by', '=', creatorId())->first();
            if (empty($NotiLangTemplate)) {
                $variables = NotificationTemplateLangs::where('parent_id', '=', $id)->where('lang', '=', $request->lang)->first()->variables;
                $NotiLangTemplate            = new NotificationTemplateLangs();
                $NotiLangTemplate->parent_id = $id;
                $NotiLangTemplate->lang      = $request['lang'];
                $NotiLangTemplate->content   = $request['content'];
                $NotiLangTemplate->variables = $variables;
                $NotiLangTemplate->created_by = creatorId();
                $NotiLangTemplate->save();
            } else {
                $NotiLangTemplate->content = $request['content'];
                $NotiLangTemplate->save();
            }
            return redirect()->route('manage.notification.language', [$id, $request->lang,])->with('success', __('Notification Template successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
