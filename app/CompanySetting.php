<?php

namespace App;

class CompanySetting
{
    public $user;
    public $settings;
    public $modules;

    public $html = [];
    public function __construct($user,$settings)
    {
        $this->user = $user;
        $this->settings = $settings;
        $this->modules = getActiveModules();
        $this->modules[] =  'Base';
    }

    public function getSettings(){
        $settings = $this->settings;
        return $settings;
    }

    public function add(array $array){
      if((in_array($array['module'],$this->modules)) && (!empty($array['permission'])  &&  $this->user->isAbleTo($array['permission']))){
         $this->html[] = $array;
      }
    }
}
