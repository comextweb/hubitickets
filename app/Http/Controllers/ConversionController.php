<?php

namespace App\Http\Controllers;

use App\Events\TicketReply;
use App\Models\Conversion;
use App\Mail\SendTicketAdminReply;
use App\Models\Ticket;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ConversionController extends Controller
{
    public function store(Request $request, $ticket_id)
    {
        $user = Auth::user();
        if ($user->isAbleTo('ticket reply')) {
            $ticket = Ticket::find($ticket_id);
            if ($ticket) {
                // $request->validate([
                //     'reply_description' => 'required'
                // ]);
                $conversion = new Conversion();
                $conversion->sender = isset($user) ? $user->id : 'user';
                $conversion->ticket_id = $ticket->id;
                $conversion->description = $request->reply_description;
                if ($request->hasfile('reply_attachments')) {
                    $errors = [];
                    foreach ($request->file('reply_attachments') as $filekey => $file) {
                        $fileNameWithExt = $file->getClientOriginalName();
                        $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                        $extention = $file->getClientOriginalExtension();
                        $filenameToStore = $fileName . '_' . time() . '.' . $extention;

                        $dir = ('tickets/' . $ticket->ticket_id);
                        $path = multipleFileUpload($file, 'reply_attachments', $filenameToStore, $dir);

                        if ($path['flag'] == 1) {
                            $data[] = $path['url'];
                        } elseif ($path['flag'] == 0) {
                            $errors = __($path['msg']);
                        }
                    }
                    $conversion->attachments = isset($data) ? json_encode($data) : '';
                }
                $conversion->save();
                Conversion::change_status($ticket_id);
                $settings = getCompanyAllSettings();

                event(new TicketReply($conversion,$request));

                // Send Reply Email To The Customer
                $error_msg = '';
                sendTicketEmail('Reply Mail To Customer',$settings,$ticket,$request,$error_msg);

                return redirect()->back()->with('success', __('Reply added successfully') . ((isset($error_msg)) ? '<br> <span class="text-danger">' . $error_msg . '</span>' : ''));
            } else {
                return redirect()->back()->with('error', 'Ticket Not Found.');
            }
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }
}
