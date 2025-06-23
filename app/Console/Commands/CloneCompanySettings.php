<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Role;
use App\Models\CustomField;
use App\Models\Category;
use App\Models\Company;
use App\Models\User;
use App\Models\Settings;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

// php artisan company:clone 1 2 --dry-run
// php artisan company:clone 1 2 

class CloneCompanySettings extends Command
{
    protected $signature = 'company:clone 
                            {source_company : ID de la empresa origen}
                            {target_company : ID de la empresa destino}
                            {--dry-run : Mostrar qué se haría sin ejecutarlo}';

    protected $description = 'Clona configuraciones, roles y datos de una empresa a otra';

    public function handle()
    {
        $sourceId = $this->argument('source_company');
        $targetId = $this->argument('target_company');
        $dryRun = $this->option('dry-run');

        // 1. Verificar empresas existentes
        if (!Company::where('id', $sourceId)->exists()) {
            return $this->error("Empresa origen $sourceId no existe");
        }

        if (!Company::where('id', $targetId)->exists()) {
            return $this->error("Empresa destino $targetId no existe");
        }

        if ($dryRun) {
            $this->info("*** MODO SIMULACIÓN (no se harán cambios) ***");
        }

        // 2. Copiar rol de administrador (con created_by = 0)
        $adminRole = $this->copyAdminRole($sourceId, $targetId, $dryRun);

        // 3. Crear usuario administrador (con created_by = 0)
        $adminUser = $this->createAdminUser($targetId, $adminRole, $dryRun);

        if (!$dryRun && $adminUser) {
            // 4. Inicializar configuraciones con created_by del admin
            $this->initializeSettings($targetId, $adminUser->id, $dryRun);

            // 5. Copiar custom fields (con created_by del admin)
            $this->copyCustomFields($sourceId, $targetId, $adminUser, $dryRun);

            // 6. Copiar categorías (con created_by del admin)
            $this->copyCategories($sourceId, $targetId, $adminUser, $dryRun);
        }

        $this->info("¡Proceso completado!");
    }

    protected function initializeSettings($companyId, $createdById, $dryRun)
    {
        $currentTime = Carbon::now();

        $defaultSettings = [
            "knowledge_base" => "on",
            "faq" => "on",
            "site_rtl" => "off",
            "color" => "theme-1",
            'default_language' => 'es',
            'CHAT_MODULE' => 'no',
            'RECAPTCHA_MODULE' => "no",
            'timezone' => 'America/Guayaquil',
            'cust_theme_bg' => 'on',
            "storage_setting" => "local",
            "local_storage_validation" => "jpg,jpeg,png,xlsx,xls,csv,pdf",
            "local_storage_max_upload_size" => "2048000",
            'enable_cookie' => 'on',
            'necessary_cookies' => 'on',
            'cookie_logging' => 'on',
            'cookie_title' => 'We use cookies!',
            'cookie_description' => 'Hi, this website uses essential cookies...',
            'strictly_cookie_title' => 'Strictly necessary cookies',
            'strictly_cookie_description' => 'These cookies are essential...',
            'more_information_description' => 'For any queries...',
            'footer_text' => '© 2025 Hubi Tickets',
            'contactus_url' => '#',
        ];

        $this->info("Inicializando configuraciones para empresa $companyId");

        foreach ($defaultSettings as $key => $value) {
            $exists = Settings::where('name', $key)
                ->where('company_id', $companyId)
                ->exists();

            if (!$exists) {
                $this->line(" - Creando setting: $key = $value");
                
                if (!$dryRun) {
                    Settings::create([
                        'name' => $key,
                        'value' => $value,
                        'company_id' => $companyId,
                        'created_by' => $createdById, // Usamos el ID del admin creado
                        'created_at' => $currentTime,
                        'updated_at' => $currentTime
                    ]);
                }
            } else {
                $this->line(" - Setting $key ya existe (omitido)");
            }
        }
    }

    protected function copyAdminRole($sourceId, $targetId, $dryRun)
    {
        $currentTime = Carbon::now();

        $this->info("\nCopiando rol de administrador...");

        $sourceRole = Role::where('company_id', $sourceId)
            ->where('name', 'admin')
            ->first();

        if (!$sourceRole) {
            $this->error("No se encontró rol admin en empresa origen");
            return null;
        }

        // Verificar si ya existe rol admin en destino
        $targetRole = Role::where('company_id', $targetId)
            ->where('name', 'admin')
            ->first();

        if ($targetRole) {
            $this->line(" - Rol admin ya existe en empresa destino (ID: {$targetRole->id})");
            return $targetRole;
        }

        $this->line(" - Creando nuevo rol: Administrador [Empresa $targetId]");

        if (!$dryRun) {
            $newRole = $sourceRole->replicate();
            $newRole->company_id = $targetId;
            $newRole->name = "admin";
            $newRole->display_name = "Administrador [Empresa $targetId]";
            $newRole->created_by = 0; // Creado por el sistema
            $newRole->created_at = $currentTime;
            $newRole->updated_at = $currentTime;
            $newRole->save();

            // Copiar permisos
            $permissions = $sourceRole->permissions()->pluck('id');
            $newRole->permissions()->sync($permissions);

            return $newRole;
        }

        return null;
    }

    protected function createAdminUser($companyId, $adminRole, $dryRun)
    {
        $currentTime = Carbon::now();

        $this->info("\nCreando usuario administrador...");

        $exists = User::where('company_id', $companyId)
            ->where('type', 'admin')
            ->exists();

        if ($exists) {
            $this->line(" - Usuario admin ya existe en empresa destino");
            return User::where('company_id', $companyId)
                ->where('type', 'admin')
                ->first();
        }

        $email = "admin{$companyId}@hubitickets.com";
        $this->line(" - Creando usuario: $email / 1234");

        if (!$dryRun && $adminRole) {
            $adminUser = User::create([
                'name' => 'Administrador',
                'email' => $email,
                'password' => Hash::make('1234'),
                'type' => 'admin',
                'company_id' => $companyId,
                'is_enable_login' => 1,
                'lang' => 'es',
                'created_by' => 0, // Usuario raíz (creado por el sistema)
                'avatar' => 'uploads/users-avatar/avatar.png',
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ]);

            $adminUser->addRole($adminRole);

            return $adminUser;
        }

        return null;
    }

    protected function copyCustomFields($sourceId, $targetId, $adminUser, $dryRun)
    {
        $currentTime = Carbon::now();

        $this->info("\nCopiando custom fields...");

        $fields = CustomField::where('company_id', $sourceId)->get();
        $copied = 0;

        foreach ($fields as $field) {
            $exists = CustomField::where('company_id', $targetId)
                ->where('name', $field->name)
                ->exists();

            if (!$exists) {
                $this->line(" - Copiando field: {$field->name}");
                $copied++;
                
                if (!$dryRun && $adminUser) {
                    $newField = $field->replicate();
                    $newField->company_id = $targetId;
                    $newField->created_by = $adminUser->id; // Asignado al admin de la empresa destino
                    $newField->created_at = $currentTime;
                    $newField->updated_at = $currentTime;
                    $newField->save();
                }
            }
        }

        $this->line("Total fields copiados: $copied");
    }

    protected function copyCategories($sourceId, $targetId, $adminUser, $dryRun)
    {
        $currentTime = Carbon::now();

        $this->info("\nCopiando categorías...");

        $categories = Category::where('company_id', $sourceId)->get();
        $copied = 0;

        foreach ($categories as $category) {
            $exists = Category::where('company_id', $targetId)
                ->where('name', $category->name)
                ->exists();

            if (!$exists) {
                $this->line(" - Copiando categoría: {$category->name}");
                $copied++;
                
                if (!$dryRun && $adminUser) {
                    $newCategory = $category->replicate();
                    $newCategory->company_id = $targetId;
                    $newCategory->created_by = $adminUser->id; // Asignado al admin de la empresa destino
                    $newCategory->parent_id = 0; // Reset parent for simplicity
                    $newCategory->created_at = $currentTime;
                    $newCategory->updated_at = $currentTime;
                    $newCategory->save();
                }
            }
        }

        $this->line("Total categorías copiadas: $copied");
    }
}