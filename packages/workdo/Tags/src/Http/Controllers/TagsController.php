<?php

namespace Workdo\Tags\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Workdo\Tags\Entities\Tags;

class TagsController extends Controller
{
    public function index()
    {
        if (Auth::user()->isAbleTo('tags manage')) {
            $tags = Tags::where('company_id',Auth::user()->company_id ) // se coloca company_id para filtrar por empresa, porque el trait no funciona con el modelo de Tags
            ->get()->map(function ($tag) { 
                $count = DB::table('tickets')
                    ->whereNotNull('tags_id')
                    ->whereRaw("FIND_IN_SET(?, tags_id)", [$tag->id])
                    ->count();

                return [
                    'name'  => $tag->name ?? '',
                    'color' => $tag->color ?? '',
                    'count' => $count ?? '',
                    'id'    => $tag->id ?? ''
                ];
            });
            return view('tags::tags.index', compact('tags'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->isAbleTo('tags create')) {
            return view('tags::tags.create');
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('tags create')) {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'color' => 'required'
            ]);

            if ($validator->fails()) {
                $message = $validator->getMessageBag();
                return redirect()->back()->with('error', $message->first());
            }

            $saveReply = new Tags();
            $saveReply->name = $request->name ?? '';
            $saveReply->color = $request->color ?? '';
            $saveReply->created_by = creatorId();
            $saveReply->save();

            return redirect()->back()->with('success', __('Tags created successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function show($id)
    {
        return view('tags::show');
    }


    public function edit($id)
    {
        if (Auth::user()->isAbleTo('tags edit')) {
            $tag = Tags::find($id);
            if ($tag) {
                return view('tags::tags.edit', compact('tag'));
            } else {
                return redirect()->back()->with('error', 'Tag is not Found.');
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('tags edit')) {
            $tag = Tags::find($id);
            if ($tag) {
                $tag->name = $request->name;
                $tag->color = $request->color;
                $tag->save();
                return redirect()->back()->with('success', __('Tag update successfully.'));
            } else {
                return redirect()->back()->with('error', 'Tag is not Found.');
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function destroy($id)
    {
        if (Auth::user()->isAbleTo('tags delete')) {
            $tag = Tags::find($id);
            if ($tag) {
                $isTagUsed = Ticket::whereRaw("FIND_IN_SET(?, tags_id)", [$id])->exists();
                if ($isTagUsed) {
                    return redirect()->back()->with('error', __('This tag is being used and cannot be deleted.'));
                }
                $tag->delete();
                return redirect()->back()->with('success', __('Tag delete successfully.'));
            } else {
                return redirect()->back()->with('error', 'Tag is not Found.');
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function assignTags(Request $request, $id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json(['status' => 'error', 'message' => 'Ticket not found'], 404);
        }

        $ticket->tags_id = implode(',', $request->input('tags', []));
        $ticket->save();

        return response()->json(['status' => 'success', 'message' => 'Tags assigned successfully']);
    }

    public function getTags(Request $request, $ticketId)
    {

        $ticket = Ticket::where('id', $ticketId)->first();
        if ($ticket) {
            $tags = Tags::where('company_id', Auth::user()->company_id)->get(); // se coloca company_id para filtrar por empresa, porque el trait no funciona con el modelo de Tags
                
            return view('tags::ticket.tags', compact('tags', 'ticket'));
        } else {
            return response()->json(['error' => __('Ticket Not Found.')], 401);
        }

    }
}
