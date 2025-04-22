<?php

namespace App\Exports;

use App\Models\Ticket;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TicketsExport implements FromCollection ,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = Ticket::with(['getCategory','getPriority'])->get(); 

   
        foreach ($data as $k => $ticket) {
            $category = $ticket->getCategory ? $ticket->getCategory->name : '-'; 
            $priority = $ticket->getPriority ? $ticket->getPriority->name : '-'; 


            // Check if 'tags_id' field exists in the tickets table
            if (Schema::hasColumn('tickets', 'tags_id')) {
                    unset($ticket->tags_id);  // Unset 'tags_id' if it exists
            }

            // Check if 'is_mark' field exists in the tickets table
            if (Schema::hasColumn('tickets', 'is_mark')) {
                unset($ticket->is_mark);  // Unset 'is_mark' if it exists
            }

            // Check if 'is_pin' field exists in the tickets table
            if (Schema::hasColumn('tickets', 'is_pin')) {
                unset($ticket->is_pin);  // Unset 'is_pin' if it exists
            }
            // Remove unnecessary fields
            unset($ticket->id, $ticket->attachments, $ticket->reslove_at,$ticket->note, $ticket->created_by, $ticket->is_assign,$ticket->created_at, $ticket->updated_at);

            $data[$k]['category_id'] = $category;
            $data[$k]['priority'] = $priority;
        }
      

        return $data;
    }

    public function headings(): array
    {
        return [
            "Ticket ID",
            "Name",
            "Email",
            "Mobile No",
            "Category",
            "Priority",
            "Subject",
            "Status",
            "Type",
            "Description",
        ];
    }
}



