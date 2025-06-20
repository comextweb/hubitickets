<?php
// This file use for handle company setting page

namespace Workdo\Webhook\Http\Controllers\Company;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workdo\Webhook\Entities\Webhook;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($settings)
    {
        $webhook_module = Webhook::where('created_by',creatorId())->get();

        return view('webhook::company.settings.index',compact('webhook_module'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }
}
