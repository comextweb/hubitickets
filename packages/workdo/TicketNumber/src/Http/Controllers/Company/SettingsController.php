<?php
// This file use for handle company setting page

namespace Workdo\TicketNumber\Http\Controllers\Company;

use App\Models\Settings;
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
        return view('ticket-number::company.settings.index', compact('settings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }
    public function setting(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'ticket_number_prefix' => 'required',
            ]
        );
        if ($validator->fails()) {
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
            ];

            // Check if the record exists, and update or insert accordingly
            Settings::updateOrInsert($data, ['value' => $value]);
        }
        companySettingCacheForget(creatorId());

        return redirect()->back()->with('success', __('Ticket Number Setting save successfully'));
    }
}
