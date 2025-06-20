<?php
// This file use for handle company setting page

namespace Workdo\Ratings\Http\Controllers\Company;

use App\Models\NotificationTemplates;
use App\Models\Settings;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($settings)
    {
        $status = Ticket::$statues;
        return view('ratings::company.settings.index',compact('settings' , 'status'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(Auth::user()->isAbleTo('ratings manage'))
        {
            $validator = Validator::make($request->all(),
            [
                'ticket_status' => 'required',
            ]);
            if($validator->fails()){
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post = $request->all();
            unset($post['_token']);
            foreach ($post as $key => $value) {
                // Define the data to be updated or inserted
                $data = [
                    'name' => $key,
                    'created_by' => creatorId(),
                    'company_id' => Auth::user()->company_id,

                ];

                // Check if the record exists, and update or insert accordingly
                Settings::updateOrInsert($data, ['value' => $value]);

            }
            // Settings Cache forget
            companySettingCacheForget(creatorId());

            return redirect()->back()->with('success',__('Ratings setting save sucessfully.'));

        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
