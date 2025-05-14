<?php
// This file use for handle company setting page

namespace Workdo\WhatsAppChatBotAndChat\Http\Controllers\Company;

use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{

    public function index($settings)
    {
        return view('whats-app-chat-bot-and-chat::company.settings.index', compact('settings'));
    }

    public function setting(Request $request)
    {
        if (Auth::user()->isAbleTo('WhatsAppChatBotAndChat manage')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'whatsapp_chatbot_phone_number_id' => 'required',
                    'whatsapp_chatbot_phone_number' => 'required',
                    'whatsapp_chatbot_access_token' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post = $request->all();
            unset($post['_token']);
            foreach ($post as $key => $value) {
                $data = [
                    'name' => $key,
                    'created_by' => creatorId(),
                ];

                Settings::updateOrInsert($data, ['value' => $value]);
            }

            companySettingCacheForget(creatorId());

            return redirect()->back()->with('success', __('Setting Save Successfully.'));
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }
}
