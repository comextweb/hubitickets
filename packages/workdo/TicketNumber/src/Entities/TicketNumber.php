<?php

namespace Workdo\TicketNumber\Entities;

use App\Models\Settings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketNumber extends Model
{
    use HasFactory;

    protected $fillable = [];

    public static function ticketNumberFormat($id)
    {
        $settings = getCompanyAllSettings();
        $data = !empty($settings['ticket_number_prefix']) ? $settings['ticket_number_prefix'] : '';

        return $data . sprintf("%05d", $id);
    }

    public static function ticketNumberPrefix()
    {
        $settings = getCompanyAllSettings();
        if(!isset($settings['ticket_number_prefix']))
        {
            Settings::updateOrInsert(['name' => 'ticket_number_prefix','created_by' => creatorId()], ['value' => '#Ticket']);
        }
    }
}
