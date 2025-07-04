<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Mail;
use App\Mail\CommonEmailTemplate;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Storage;
use Date;
use Illuminate\Support\Facades\Log;

class Utility extends Model
{

    private static $fetchSetting = null;

    private static $storageSetting = null;

    private static $languages = null;

    public static function sendEmailTemplate($emailTemplate, $mailTo, $obj)
    {
        if (Auth::check()) {
            if (Auth::user()->type == 'admin') {
                $usr = User::where('id', Auth::user()->id)->first();
            } else {
                $usr = User::where('id', Auth::user()->created_by)->first();
            }
        } else {
            $ticket = Ticket::where('id', $obj['originalTicketId'])->first();
            // Once Will reply from user side if ticket created by agent the it will return agent id
            $agentOrAdmin = User::where('id', $ticket->created_by)->first();
            $usr = User::where('id', $agentOrAdmin->created_by)->first();
            if (empty($usr)) {
                $usr = User::where('id', $ticket->created_by)->first();
            }
        }
        unset($mailTo[$usr->id]);
        $mailTo = array_values($mailTo);
        $template = NotificationTemplates::where('action', $emailTemplate)->first();
        if (isset($template) && !empty($template)) {
            $settings = getCompanyAllSettings();
            $content = NotificationTemplateLangs::where('parent_id', '=', $template->id)->where('lang', 'LIKE', $usr->lang)->first();
            if (!empty($content->content)) {
                $content->content = self::replaceVariable($content->content, $obj);

                if(isset($obj['ticket_subject']) && $obj['ticket_subject']){
                    $content->subject = $content->subject .': '. $obj['ticket_subject'];
                }

                try {
                    $a = setSMTPConfig();
                    Mail::to($mailTo)->send(new CommonEmailTemplate($content, $usr->id, $settings));
                } catch (\Exception $e) {
                    $error = __('E-Mail has been not sent due to SMTP configuration' .  $e->getMessage());
                }

                if (isset($error)) {
                    $arReturn = [
                        'is_success' => false,
                        'error' => $error,
                    ];
                } else {
                    $arReturn = [
                        'is_success' => true,
                        'error' => false,
                    ];
                }
            } else {
                $arReturn = [
                    'is_success' => false,
                    'error' => __('Mail Not Send, Email Template Content Not Found.'),
                ];
            }
            return $arReturn;
        } else {
            return [
                'is_success' => false,
                'error' => __('Mail Not Send. Email Template Not Found.'),
            ];
        }
    }

    public static function replaceVariable($content, $obj)
    {
        $arrVariable = [
            '{app_name}',
            '{company_name}',
            '{ticket_name}',
            '{ticket_subject}',
            '{ticket_id}',
            '{ticket_category}',
            '{ticket_priority}',
            '{ticket_priority_color}',
            '{ticket_department}',
            '{ticket_description}',
            '{ticket_reply_description}',
            '{app_url}',
            '{email}',
            '{password}',
            '{user_name}',
            '{ticket_url}',
            '{customer_email}',
            '{agent_email}',
            '{agent_name}',
            '{resolution_agent_name}',
            '{resolution_agent_email}',
            '{support_agent_email}',
            '{support_agent_name}',
            '{customer_name}',
            '{admin_name}',
            '{sender_name}',
            '{rating_url}',
        ];

        $arrValue    = [
            'app_name' => '-',
            'company_name' => '-',
            'ticket_name' => '-',
            'ticket_subject' => '-',
            'ticket_id' => '-',
            'ticket_category' => '-',
            'ticket_priority' => '-',
            'ticket_priority_color' => '-',
            'ticket_department' => '-',
            'ticket_description' => '-',
            'ticket_reply_description' => '-',
            'app_url' => '-',
            'email' => '-',
            'password' => '-',
            'user_name' => '-',
            'ticket_url' => '-',
            'customer_email' => '-',
            'agent_email' => '-',
            'agent_name' => '-',
            'resolution_agent_name' => '-',
            'resolution_agent_email' => '-',
            'support_agent_email' => '-',
            'support_agent_name' => '-',
            'customer_name' => '',
            'admin_name' => '',
            'sender_name' => '',
            'rating_url' => '',
        ];

        foreach ($obj as $key => $val) {
            $arrValue[$key] = $val;
        }


        $settings = getCompanyAllSettings();
        $company_name = isset($settings['company_name']) ? $settings['company_name'] : env('APP_NAME');

        // Get current request URL
        $currentUrl = request()->getSchemeAndHttpHost();
        
        $arrValue['app_name']     =  $company_name;
        $arrValue['company_name'] = isset($settings['company_name']) ? $settings['company_name'] : env('APP_NAME');;
        $arrValue['app_url']      = '<a href="' . $currentUrl . '" target="_blank">' . $currentUrl . '</a>';
        return str_replace($arrVariable, array_values($arrValue), $content);
    }
}
