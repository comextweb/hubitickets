<?php

namespace App;

use Illuminate\Support\Facades\Cache;
use App\Models\AddOn;
use App\Models\Ticket;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class AddOnDetails
{
    protected $addon;
    public $name;
    public $alias;
    public $image;
    public $description;
    public $priority;
    public $child_module;
    public $parent_module;
    public $version;
    public $package_name;
    public $display;
    protected $allEnabled = [];
    public static function activeModules()
    {
        return AddOn::where('is_enable', 1)->pluck('name')->toArray() ?? [];
    }


    public function json($name)
    {
        $path = base_path('packages/workdo/' . $name . '/module.json');
        if (!File::exists($path)) {
            return false;
        }
        $contents = File::get($path);
        return json_decode($contents, true);
    }

    public function getDevPackagePath()
    {
        if (is_null($this->addon)) {
            $path = base_path('packages/workdo');
            return File::directories($path);
        }
        return base_path('packages/workdo/' . $this->name);
    }

    public function find($name)
    {
        return Cache::rememberForever(
            $name,
            function () use ($name) {
                if ($name === 'general') {
                    $this->name =  $name;
                    $this->alias =  $name;
                } else {
                    $this->addon = AddOn::where('name', $name)->orWhere('package_name', $name)->first();

                    $addonJson = $this->json($name);

                    if ($addonJson) {
                        $this->name = $addonJson['name'] ?? $name;
                        $this->alias = $addonJson['alias'] ?? $name;
                        $this->image = url('/packages/workdo/' . $addonJson['name'] . '/favicon.png');
                        $this->description = $addonJson['description'] ?? "";
                        $this->priority = $addonJson['priority'] ?? 10;
                        $this->child_module = $addonJson['child_module'] ?? [];
                        $this->parent_module = $addonJson['parent_module'] ?? [];
                        $this->version = $addonJson['version'] ?? 1.0;
                        $this->package_name = $addonJson['package_name'] ?? null;
                        $this->display = $addonJson['display'] ?? true;
                    }

                    // Need If we gives AddOn Name Update Functionality
                    // if ($this->addon) {
                    //     $this->name = $this->addon->name ?? $name;
                    //     $this->alias = $this->addon->name ?? $name;
                    //     $this->image = !empty($this->addon->image) ? getFile($this->addon->image) : url('/packages/workdo/' . $this->addon->name . '/favicon.png');
                    //     $this->package_name = $this->addon->package_name ?? null;
                    // }

                }

                return $this;
            }
        );
    }


    public function getOrdered()
    {
        $modules = $this->all();

        usort($modules, function ($a, $b) {
            return $a->priority - $b->priority;
        });

        return $modules;
    }

    public function all()
    {
        $modules = $this->activeModules();
        return $this->moduleArr($modules);
    }

    public function moduleArr($modules)
    {
        $allModulesArr = [];
        foreach ($modules as $module) {
            $moduleInstance = new self();
            $allModulesArr[] = $moduleInstance->find($module);
        }
        return $allModulesArr;
    }


    public function has($name)
    {
        return in_array($name, array_column($this->allModules(), 'name'));
    }

    public function allModules()
    {
        $directories = array_map(function ($dir) {
            return basename($dir);
        }, $this->getDirectories());

        return $this->moduleArr($directories);
    }

    public function getDirectories()
    {
        $path = base_path('packages/workdo');
        return File::directories($path);
    }

    public function isEnabled($module = null)
    {
        static $cache = [];

        if ($module) {
            if (!isset($cache[$module])) {

                $cache[$module] = Addon::where('name', $module)->where('is_enable', 1)->exists();
            }

            return $cache[$module];
        }
        return $this->addon && $this->addon->is_enable;
    }

    public function moduleCacheForget($module = null)
    {
        try {
            if (is_null($module)) {
                Cache::forget($this->addon->module);
                Cache::forget($this->addon->package_name);
            } else {
                Cache::forget($module);
            }
        } catch (\Exception $e) {
            Log::error($module . $e->getMessage());
        }
    }

    public function getPath()
    {
        if (is_null($this->addon)) {
            return $this->getDirectories();
        }
        return base_path('packages/workdo/' . $this->name);
    }

    public function enableAddon()
    {
        if ($this->addon) {
            $this->addon->is_enable = 1;
            $this->addon->save();
            $this->moduleCacheForget();
        }
    }

    public function disableAddon()
    {
        if ($this->addon) {
            $this->addon->is_enable = 0;
            $this->addon->save();
            $this->moduleCacheForget();

            // if disabled Addon is Whatsapp chatbot or instagram chat
            if ($this->addon->name  == 'WhatsAppChatBotAndChat' || $this->addon->name  == 'InstagramChat' || $this->addon->name  == 'FacebookChat') {
                $this->closeTickets($this->addon->name);
            }
        }
    }


    public function closeTickets($addonName)
    {
        if ($addonName == 'WhatsAppChatBotAndChat') {
            $tickets = Ticket::where('type', 'Whatsapp')->get();
            foreach ($tickets as $ticket) {
                $ticket->status = "Closed";
                $ticket->save();
            }
        } elseif ($addonName == 'InstagramChat') {
            $tickets = Ticket::where('type', 'Instagram')->get();
            foreach ($tickets as $ticket) {
                $ticket->status = "Closed";
                $ticket->save();
            }
        }elseif ($addonName == 'FacebookChat') {
            $tickets = Ticket::where('type', 'Facebook')->get();
            foreach ($tickets as $ticket) {
                $ticket->status = "Closed";
                $ticket->save();
            }
        }
    }
}
