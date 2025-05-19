<?php

use App\Events\CompanySettingEvenet;
use App\Events\CompanySettingMenuEvent;
use App\Menu;
use App\CompanySetting;
use App\Models\Category;
use App\Models\Languages;
use App\Models\NotificationTemplateLangs;
use App\Models\Permission;
use App\Models\Settings;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Events\CompanySettingEvent;
use App\Facades\AddonFacade as AddOnFacade;
use App\Models\NotificationTemplates;
use App\Models\Utility;

// get Company Sidebar Menu
if (!function_exists('getMenu')) {
    function getMenu()
    {
        $user = Auth::user();
        return Cache::rememberForever('sidebar_menu_' . $user->id, function () use ($user) {
            $role = $user->roles->first();
            $menu = new Menu($user);
            event(new \App\Events\CompanyMenuEvent($menu));
            return generateMenu($menu->menu, null);
        });
    }
}
// generate Compoany Sidebar Menu
if (!function_exists(function: 'generateMenu')) {
    function generateMenu($menuItems, $parent = null)
    {
        $html = '';
        // fiter the array and return which menu parent is null
        $filteredItems = array_filter($menuItems, function ($item) use ($parent) {
            return $item['parent'] == $parent;
        });

        // sort the array based on priority (less priority comes on top)
        usort($filteredItems, function ($a, $b) {
            return $a['order'] - $b['order'];
        });


        foreach ($filteredItems as $item) {
            // check the current menu have a children or not
            $hasChildren = hasChildren($menuItems, $item['name']);
            if ($item['parent'] == null) {
                $html .= '<li class="dash-item dash-hasmenu">';
            } else {
                $html .= '<li class="dash-item">';
            }

            if ($item['name'] == 'add-on-manager') {
                $html .= '<a href="' . (!empty($item['route']) ? route($item['route']) : '#!') . '" class="dash-link d-flex align-items-center">';
                if ($item['parent'] == null) {
                    $html .= ' <span class="dash-micon">
                                 <i class="ti ti-' . $item['icon'] . '">
                                 </i>
                                </span>
                    <span class="dash-mtext">
                    <div class="text-center"> <span class="dash-mtext">';
                    $html .= __($item['title']) . '</span> <span class="text-center d-block animate-charcter">Premium</span></div>';
                }
            } else {
                $html .= '<a href="' . (!empty($item['route']) ? route($item['route']) : '#!') . '" class="dash-link">';
                if ($item['parent'] == null) {
                    $html .= ' <span class="dash-micon">
                                 <i class="ti ti-' . $item['icon'] . '">
                                 </i>
                                </span>
                    <span class="dash-mtext">';
                }
                $html .= __($item['title']) . '</span>';
            }


            if ($hasChildren) {
                $html .= '<span class="dash-arrow"> <i data-feather="chevron-right"></i> </span> </a>';
                $html .= '<ul class="dash-submenu">';
                $html .= generateMenu($menuItems, $item['name']);
                $html .= '</ul>';
            } else {
                $html .= '</a>';
            }
            $html .= '</li>';
        }
        return $html;
    }
}

// check The Children name with parent menu
if (!function_exists('hasChildren')) {
    function hasChildren($menuItems, $name)
    {
        foreach ($menuItems as $item) {
            if ($item['parent'] === $name) {
                return true;
            }
        }
        return false;
    }
}

// get companySetting Sidebar Menu

if (!function_exists('getCompanySettingMenu')) {
    function getCompanySettingMenu()
    {
        $user = Auth::user();
        $roles = $user->roles->first();
        $menu = new Menu($user);
        event(new CompanySettingMenuEvent($menu));
        return generateCompanySettingMenu($menu->menu);
    }
}

// generate CompanySetting Sidebar Menu

if (!function_exists('generateCompanySettingMenu')) {
    function generateCompanySettingMenu($menuItems)
    {
        usort($menuItems, function ($a, $b) {
            return $a['order'] - $b['order'];
        });
        $html = '';
        foreach ($menuItems as $menu) {
            $html .= '<a href="#' . $menu['navigation'] . '" data-module="' . $menu['module'] . '" class="list-group-item list-group-item-action border-0 setting-menu-nav">' . $menu['title'] . '<div class="float-end"><i class="ti ti-chevron-right"></i></div> </a>';
        }
        return $html;
    }
}


// Get the All Modules and base company settings
if (!function_exists('getCompanySetting')) {
    function getCompanySetting()
    {
        $user = Auth::user();
        $role = $user->roles->first();
        $settings = getCompanyAllSettings();
        $html = new CompanySetting($user, $settings);
        event(new CompanySettingEvent($html));
        ;
        return generateCompanySettings($html->html);
    }
}

// generate all module and base company settings
if (!function_exists('generateCompanySettings')) {
    function generateCompanySettings($companySetting)
    {
        usort($companySetting, function ($a, $b) {
            return $a['order'] - $b['order'];
        });
        $html = '';
        foreach ($companySetting as $setting) {
            $html .= $setting['html'];
        }
        return $html;
    }
}

// Get All Company Settings
if (!function_exists('getCompanyAllSettings')) {
    function getCompanyAllSettings($userId = null)
    {
        if (!empty($userId)) {
            $user = User::find($userId);
        } elseif (Auth::check()) {
            $user = auth()->user();
        } else {
            $user = User::find(1);
        }

        // For Other roles Such as Agent
        if (Auth::check() && Auth::user()->parent == 1) {
            $user = User::find(id: Auth::user()->parent == 1);
        }


        if (!empty($user)) {
            $key = 'company_settings_' . $user->id;
            return Cache::rememberForever($key, function () use ($user) {
                $settings = [];
                $settings = Settings::where('created_by', $user->id)->pluck('value', 'name')->toArray();
                return $settings;
            });
        }
        return [];
    }
}

// Forget Company Cache
if (!function_exists('companySettingCacheForget')) {
    function companySettingCacheForget($userId = null)
    {
        try {
            if (!empty($userId)) {
                $user = User::find($userId);
            } else {
                $user = auth()->user();
            }

            $key = 'company_settings_' . $user->id;
            Cache::forget($key);
        } catch (Exception $e) {
            Log::error('companySettingCacheForget', $e->getMessage());
        }
    }
}

// Check File Exists or not
if (!function_exists('checkFile')) {
    function checkFile($path)
    {
        if (!empty($path)) {

            $storage_settings = getCompanyAllSettings();

            if ($storage_settings['storage_setting'] == null || $storage_settings['storage_setting'] == 'local') {

                return file_exists(base_path($path));
            } else {

                if (isset($storage_settings['storage_setting']) && $storage_settings['storage_setting'] == 's3') {
                    config(
                        [
                            'filesystems.disks.s3.key' => $storage_settings['s3_key'],
                            'filesystems.disks.s3.secret' => $storage_settings['s3_secret'],
                            'filesystems.disks.s3.region' => $storage_settings['s3_region'],
                            'filesystems.disks.s3.bucket' => $storage_settings['s3_bucket'],
                            // 'filesystems.disks.s3.url' => $storage_settings['s3_url'],
                            // 'filesystems.disks.s3.endpoint' => $storage_settings['s3_endpoint'],
                        ]
                    );
                } else if (isset($storage_settings['storage_setting']) && $storage_settings['storage_setting'] == 'wasabi') {
                    config(
                        [
                            'filesystems.disks.wasabi.key' => $storage_settings['wasabi_key'],
                            'filesystems.disks.wasabi.secret' => $storage_settings['wasabi_secret'],
                            'filesystems.disks.wasabi.region' => $storage_settings['wasabi_region'],
                            'filesystems.disks.wasabi.bucket' => $storage_settings['wasabi_bucket'],
                            'filesystems.disks.wasabi.root' => $storage_settings['wasabi_root'],
                            'filesystems.disks.wasabi.endpoint' => $storage_settings['wasabi_url']
                        ]
                    );
                }
                try {
                    return Storage::disk($storage_settings['storage_setting'])->exists($path);
                } catch (\Throwable $th) {
                    return 0;
                }
            }
        } else {
            return 0;
        }
    }
}

// Store Image
if (!function_exists('uploadFile')) {
    function uploadFile($request, $key_name, $fileNameToStore, $path, $custom_validation = [])
    {
        try {
            $settings = getCompanyAllSettings();
            if (!empty($settings['storage_setting'])) {

                if ($settings['storage_setting'] == 'wasabi') {

                    config(
                        [
                            'filesystems.disks.wasabi.key' => $settings['wasabi_key'],
                            'filesystems.disks.wasabi.secret' => $settings['wasabi_secret'],
                            'filesystems.disks.wasabi.region' => $settings['wasabi_region'],
                            'filesystems.disks.wasabi.bucket' => $settings['wasabi_bucket'],
                            'filesystems.disks.wasabi.root' => $settings['wasabi_root'],
                            'filesystems.disks.wasabi.endpoint' => $settings['wasabi_url']
                        ]
                    );

                    $max_size = !empty($settings['wasabi_max_upload_size']) ? $settings['wasabi_max_upload_size'] : '2048';
                    $mimes = !empty($settings['wasabi_storage_validation']) ? $settings['wasabi_storage_validation'] : '';
                } else if ($settings['storage_setting'] == 's3') {
                    config(
                        [
                            'filesystems.disks.s3.key' => $settings['s3_key'],
                            'filesystems.disks.s3.secret' => $settings['s3_secret'],
                            'filesystems.disks.s3.region' => $settings['s3_region'],
                            'filesystems.disks.s3.bucket' => $settings['s3_bucket'],
                            'filesystems.disks.s3.use_path_style_endpoint' => false,
                        ]
                    );
                    $max_size = !empty($settings['s3_max_upload_size']) ? $settings['s3_max_upload_size'] : '2048';
                    $mimes = !empty($settings['s3_storage_validation']) ? $settings['s3_storage_validation'] : '';
                } else {
                    $max_size = !empty($settings['local_storage_max_upload_size']) ? $settings['local_storage_max_upload_size'] : '2048';
                    $mimes = !empty($settings['local_storage_validation']) ? $settings['local_storage_validation'] : '';
                }
                $file = $request->$key_name;

                $extension = strtolower($file->getClientOriginalExtension());
                $allowed_extensions = explode(',', $mimes);
                if (empty($extension) || !in_array($extension, $allowed_extensions)) {
                    return [
                        'flag' => 0,
                        'msg' => 'The ' . $key_name . ' must be a file of type: ' . implode(', ', $allowed_extensions) . '.',
                    ];
                }

                if (count($custom_validation) > 0) {
                    $validation = $custom_validation;
                } else {
                    $validation = [
                        'mimes:' . $mimes,
                        'max:' . $max_size,
                    ];
                }

                $validator = Validator::make($request->all(), [
                    $key_name => $validation
                ]);

                if ($validator->fails()) {
                    $res = [
                        'flag' => 0,
                        'msg' => $validator->messages()->first(),
                    ];
                    return $res;
                } else {
                    $name = $fileNameToStore;

                    $saveImage = Storage::disk($settings['storage_setting'])->putFileAs($path, $file, $name);
                    if ($settings['storage_setting'] == 'wasabi') {
                        $path = $saveImage;
                    } else if ($settings['storage_setting'] == 's3') {
                        $path = $saveImage;
                    } else {
                        $path = 'uploads/' . $saveImage;
                    }
                    $res = [
                        'flag' => 1,
                        'msg' => 'success',
                        'url' => $path
                    ];
                    return $res;
                }
            } else {
                $res = [
                    'flag' => 0,
                    'msg' => __('Please set proper configuration for storage.'),
                ];
                return $res;
            }
        } catch (\Exception $e) {
            $res = [
                'flag' => 0,
                'msg' => $e->getMessage(),
            ];
            return $res;
        }
    }
}

// Upload Multiple Files
if (!function_exists('multipleFileUpload')) {
    function multipleFileUpload($request, $key_name, $fileNameToStore, $path, $custom_validation = [])
    {
        try {
            $storage_settings = getCompanyAllSettings();

            if (isset($storage_settings['storage_setting'])) {
                if ($storage_settings['storage_setting'] == 'wasabi') {
                    config(
                        [
                            'filesystems.disks.wasabi.key' => $storage_settings['wasabi_key'],
                            'filesystems.disks.wasabi.secret' => $storage_settings['wasabi_secret'],
                            'filesystems.disks.wasabi.region' => $storage_settings['wasabi_region'],
                            'filesystems.disks.wasabi.bucket' => $storage_settings['wasabi_bucket'],
                            'filesystems.disks.wasabi.root' => $storage_settings['wasabi_root'],
                            'filesystems.disks.wasabi.endpoint' => $storage_settings['wasabi_url']
                        ]
                    );
                    $max_size = !empty($storage_settings['wasabi_max_upload_size']) ? $storage_settings['wasabi_max_upload_size'] : '2048';
                    $mimes = !empty($storage_settings['wasabi_storage_validation']) ? $storage_settings['wasabi_storage_validation'] : 'jpeg,jpg,png,svg,zip,txt,gif,docx';
                } else if ($storage_settings['storage_setting'] == 's3') {
                    config(
                        [
                            'filesystems.disks.s3.key' => $storage_settings['s3_key'],
                            'filesystems.disks.s3.secret' => $storage_settings['s3_secret'],
                            'filesystems.disks.s3.region' => $storage_settings['s3_region'],
                            'filesystems.disks.s3.bucket' => $storage_settings['s3_bucket'],
                            // 'filesystems.disks.s3.url' => $storage_settings['s3_url'],
                            // 'filesystems.disks.s3.endpoint' => $storage_settings['s3_endpoint'],
                        ]
                    );
                    $max_size = !empty($storage_settings['s3_max_upload_size']) ? $storage_settings['s3_max_upload_size'] : '2048';
                    $mimes = !empty($storage_settings['s3_storage_validation']) ? $storage_settings['s3_storage_validation'] : 'jpeg,jpg,png,svg,zip,txt,gif,docx';
                } else {
                    $max_size = !empty($storage_settings['local_storage_max_upload_size']) ? $storage_settings['local_storage_max_upload_size'] : '2048';
                    $mimes = !empty($storage_settings['local_storage_validation']) ? $storage_settings['local_storage_validation'] : 'jpeg,jpg,png,svg,zip,txt,gif,docx';
                }

                $file = $request;

                $extension = strtolower($file->getClientOriginalExtension());
                $allowed_extensions = explode(',', $mimes);
                if (empty($extension) || !in_array($extension, $allowed_extensions)) {
                    return [
                        'flag' => 0,
                        'msg' => 'The ' . $key_name . ' must be a file of type: ' . implode(', ', $allowed_extensions) . '.',
                    ];
                }

                $key_validation = $key_name . '*';

                if (count($custom_validation) > 0) {
                    $validation = $custom_validation;
                } else {
                    $validation = [
                        'mimes:' . $mimes,
                        'max:' . $max_size,
                    ];
                }
                $validator = Validator::make(array($key_name => $request), [
                    $key_validation => $validation
                ]);
                if ($validator->fails()) {
                    $res = [
                        'flag' => 0,
                        'msg' => $validator->messages()->first(),
                    ];
                    return $res;
                } else {

                    $name = $fileNameToStore;

                    $save = Storage::disk($storage_settings['storage_setting'])->putFileAs(
                        $path,
                        $file,
                        $name
                    );

                    if ($storage_settings['storage_setting'] == 'wasabi') {
                        $url = $save;
                    } elseif ($storage_settings['storage_setting'] == 's3') {
                        $url = $save;
                    } else {
                        $url = 'uploads/' . $save;
                    }
                    $res = [
                        'flag' => 1,
                        'msg' => 'success',
                        'url' => $url
                    ];
                    return $res;
                }
            } else {
                $res = [
                    'flag' => 0,
                    'msg' => 'not set configration',
                ];
                return $res;
            }
        } catch (\Exception $e) {
            $res = [
                'flag' => 0,
                'msg' => $e->getMessage(),
            ];
            return $res;
        }
    }
}

// Delete Image
if (!function_exists('deleteFile')) {
    function deleteFile($path)
    {
        if (checkFile($path)) {
            $storage_settings = getCompanyAllSettings();
            if (isset($storage_settings['storage_setting'])) {
                if ($storage_settings['storage_setting'] == 'local') {
                    return File::delete($path);
                } else {
                    if ($storage_settings['storage_setting'] == 's3') {
                        config(
                            [
                                'filesystems.disks.s3.key' => $storage_settings['s3_key'],
                                'filesystems.disks.s3.secret' => $storage_settings['s3_secret'],
                                'filesystems.disks.s3.region' => $storage_settings['s3_region'],
                                'filesystems.disks.s3.bucket' => $storage_settings['s3_bucket'],
                                // 'filesystems.disks.s3.url' => $storage_settings['s3_url'],
                                // 'filesystems.disks.s3.endpoint' => $storage_settings['s3_endpoint'],
                            ]
                        );
                    } else if ($storage_settings['storage_setting'] == 'wasabi') { {
                            config(
                                [
                                    'filesystems.disks.wasabi.key' => $storage_settings['wasabi_key'],
                                    'filesystems.disks.wasabi.secret' => $storage_settings['wasabi_secret'],
                                    'filesystems.disks.wasabi.region' => $storage_settings['wasabi_region'],
                                    'filesystems.disks.wasabi.bucket' => $storage_settings['wasabi_bucket'],
                                    'filesystems.disks.wasabi.root' => $storage_settings['wasabi_root'],
                                    'filesystems.disks.wasabi.endpoint' => $storage_settings['wasabi_url']
                                ]
                            );
                        }
                    }
                    return Storage::disk($storage_settings['storage_setting'])->delete($path);
                }
            }
        }
    }
}


// Fetch the Image
if (!function_exists('getFile')) {
    function getFile($path)
    {
        $storageSettings = getCompanyAllSettings();
        if (isset($storageSettings['storage_setting']) && $storageSettings['storage_setting'] == 's3') {
            config(
                [
                    'filesystems.disks.s3.key' => $storageSettings['s3_key'],
                    'filesystems.disks.s3.secret' => $storageSettings['s3_secret'],
                    'filesystems.disks.s3.region' => $storageSettings['s3_region'],
                    'filesystems.disks.s3.bucket' => $storageSettings['s3_bucket'],
                    'filesystems.disks.s3.url' => $storageSettings['s3_url'],
                    'filesystems.disks.s3.endpoint' => $storageSettings['s3_endpoint'],
                ]
            );
            return Storage::disk('s3')->url($path);
        } else if (isset($storageSettings['storage_setting']) && $storageSettings['storage_setting'] == 'wasabi') {

            config(
                [
                    'filesystems.disks.wasabi.key' => $storageSettings['wasabi_key'],
                    'filesystems.disks.wasabi.secret' => $storageSettings['wasabi_secret'],
                    'filesystems.disks.wasabi.region' => $storageSettings['wasabi_region'],
                    'filesystems.disks.wasabi.bucket' => $storageSettings['wasabi_bucket'],
                    'filesystems.disks.wasabi.root' => $storageSettings['wasabi_root'],
                    'filesystems.disks.wasabi.endpoint' => $storageSettings['wasabi_url']
                ]
            );

            return Storage::disk('wasabi')->url($path);
        } else {
            return asset($path);
        }
    }
}


// Get sidebar Logo
if (!function_exists('getSidebarLogo')) {
    function getSidebarLogo()
    {
        $companySettings = getCompanyAllSettings();
        if ((isset($companySettings['cust_darklayout']) ? $companySettings['cust_darklayout'] : 'off') == 'on') {
            if (!empty($companySettings['light_logo'])) {
                if (checkFile($companySettings['light_logo'])) {
                    return $companySettings['light_logo'];
                } else {
                    return 'uploads/logo/logo-light.png';
                }
            } else {
                return 'uploads/logo/logo-light.png';
            }
        } else {
            if (!empty($companySettings['dark_logo'])) {
                if (checkFile($companySettings['dark_logo'])) {
                    return $companySettings['dark_logo'];
                } else {
                    return 'uploads/logo/logo-dark.png';
                }
            } else {
                return 'uploads/logo/logo-dark.png';
            }
        }
    }
}


// Get Favicon
if (!function_exists('getFavIcon')) {
    function getFavIcon()
    {
        $companySettings = getCompanyAllSettings();
        if (!empty($companySettings['favicon'])) {
            if (checkFile($companySettings['favicon'])) {
                return $companySettings['favicon'];
            } else {
                return 'uploads/logo/favicon.png';
            }
        } else {
            return 'uploads/logo/favicon.png';
        }
    }
}



// Count Cache Size
if (!function_exists('getCacheSize')) {
    function getCacheSize()
    {
        $file_size = 0;
        foreach (File::allFiles(storage_path('/framework')) as $file) {
            $file_size += $file->getSize();
        }
        $file_size = number_format($file_size / 1000000, 4);
        return $file_size;
    }
}

// Get All Languages
if (!function_exists('languages')) {
    function languages()
    {
        $settings = getCompanyAllSettings();
        try {
            $disableLanguages = isset($settings['disable_lang']) && !empty($settings['disable_lang']) ? explode(',', $settings['disable_lang']) : [];
            $languages = Languages::whereNotIn('code', $disableLanguages)->pluck('fullName', 'code')->toArray();
        } catch (Exception $e) {
            $languages = [
                "ar" => "Arabic",
                "zh" => "Chinese",
                "da" => "Danish",
                "de" => "German",
                "en" => "English",
                "es" => "Spanish",
                "fr" => "French",
                "he" => "Hebrew",
                "it" => "Italian",
                "ja" => "Japanese",
                "nl" => "Dutch",
                "pl" => "Polish",
                "pt" => "Portuguese",
                "ru" => "Russian",
                "tr" => "Turkish",
                "pt-br" => "Portuguese(Brazil)",
            ];
        }
        return $languages;
    }
}

// Get Current Active Language
if (!function_exists('getActiveLanguage')) {
    function getActiveLanguage()
    {
        if (Auth::check() && !empty(Auth::user()->lang)) {
            $language = Auth::user()->lang;
        } else {
            $settings = getCompanyAllSettings();
            $language = isset($settings['default_language']) ? $settings['default_language'] : 'en';
        }

        return $language;
    }
}


// set SMTP in Config
if (!function_exists('setSMTPConfig')) {
    function setSMTPConfig()
    {
        $settings = getCompanyAllSettings();
        if ($settings) {
            config([
                'mail.default' => isset($settings['mail_driver']) ? $settings['mail_driver'] : '',
                'mail.mailers.smtp.host' => isset($settings['mail_host']) ? $settings['mail_host'] : '',
                'mail.mailers.smtp.port' => isset($settings['mail_port']) ? $settings['mail_port'] : '',
                'mail.mailers.smtp.encryption' => isset($settings['mail_encryption']) ? $settings['mail_encryption'] : '',
                'mail.mailers.smtp.username' => isset($settings['mail_username']) ? $settings['mail_username'] : '',
                'mail.mailers.smtp.password' => isset($settings['mail_password']) ? $settings['mail_password'] : '',
                'mail.from.address' => isset($settings['mail_from_address']) ? $settings['mail_from_address'] : '',
                'mail.from.name' => isset($settings['mail_from_name']) ? $settings['mail_from_name'] : '',
            ]);
        } else {
            return redirect()->back()->with('Email SMTP settings does not configured so please contact to your site admin.');
        }
    }
}


// fetch the countries flags
if (!function_exists('flagOfCountry')) {
    function flagOfCountry()
    {
        $arr = [
            'ar' => '🇦🇪 ar',
            'da' => '🇩🇰 da',
            'de' => '🇩🇪 de',
            'es' => '🇪🇸 es',
            'fr' => '🇫🇷 fr',
            'it' => '🇮🇹 it',
            'ja' => '🇯🇵 ja',
            'nl' => '🇳🇱 nl',
            'pl' => '🇵🇱 pl',
            'ru' => '🇷🇺 ru',
            'pt' => '🇵🇹 pt',
            'en' => '🇮🇳 en',
            'tr' => '🇹🇷 tr',
            'pt-br' => '🇵🇹 pt-br',
            'zh' => '🇨🇳 zh',
            'he' => '🇮🇱 he',

        ];
        return $arr;
    }
}


// Create the Category Tree Structure
if (!function_exists('buildCategoryTree')) {
    function buildCategoryTree($categories, $parentId = null, $prefix = '')
    {
        $tree = [];
        foreach ($categories as $category) {
            if ($category->parent_id == $parentId) {
                $tree[] = [
                    'id' => $category->id,
                    'name' => $prefix . $category->name,
                ];
                $tree = array_merge($tree, buildCategoryTree($categories, $category->id, $prefix . '&nbsp;&nbsp;&nbsp;&nbsp;'));
            }
        }
        return $tree;
    }
}


// Generate Unique Slug
if (!function_exists('generateUniqueSlug')) {
    function generateUniqueSlug($name, $model, $field = 'slug')
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;
        while ($model->where($field, $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }
        return $slug;
    }
}

// get the Creator
if (!function_exists('creatorId')) {
    function creatorId()
    {
        if (Auth::user()->type == 'admin') {
            return Auth::user()->id;
        } else {
            return Auth::user()->created_by;
        }
    }
}

// Get The  Module Wise All Permisison
if (!function_exists('getPermissionsByModule')) {
    function getPermissionsByModule($module)
    {
        $permissions = Permission::where('module', $module)->where('created_by', creatorId())->get();
        return $permissions;
    }
}


// Get the Parent Category From The Category Id
if (!function_exists('getParentCategory')) {
    function getParentCategory($categoryId)
    {
        $category = Category::where('id', $categoryId)->first();
        return $category->name;
    }
}


// Get Key Wise Company Settings
if (!function_exists('company_setting')) {
    function company_setting($key, $user_id = null)
    {
        if ($key) {
            $company_settings = getCompanyAllSettings($user_id);
            $setting = null;
            if (!empty($company_settings)) {
                $setting = (array_key_exists($key, $company_settings)) ? $company_settings[$key] : null;
            }
            return $setting;
        }
    }
}

// Get Children Category of parent Categpry
if (!function_exists('getChildreCategory')) {

    function getChildreCategory($categories, $parentId = null)
    {
        $tree = [];
        foreach ($categories as $category) {
            if ($category->parent_id == $parentId) {
                $children = getChildreCategory($categories, $category->id);
                $tree[] = [
                    'id' => $category->id,
                    'name' => $category->name,
                    'children' => $children,
                    'parent' => $category->parent_id,
                ];
            }
        }
        return $tree;
    }
}

// Assign Random Agent For The Ticket
if (!function_exists('getRandomAgent')) {
    function getRandomAgent($categoryId)
    {
        $category = Category::find($categoryId);
        $agents = User::where('category_id', $category->id)->get();
        if ($agents->isNotEmpty()) {
            $randomAgent = $agents->random();
            return $randomAgent;
        }
        // Current category agent not found the find previous category agent recursively
        if ($category->parent_id) {
            return getRandomAgent($category->parent_id);
        }

        // parent category agent not found then return default agent
        $defaultAgent = User::where('type', 'agent')->first();
        if ($defaultAgent) {
            return $defaultAgent;
        }
        return null;
    }
}

// get the Categorywise Agent
if (!function_exists('findAgentByCategory')) {
    function findAgentByCategory($categoryId)
    {
        $category = Category::find($categoryId);
        if ($category) {
            $agents = User::where('category_id', $category->id)->get();
            if ($agents->isNotEmpty()) {
                return $agents;
            }
            // call recursively
            if ($category->parent_id) {
                return findAgentByCategory($category->parent_id);
            }

            // parent category agent not found then return default agent
            $defauntAgent = User::where('type', 'agent')->first();
            if ($defauntAgent) {
                return collect([$defauntAgent]);
            }
            return null;
        }
    }
}

// get Activate Modules
if (!function_exists('getActiveModules')) {
    function getActiveModules($userId = null)
    {
        $defaultActivatedModules = User::$adminDefaultActivatedModules;
        $userActiveModules = []; // Default value.

        if ($userId !== null) {
            $user = User::find($userId);
        } else if (Auth::check() && Auth::user()->hasRole('admin')) {
            $user = Auth::user();
        } else if (Auth::check()) {
            $user = User::where('id', Auth::user()->created_by)->first();
        }

        if (!empty($user)) {
            $enabledModules = array_values(AddOnFacade::activeModules());
            $userActiveModules = array_unique(array_merge($enabledModules, $defaultActivatedModules));
        }

        return $userActiveModules;
    }
}

// get the module based on module priority (High Priority Module Comes First)
if (!function_exists('getshowModuleList')) {
    function getshowModuleList()
    {
        $all = AddOnFacade::getOrdered();
        $list = [];
        foreach ($all as $module) {
            if ($module->display) {
                array_push($list, $module->name);
            }
        }
        return $list;
    }
}

// Check Module Is Active Or Not
if (!function_exists('moduleIsActive')) {
    function moduleIsActive($module, $userId = null)
    {
        if (AddOnFacade::has($module)) {
            $isModuleActive = AddOnFacade::isEnabled($module);
            if ($isModuleActive == false) {
                return false;
            }

            if ($userId !== null) {
                $user = User::find($userId);
            } else if (Auth::check() && Auth::user()->hasRole('admin')) {
                $user = Auth::user();
            } else if (Auth::check()) {
                $user = User::where('id', Auth::user()->created_by)->first();
            }

            if (!empty($user)) {
                $active_module = getActiveModules($user->id);
                if ((count($active_module) > 0 && in_array($module, $active_module))) {
                    return true;
                }
                return false;
            }
            return true;
        } else {
            return false;
        }
    }
}


// clear the cache once enable the module
if (!function_exists('sideMenuCacheForget')) {
    function sideMenuCacheForget($type = null, $user_id = null)
    {
        if ($type == 'all') {
            Cache::flush();
        }

        if (!empty($user_id)) {
            $user = User::find($user_id);
        } else {
            $user = auth()->user();
        }
        if ($user->hasRole('admin')) {
            $users = User::select('id')->where('created_by', $user->id)->pluck('id');
            foreach ($users as $id) {
                try {
                    $key = 'sidebar_menu_' . $id;
                    Cache::forget($key);
                } catch (\Exception $e) {
                    Log::error('comapnySettingCacheForget :' . $e->getMessage());
                }
            }
            try {
                $key = 'sidebar_menu_' . $user->id;
                Cache::forget($key);
            } catch (\Exception $e) {
                Log::error('comapnySettingCacheForget :' . $e->getMessage());
            }
            return true;
        }

        try {
            $key = 'sidebar_menu_' . $user->id;
            Cache::forget($key);
        } catch (\Exception $e) {
            Log::error('comapnySettingCacheForget :' . $e->getMessage());
        }

        return true;
    }
}

// Generate Response in JSON
if (!function_exists('error_res')) {
    function error_res($msg = "", $args = array())
    {
        $msg = $msg == "" ? "error" : $msg;
        $msg_id = 'error.' . $msg;
        $converted = \Lang::get($msg_id, $args);
        $msg = $msg_id == $converted ? $msg : $converted;
        $json = array(
            'flag' => 0,
            'msg' => $msg,
        );

        return $json;
    }
}

// Generate Response in JSON
if (!function_exists('success_res')) {
    function success_res($msg = "", $args = array())
    {
        $msg = $msg == "" ? "success" : $msg;
        $json = array(
            'flag' => 1,
            'msg' => $msg,
        );

        return $json;
    }
}

// // module alias name
if (!function_exists('moduleAliasName')) {
    function moduleAliasName($module_name)
    {
        static $addons = [];
        static $resultArray = [];
        if (count($addons) == 0 && count($resultArray) == 0) {
            $addons = AddOnFacade::all();
            $resultArray = array_reduce($addons, function ($carry, $item) {
                if (isset($item->name) && isset($item->alias)) {
                    $carry[$item->name] = $item->alias;
                }
                return $carry;
            }, []);
        }

        if ($module_name === 'general' || $module_name === 'General') {
            return $module_name;
        }
        $module = AddOnFacade::find($module_name);
        if (isset($resultArray)) {
            $module_name = array_key_exists($module_name, $resultArray) ? $resultArray[$module_name] : (!empty($module) ? $module->alias : $module_name);
        } elseif (!empty($module)) {
            $module_name = $module->alias;
        }
        return $module_name;
    }
}

// create a new languages for templates.
if (!function_exists('makeEmailLang')) {
    function makeEmailLang($lang)
    {
        $templates = NotificationTemplates::all();
        foreach ($templates as $template) {
            $default_lang = NotificationTemplateLangs::where('parent_id', '=', $template->id)->where('lang', 'LIKE', 'en')->first();
            $emailTemplateLang = new NotificationTemplateLangs();
            $emailTemplateLang->parent_id = $template->id;
            $emailTemplateLang->lang = $lang;
            $emailTemplateLang->subject = $default_lang->subject;
            $emailTemplateLang->content = $default_lang->content;
            $emailTemplateLang->variables = $default_lang->variables;
            $emailTemplateLang->save();
        }
    }
}

// This Function is for Sending Common Email Template Base On The Email Notification Setting.
if (!function_exists('sendTicketEmail')) {
    function sendTicketEmail($templateName, $companySettings, $ticket, $request, &$error_msg)
    {
        if (!isset($companySettings[$templateName]) || $companySettings[$templateName] != 1) {
            return;
        }
        $ticketNumber = moduleIsActive('TicketNumber') ? Workdo\TicketNumber\Entities\TicketNumber::ticketNumberFormat($ticket->id) : $ticket->ticket_id;
        $uArr = [
            'ticket_name' => $ticket->name,
            'ticket_id' => $ticketNumber,
            'ticket_url' => route('home.view', ['id' => encrypt($ticketNumber)]),
            'originalTicketId' => $ticket->id,
        ];
        switch ($templateName) {
            case 'Send Mail To Agent':
                $agent = User::where('id', $ticket->is_assign)->first();
                if (!$agent)
                    return;
                $uArr['email'] = $request->email;
                $recipientEmail = $agent->email;
                break;
            case 'Send Mail To Customer':
                $uArr['email'] = $request->email;
                $recipientEmail = $request->email;
                break;
            case 'Send Mail To Admin':
                $agent = User::where('id', $ticket->is_assign)->first();
                $admin = User::where('type', 'admin')->where('created_by', 0)->first();
                if (!$admin)
                    return;
                $uArr['customer_email'] = $request->email;
                $uArr['agent_email'] = isset($agent->email) ? $agent->email : '---';
                $recipientEmail = $admin->email;
                break;
            case 'Reply Mail To Customer':
                unset($uArr['ticket_url']);
                $uArr['ticket_description'] = $request->reply_description;
                $recipientEmail = $ticket->email;
                break;
            case 'Reply Mail To Agent':
                $agent = User::where('id', $ticket->is_assign)->first();
                if (!$agent)
                    return;
                unset($uArr['ticket_url']);
                $uArr['ticket_description'] = $request->reply_description;
                $recipientEmail = $agent->email;
                break;
            case 'Reply Mail To Admin':
                $agent = User::where('id', $ticket->is_assign)->first();
                $admin = User::where('type', 'admin')->where('created_by', 0)->first();
                if (!$admin)
                    return;
                $uArr['customer_email'] = $ticket->email;
                $uArr['agent_email'] = isset($agent->email) ? $agent->email : '---';
                $uArr['ticket_description'] = $request->reply_description;
                $recipientEmail = $admin->email;
                break;
            case 'Ticket Close':
                $uArr['customer_email'] = $request->email;
                $recipientEmail = $request->email;
                break;
            default:
                return;
        }

        $response = Utility::sendEmailTemplate($templateName, [$recipientEmail], $uArr);
        if ((isset($response) && isset($response['is_success'])) && $response['is_success'] == false) {
            $error_msg = $response['error'];
        }
    }
}
