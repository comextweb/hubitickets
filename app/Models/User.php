<?php

namespace App\Models;

use App\Traits\UserTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laratrust\Traits\HasRolesAndPermissions;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Laratrust\Contracts\LaratrustUser;

class User extends Authenticatable implements LaratrustUser
{
    use Notifiable, UserTrait, HasApiTokens, HasRolesAndPermissions;

    protected $fillable = [
        'name',
        'email',
        'password',
        'parent',
        'type',
        'is_enable_login',
        'avatar',
        'category_id',
        'created_by'
    ];

    // protected $appends = [
    //     'profilelink',
    //     'avatarlink',
    //     'isme',
    // ];

    public static $adminDefaultActivatedModules = [];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function languages()
    {

        $dir     = base_path() . '/resources/lang/';
        $glob    = glob($dir . "*", GLOB_ONLYDIR);
        $arrLang = array_map(
            function ($value) use ($dir) {
                return str_replace($dir, '', $value);
            },
            $glob
        );
        $arrLang = array_map(
            function ($value) use ($dir) {
                return preg_replace('/[0-9]+/', '', $value);
            },
            $arrLang
        );
        $arrLang = array_filter($arrLang);

        return $arrLang;
    }

    public function currantLang()
    {
        return $this->isAbleTo('lang-change') ? $this->lang : $this->parentUser()->lang;
    }

    public function currantLangPath()
    {
        if ($this->isAbleTo('lang-change')) {
            $lang = $this->lang;
            $dir  = base_path() . '/resources/lang/' . $lang . "/";
            if (!is_dir($dir) && $this->roles[0]->name != 'Admin') {
                $lang = $this->lang;
            }
        } else {
            $lang = $this->parentUser()->lang;
        }
        $dir = base_path() . '/resources/lang/' . $lang . "/";
        return is_dir($dir) ? $lang : 'en';
    }

    public function getCreatedBy()
    {
        $roles = $this->roles();
        return $roles == '["admin"]' ? $this->id : $this->parent;
    }

    public function parentUser()
    {
        return $this->hasOne('App\Models\User', 'id', 'parent')->first();
    }

    public function unread()
    {
        return Message::where('from', '=', $this->id)->where('is_read', '=', 0)->count();
    }

    public static function setEnvironmentValue(array $values)
    {
        $envFile = app()->environmentFilePath();
        $str     = file_get_contents($envFile);
        if (count($values) > 0) {
            foreach ($values as $envKey => $envValue) {
                $keyPosition       = strpos($str, "{$envKey}=");
                $endOfLinePosition = strpos($str, "\n", $keyPosition);
                $oldLine           = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);

                // If key does not exist, add it
                if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                    $str .= "{$envKey}='{$envValue}'\n";
                } else {
                    $str = str_replace($oldLine, "{$envKey}='{$envValue}'", $str);
                }
            }
        }
        $str = substr($str, 0, -1) . "\n";

        return file_put_contents($envFile, $str) ? true : false;
    }

    public static function delete_directory($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }
        if (!is_dir($dir)) {
            return unlink($dir);
        }
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            if (!self::delete_directory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }
        return rmdir($dir);
    }
    public static function userDefaultDataRegister($user_id)
    {
        // Make Entry In User_Email_Template
        $allEmail = EmailTemplate::all();

        foreach ($allEmail as $email) {
            UserEmailTemplate::create(
                [
                    'template_id' => $email->id,
                    'user_id' => $user_id,
                    'is_active' => 1,
                ]
            );
        }
    }

    public function createId()
    {
        return $this->parent == 0 ? $this->id : $this->parent;
    }


    public function getCategoryIdsAttribute()
    {
        return explode(',', $this->category_id);
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'id', 'category_id')
            ->whereIn('id', $this->getCategoryIdsAttribute());
    }


    public function getCategory()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function getAssignTickets()
    {
        return $this->hasMany(Ticket::class, 'is_assign', 'id');
    }

    public static $nonEditableRoles = [
        'agent',
        'customer',
    ];
}
