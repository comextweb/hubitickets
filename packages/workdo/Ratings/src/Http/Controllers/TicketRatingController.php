<?php

namespace Workdo\Ratings\Http\Controllers;

use App\Models\Ticket;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Workdo\Ratings\Entities\TicketRating;

class TicketRatingController extends Controller
{
    public function index()
    {
        if(Auth::user()->isAbleTo('ratings manage'))
        {
            $ticketRatings = TicketRating::where('created_by' , creatorId())->orderBy('id' , 'desc')->get();

            return view('ratings::ratings.index' , compact('ticketRatings'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied'));
        }
    }

    public function edit($id)
    {
        if(Auth::user()->isAbleTo('ratings edit'))
        {
            $ticketRatings = TicketRating::where('created_by' , creatorId())->get()->pluck('ticket_id')->toArray();
            $tickets       = Ticket::where('created_by' , creatorId())->get();
            $rating        = TicketRating::find($id);

            return view('ratings::ratings.edit' , compact('tickets' , 'rating' , 'ticketRatings'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied'));
        }
    }


    public function update(Request $request , $id)
    {
        if(Auth::user()->isAbleTo('ratings create'))
        {
            $ticket    = Ticket::find($request->ticket);
            $validator = Validator::make(
                $request->all(),
                [
                    'rating'      => 'required',
                    'description' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $ticketRating  = TicketRating::find($id);
            if ($ticket) {
                $ticketRating->rating_date     = $request->rating_date;
                $ticketRating->rating          = $request->rating;
                $ticketRating->description     = $request->description;
                $ticketRating->created_by      = creatorId();
                $ticketRating->save();
    
                return redirect()->back()->with('success', __('Ticket Rating updated successfully'));
            } else {
                return redirect()->back()->with('error', __('Something went wrong'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied'));
        }
    }
    
    public function destroy($id)
    {
        if (Auth::user()->isAbleTo('ratings delete')) {

            $ticketRating  = TicketRating::find($id);
            if ($ticketRating) {
                $ticketRating->delete();
            }
            return redirect()->back()->with('success', __('Ticket Rating deleted Successfully'));
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }

    public function ratingPage(Request $request , $id = '')
    {
        try
        {
            $id = Crypt::decrypt($id);
            $ticket = Ticket::find($id);
        
            $existingRating = TicketRating::where('ticket_id', $id)
            ->where('customer', $ticket->name)
            ->first();
    
            return view('ratings::rating' , compact('ticket' , 'existingRating'));
        }
        catch(Exception $e)
        {
            return redirect()->back()->with('error', __('Ticket Not Found'));
        }
    }

    public function ratingStore(Request $request)
    {
        $ticket = Ticket::find($request->ticket_id);
        $validator = Validator::make(
            $request->all(),
            [
                'rating'      => 'required',
                'description' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
        if ($ticket) {
            $ticketRating                  = new TicketRating();
            $ticketRating->ticket_id       = $request->ticket_id;
            $ticketRating->customer        = $request->customer;
            $ticketRating->user_id         = $request->user_id ?? 0;
            $ticketRating->rating_date     = date('Y-m-d');
            $ticketRating->rating          = $request->rating;
            $ticketRating->description     = $request->description;
            $ticketRating->created_by      = $ticket->created_by;
            $ticketRating->save();

            return redirect()->back()->with('success', __('Ticket Rating saved successfully'));
        } else {
            return redirect()->back()->with('error', __('Ticket Not Found'));
        }
    }
}
