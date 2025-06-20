<?php

namespace App\Models;

use App\Traits\BelongsToCompany;
use Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use BelongsToCompany;

    protected $fillable = [
        'name', 'value', 'created_by','company_id'
    ];

    public function settings($key)
    {
        static $settings;

        if(is_null($settings))
        {
            $settings = Cache::remember(
                'settings', 24 * 60, function (){
                return Settings::all()->pluck('value', 'key');
            }
            );
        }

        return (is_array($key)) ? array_only($settings, $key) : $settings[$key];
    }

       
}
