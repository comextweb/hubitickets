<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Priority;
use App\Models\SubCategory;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Facades\AddonFacade as AddOnFacade;
use Illuminate\Support\Facades\File;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('2fa');
    }

    public function index(Request $request)
    {
        if (Auth::user()->isAbleTo('dashboard manage')) {
            if (Auth::user()->hasRole('admin')) {

                // Check Migrations For Updater
                $alreadyRunnedMigrations = DB::table('migrations')->pluck('migration');
                $getAllModules = AddOnFacade::allModules();
                $baseMigrationsFiles = collect(File::glob(database_path('migrations/*.php')))
                    ->map(function ($path) {
                        return File::name($path);
                    });

                foreach ($getAllModules as $key => $module) {
                    $directory = "packages/workdo/" . $module->name . "/src/Database/Migrations";
                    $modulesMigrations = collect(File::glob("{$directory}/*.php"))->map(function ($path) {
                        return File::name($path);
                    });

                    // Merge Modules Migrations files with basecode migrations files collection
                    $baseMigrationsFiles = $baseMigrationsFiles->merge($modulesMigrations);
                }
                // Count Total Pending Migrations
                $pendingMigrations = $baseMigrationsFiles->diff($alreadyRunnedMigrations);
                if (count($pendingMigrations) > 0) {
                    return redirect()->route('LaravelUpdater::welcome');
                }


                $categories = Category::count();
                $open_ticket = Ticket::whereIn('status', ['On Hold', 'In Progress'])->count();
                $close_ticket = Ticket::where('status', '=', 'Closed')->count();
                $agents = User::where('created_by', creatorId())->where('type', 'agent')->count();

                // Category Wise Total Ticket Number Chart
                // $categoriesChart = Category::withCount('getTickets')->get();

                $categoriesChart = Category::withCount('getTickets')->whereHas('getTickets', function ($query) {
                    $query->whereColumn('tickets.category_id', 'categories.id');
                })->get();

                $chartData = ['color' => [], 'name' => [], 'value' => []];
                foreach ($categoriesChart as $category) {
                    $chartData['name'][] = $category->name;
                    $chartData['value'][] = $category->get_tickets_count;
                    $chartData['color'][] = $category->color;
                }
                // Yearly Ticket Chart
                $monthData = [];
                $barChart = Ticket::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, count(*) as total')
                    ->where('created_at', '>', now()->subYear())
                    ->groupByRaw('MONTH(created_at), YEAR(created_at)')
                    ->get();
                $start = \Carbon\Carbon::now()->startOfYear();
                for ($i = 0; $i <= 11; $i++) {
                    $monthData[$start->format('M')] = 0;
                    foreach ($barChart as $chart) {
                        if (intval($chart->month) == intval($start->format('m'))) {
                            $monthData[$start->format('M')] = $chart->total;
                        }
                    }
                    $start->addMonth();
                }

                // Ticket Status wise Chart
                $statusChart = Ticket::all()->groupBy('status')->map(function ($tickets) {
                    return $tickets->count();
                });
                $statusData = [
                    'name' => [],
                    'value' => []
                ];
                foreach ($statusChart as $key => $status) {
                    $statusData['name'][] = $key;
                    $statusData['value'][] = $status;
                }

                // Priority Wise Chart
                $priorityChart = Priority::withCount('getAllTickets')->where('created_by', creatorId())->get();
                $priorityData = [
                    'name' => [],
                    'color' => [],
                    'value' => [],
                ];
                foreach ($priorityChart as $priority) {
                    $priorityData['name'][] = $priority->name;
                    $priorityData['value'][] = $priority->get_all_tickets_count;
                    $priorityData['color'][] = $priority->color;
                }

                // Total Number of Tickets Assign to the Agents.
                $allAgents = User::withCount('getAssignTickets')->where('type', 'agent')->get();
                $agentNames = [];
                $totalTicketCount = [];
                foreach ($allAgents as $agent) {
                    $agentNames[] = $agent->name;
                    $totalTicketCount[] = $agent->get_assign_tickets_count;
                }
                $totalTicketAgentWise = [
                    'agent_names' => $agentNames,
                    'ticket_counts' => $totalTicketCount
                ];


                return view('admin.dashboard.index', compact('categories', 'open_ticket', 'close_ticket', 'agents', 'chartData', 'monthData', 'statusData', 'priorityData', 'totalTicketAgentWise'));
            } else {

                $totalAssignTickets = Ticket::where('is_assign', Auth::user()->id)->count();
                $openTicket = Ticket::whereIn('status', ['On Hold', 'In Progress'])->where('is_assign', Auth::user()->id)->count();
                $closeTickets = Ticket::where('status', '=', 'Closed')->where('is_assign', Auth::user()->id)->count();

                // Category Wise Total Ticket Number Chart
                $categoriesChart = Category::withCount('getTickets')
                    ->whereHas('getTickets', function ($query) {
                        $query->where('is_assign', Auth::user()->id);
                    })->get();
                $chartData = ['color' => [], 'name' => [], 'value' => []];
                foreach ($categoriesChart as $category) {
                    $chartData['name'][] = $category->name;
                    $chartData['value'][] = $category->get_tickets_count;
                    $chartData['color'][] = $category->color;
                }

                //  Ticket StatusWise Total Number Of Tickets
                $statusChart = Ticket::all()->groupBy('status')->map(function ($tickets) {
                    return $tickets->where('is_assign', Auth::user()->id)->count();
                });
                $statusData = [
                    'name' => [],
                    'value' => []
                ];
                foreach ($statusChart as $key => $status) {
                    $statusData['name'][] = $key;
                    $statusData['value'][] = $status;
                }

                // Ticket Priority Wise Total Number Of Tickets
                $priorityChart = Ticket::where('is_assign', Auth::user()->id)
                    ->whereNotNull('priority')
                    ->with('getPriority')
                    ->get()
                    ->groupBy('priority');

                $groupedTickets = $priorityChart->map(function ($tickets, $priorityId) {
                    $priorityName = isset($tickets->first()->getPriority) ? $tickets->first()->getPriority->name : '';
                    $priorityColor = isset($tickets->first()->getPriority) ? $tickets->first()->getPriority->color : '';

                    return [
                        'priority_name' => $priorityName,
                        'total_tickets' => $tickets->count(),
                        'color' => $priorityColor,
                    ];
                });


                $priorityData = [
                    'name' => [],
                    'color' => [],
                    'value' => [],
                ];
                foreach ($groupedTickets as $priority) {
                    $priorityData['name'][] = $priority['priority_name'];
                    $priorityData['value'][] = $priority['total_tickets'];
                    $priorityData['color'][] = $priority['color'];
                }

                $monthData = [];
                $barChart = Ticket::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, count(*) as total')
                    ->where('created_at', '>', now()->subYear())
                    ->groupByRaw('MONTH(created_at), YEAR(created_at)')
                    ->where('is_assign', Auth::user()->id)
                    ->get();
                $start = \Carbon\Carbon::now()->startOfYear();
                for ($i = 0; $i <= 11; $i++) {
                    $monthData[$start->format('M')] = 0;
                    foreach ($barChart as $chart) {
                        if (intval($chart->month) == intval($start->format('m'))) {
                            $monthData[$start->format('M')] = $chart->total;
                        }
                    }
                    $start->addMonth();
                }
                return view('admin.users.dashboard', compact('openTicket', 'closeTickets', 'totalAssignTickets', 'chartData', 'statusData', 'priorityChart', 'priorityData', 'monthData'));
            }
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }
}
