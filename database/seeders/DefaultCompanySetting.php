<?php

namespace Database\Seeders;

use App\Models\Settings;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DefaultCompanySetting extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    //  need to change here after adding role/permission
    $company = User::where('parent', 0)->first();
    $defaultCompanySetting = [
      "knowledge_base" => "on",
      "faq" => "on",
      "site_rtl" => "off",
      "color" => "theme-1",
      'default_language' => 'en',
      'CHAT_MODULE' => 'no',
      'RECAPTCHA_MODULE' => "no",
      'timezone' => 'Asia/Kolkata',
      'cust_theme_bg' => 'on',

      // For Storage
      "storage_setting" => "local",
      "local_storage_validation" => "jpg,jpeg,png,xlsx,xls,csv,pdf",
      "local_storage_max_upload_size" => "2048000",

      // For Cookie
      'enable_cookie' => 'on',
      'necessary_cookies' => 'on',
      'cookie_logging' => 'on',
      'cookie_title' => 'We use cookies!',
      'cookie_description' => 'Hi, this website uses essential cookies to ensure its proper operation and tracking cookies to understand how you interact with it',
      'strictly_cookie_title' => 'Strictly necessary cookies',
      'strictly_cookie_description' => 'These cookies are essential for the proper functioning of my website. Without these cookies, the website would not work properly',
      'more_information_description' => 'For any queries in relation to our policy on cookies and your choices, please contact us',
      'footer_text' => '© 2025 TicketGo',
      'contactus_url' => '#',
    ];

    foreach ($defaultCompanySetting as $key => $value) {
      $setting = Settings::where('name', $key)->where('created_by', $company->id)->first();
      if (empty($setting)) {
        $data = [
          'name' => $key,
          'value' => $value,
          'created_by' => $company->id
        ];
        Settings::create($data);
      }
    }
  }
}
