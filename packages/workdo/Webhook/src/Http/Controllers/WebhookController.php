<?php

namespace Workdo\Webhook\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Workdo\Webhook\Entities\Webhook;
use Workdo\Webhook\Entities\WebhookModule;

class WebhookController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return redirect()->back();
        return view('webhook::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if (Auth::user()->isAbleTo('webhook create')) {
            $modules = WebhookModule::select('module', 'submodule')->where('type', 'admin')->get();
            $webhookModule = [];
            foreach ($modules as $module) {
                if (moduleIsActive($module->module) || $module->module == 'general') {
                    $sub_modules = WebhookModule::select('id', 'module', 'submodule')->where('module', $module->module)->where('type', 'admin')->get();
                    $temp = [];
                    foreach ($sub_modules as $sub_module) {
                        $temp[$sub_module->id] = $sub_module->submodule;
                    }
                    $webhookModule[moduleAliasName($module->module)] = $temp;
                }
            }
            return view('webhook::webhook.create', compact('webhookModule'));
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('webhook create')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'module' => 'required',
                    'method' => 'required',
                    'url' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $webhook =  Webhook::where('action', $request->module)->where('method', $request->method)->first();
            if (empty($webhook)) {
                $webhook = new Webhook();
                $webhook->method = $request->method;
                $webhook->action = $request->module;
                $webhook->url = $request->url;
                $webhook->created_by = creatorId();
                $webhook->save();

                return redirect()->back()->with('success', __('Webhook Setting successfully created!'));
            } else {
                return redirect()->back()->with('error', __('The module has already been taken.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return redirect()->back();
        return view('webhook::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('webhook edit')) {
            $methods = ['GET' => 'GET', 'POST' => 'POST'];
            $webhook = Webhook::find($id);
            if ($webhook) {
                $modules = WebhookModule::select('module', 'submodule')->where('type', 'admin')->get();
                $webhookModule = [];
                foreach ($modules as $module) {
                    if (moduleIsActive($module->module) || $module->module == 'general') {
                        $sub_modules = WebhookModule::select('id', 'module', 'submodule')->where('module', $module->module)->where('type', 'admin')->get();
                        $temp = [];
                        foreach ($sub_modules as $sub_module) {
                            $temp[$sub_module->id] = $sub_module->submodule;
                        }
                        $webhookModule[moduleAliasName($module->module)] = $temp;
                    }
                }
                return view('webhook::webhook.edit', compact('webhookModule', 'webhook', 'methods'));
            } else {
                return redirect()->back()->with('error', __('Something went wrong!'));
            }
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('webhook edit')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'module' => 'required',
                    'method' => 'required',
                    'url' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $check_webhook = Webhook::where('action', $request->module)->where('method', $request->method)->first();
            if ($check_webhook) {
                return redirect()->back()->with('error', __('The module has already been taken.'));
            }

            $webhook = Webhook::find($id);
            if ($webhook) {
                $webhook->method = $request->method;
                $webhook->action = $request->module;
                $webhook->url = $request->url;
                $webhook->created_by = creatorId();
                $webhook->update();
                return redirect()->back()->with('success', __('Webhook Setting successfully Updated!'));
            } else {
                return redirect()->back()->with('error', __('Something went wrong!'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Webhook $webhook)
    {
        if (Auth::user()->isAbleTo('webhook delete')) {
            $webhook->delete();
            return redirect()->back()->with('success', __('Webhook Setting successfully deleted!'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
}
