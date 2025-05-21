<?php

namespace App\Http\Controllers;

use App\Events\CreateTicket;
use App\Events\DestroyTicket;
use App\Events\UpdateTicket;
use App\Models\Category;
use App\Models\CustomField;
use App\Mail\SendCloseTicket;
use App\Mail\SendTicket;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Utility;
use App\Models\UserCatgory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Exports\TicketsExport;
use App\Models\Conversion;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Priority;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Exception;

class TicketController extends Controller
{

    public function __construct()
    {
        $this->middleware('2fa');
    }

    public function create()
    {
        if (Auth::user()->isAbleTo('ticket create')) {
            $customFields = CustomField::where('id', '>', '7')->get();
            $categories = Category::where('created_by', creatorId())->get();
            $categoryTree = buildCategoryTree($categories);
            $priorities = Priority::where('created_by', creatorId())->get();
            $settings = getCompanyAllSettings();
            $users = User::where('type', 'agent')->get();
            $ticket = null;
            return view('admin.tickets.create', compact('categories', 'customFields', 'priorities', 'settings', 'categoryTree', 'ticket', 'users'));
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('ticket create')) {
            $validation = [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'category' => 'required',
                'priority' => 'required',
                'subject' => 'required|string|max:255',
                'status' => 'required|string|max:100',
                'description' => 'required',
                'priority' => 'required',
                'agent' => 'required',
            ];

            $this->validate($request, $validation);

            $ticket = new Ticket();
            $ticket->ticket_id = time();
            $ticket->name = $request->name;
            $ticket->email = $request->email;
            $ticket->category_id = $request->category;
            $ticket->is_assign = $request->agent;
            $ticket->priority = $request->priority;
            $ticket->subject = $request->subject;
            $ticket->status = $request->status;
            $ticket->description = $request->description;
            $ticket->type = "Assigned";
            $ticket->created_by = Auth::check() ? Auth::user()->id : creatorId();
            $data = [];

            if ($request->hasfile('attachments')) {
                $errors = [];
                foreach ($request->file('attachments') as $filekey => $file) {
                    $filenameWithExt = $file->getClientOriginalName();
                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension = $file->getClientOriginalExtension();
                    $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                    $dir = ('tickets/' . $ticket->ticket_id);
                    $path = multipleFileUpload($file, 'attachments', $fileNameToStore, $dir);
                    if ($path['flag'] == 1) {
                        $data[] = $path['url'];
                    } elseif ($path['flag'] == 0) {
                        $errors = __($path['msg']);
                        return redirect()->back()->with('error', __($errors));
                    }
                }
            }
            $ticket->attachments = json_encode($data);
            $ticket->save();

            // Find the Agents based on selected Category.
            // $agent = getRandomAgent($request->category);
            // $ticket->is_assign = $agent->id;
            // $ticket->save();

            CustomField::saveData($ticket, $request->customField);

            $settings = getCompanyAllSettings();

            event(new CreateTicket($ticket, $request));

            $error_msg = '';
            // Send Ticket Email To The Agent , Customer & Admin
            sendTicketEmail('Send Mail To Agent', $settings, $ticket, $request, $error_msg);
            sendTicketEmail('Send Mail To Customer', $settings, $ticket, $request, $error_msg);
            sendTicketEmail('Send Mail To Admin', $settings, $ticket, $request, $error_msg);

            if (isset($error_msg)) {
                Session::put('smtp_error', '<br><span class="text-danger ml-2">' . $error_msg . '</span>');
            }

            Session::put('ticket_id', ' <a class="text text-primary" target="_blank" href="' . route('home.view', encrypt($ticket->ticket_id)) . '"><b>' . __('Your unique ticket link is this.') . '</b></a>');
            return redirect()->route('admin.new.chat')->with('success', __('Ticket created successfully'));
        } else {
            return redirect()->back() - with('error', 'Permission Denied');
        }
    }


    public function destroy($id)
    {
        if (Auth::user()->isAbleTo('ticket delete')) {
            try {
                $id = decrypt($id);
            } catch (Exception $e) {
                return redirect()->back()->with('error', __($e->getMessage()));
            }
            $ticket = Ticket::with('conversions')->where('ticket_id',$id)->first();
            if ($ticket) {
                event(new DestroyTicket($ticket));
                $ticketImageDirectory = ('uploads/tickets/' . $ticket->ticket_id);
                if (checkFile($ticketImageDirectory)) {
                    File::deleteDirectory($ticketImageDirectory);
                }
                if ($ticket->conversions->isNotEmpty()) {
                    $ticket->conversions->each(function ($conversion) {
                        $conversion->delete();
                    });
                }
                $ticket->delete();

                return redirect()->back()->with('success', __('Ticket deleted successfully'));
            } else {
                return redirect()->back()->with('error', 'Ticket Not Found.');
            }
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }

    public function attachmentDestroy($ticket_id, $id)
    {
        if (Auth::user()->isAbleTo('ticket edit')) {
            $ticket = Ticket::find($ticket_id);
            $attachments = json_decode($ticket->attachments);
            if (isset($attachments[$id])) {
                if (asset(Storage::exists('tickets/' . $ticket->ticket_id . "/" . $attachments[$id]))) {
                    asset(Storage::delete('tickets/' . $ticket->ticket_id . "/" . $attachments[$id]));
                }
                unset($attachments[$id]);
                $ticket->attachments = json_encode(array_values($attachments));
                $ticket->save();

                return redirect()->back()->with('success', __('Attachment deleted successfully'));
            } else {
                return redirect()->back()->with('error', __('Attachment is missing'));
            }
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }

    public function export()
    {

        if (Auth::user()->isAbleTo('ticket export')) {
            $name = 'Tickets' . date('Y-m-d i:h:s');
            $data = Excel::download(new TicketsExport(), $name . '.csv');
            return $data;
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }
}
