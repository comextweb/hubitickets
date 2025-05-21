<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Category;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    use ApiResponser;

    public function index(Request $request)
    {    
            $user         = User::find($request['id']);
            $categories   = Category::count();
            $open_ticket  = Ticket::whereIn('status', ['On Hold','In Progress'])->count();
            $close_ticket = Ticket::where('status', '=', 'Closed')->count();
            $agents       = User::where('created_by', creatorId())->count();
            $today_ticket = Ticket::whereDate('created_at', Carbon::today())->count();
    
            // Latest Ticket
            $tickets      = Ticket::select('tickets.id','tickets.ticket_id','tickets.name','tickets.email','tickets.is_assign','categories.name as category','categories.color as color','tickets.subject','tickets.status','tickets.description','tickets.note','tickets.attachments')->join('categories', 'categories.id', '=', 'tickets.category_id')->orderBy('id', 'desc')->take(5)->where('tickets.created_by', creatorId())->get()        
            ->map(function ($ticket) {            
                $ticket->attachments = json_decode($ticket->attachments, true) ?? [];
                return $ticket;
            });
            // Start Categories Analytics
            $categoriesChart = Ticket::select(
                [
                    'tickets.category_id',
                    'categories.name',
                    'categories.color',
                    DB::raw('count(*) as total'),
                ]
            )->join('categories', 'categories.id', '=', 'tickets.category_id')->groupBy('categories.id')->get();
        
            $total_cat_ticket   = Ticket::count();
    
            if(count($categoriesChart) > 0)
            {
                foreach($categoriesChart as $category)
                {
                
                    $cat_ticket = round((float)(($category->total / 100) * $total_cat_ticket) * 100);
    
                    $chartData[]=[
                        'category' => $category->name,
                        'color'    => $category->color,
                        'value'    => $cat_ticket,
                    ];
                }
            }
            // End Categories Analytics
    
            // Start Ticket Analytics
            $anew_ticket    = Ticket::whereDate('created_at', Carbon::today())->count();
            $aopen_ticket   = Ticket::whereIn('status', ['On Hold','In Progress'])->count();
            $aclose_ticket  = Ticket::where('status', '=', 'Closed')->count();
    
    
            $atotal_ticket  = $anew_ticket+$aopen_ticket+$aclose_ticket;
    
            if($atotal_ticket != 0)
            {
                $anew_ticket    = round((float)((100 * $anew_ticket)/$atotal_ticket));
                $aopen_ticket   = round((float)((100 * $aopen_ticket)/$atotal_ticket));
                $aclose_ticket  = round((float)((100 * $aclose_ticket)/$atotal_ticket));
            }

            $ticket_analytics = [
                'new_ticket'   => $anew_ticket,
                'open_ticket'  => $aopen_ticket,
                'close_ticket' => $aclose_ticket
            ];
            // End Ticket Analytics
    
            $datagrph = Ticket::getIncExpLineChartDate();
    
            $y[] = [
                'name'  =>"Open Ticket",
                'color' => "#6FD943",
                'data'  => $datagrph['open_ticket'],
            ];
            $y[] = [
                'name'  =>"Close Ticket",
                'color' => "#FF3a6e",
                'data'  => $datagrph['close_ticket'],
                ];
    
            $graph_data = [
                'x_axis' => $datagrph['day'],
                'y_axis' => $y
            ];                    
         
            if($user)
            {
                $user = [
                    'id'            => 1,
                    'name'          => $user->name,
                    'email'         => $user->email,
                    'image_url'     => asset(\Storage::url('public/public/'.$user->avatar)),
                    'total_ticket'  => $today_ticket,
                ];
            }
    
            $statistics = [
                'category'     => $categories,
                'open_ticket'  => $open_ticket,
                'close_ticket' => $close_ticket,
                'agents'       => $agents
            ];
    
            $data = [
                'user_data'          => $user,
                'statistics'         => $statistics,
                'last_ticket'        => $tickets,
                'graph_data'         => $graph_data,
                'category_analytics' => $chartData,
                'ticket_analytics'   => $ticket_analytics
            ];
            return $this->success($data);
    }
}