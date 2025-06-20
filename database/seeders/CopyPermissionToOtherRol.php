<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\CustomField;
use App\Models\Category;
use App\Models\User;

class CopyPermissionToOtherRol extends Seeder
{
    public function run(): void
    {
        // Copiar permisos del rol 1 al rol 8
        $roleSource = Role::find(1); // Rol origen (admin)
        $roleTarget = Role::find(8); // Rol destino (nuevo admin)

        // Obtener el admin de la empresa 2
        $adminEmpresa2 = User::where('company_id', 2)->where('type', 'admin')->first();

        if ($roleSource && $roleTarget) {
            // Borra todos los permisos actuales del rol 8
            $roleTarget->permissions()->detach();

            // Copia todos los permisos del rol 1 al rol 8
            $permissions = $roleSource->permissions()->pluck('id');
            foreach ($permissions as $permissionId) {
                // Solo agrega si no existe
                if (!$roleTarget->permissions()->where('id', $permissionId)->exists()) {
                    $roleTarget->permissions()->attach($permissionId);
                }
            }
        }

        // Copiar todos los custom fields de la empresa 1 a la empresa 2, evitando duplicados por nombre
        if ($adminEmpresa2) {
            $customFields = CustomField::where('company_id', 1)->get();
            foreach ($customFields as $field) {
                $exists = CustomField::where('company_id', 2)
                    ->where('name', $field->name)
                    ->exists();
                if (!$exists) {
                    $newField = $field->replicate();
                    $newField->company_id = 2;
                    $newField->created_by = $adminEmpresa2->id;
                    $newField->save();
                }
            }

            // Copiar todas las categorías de la empresa 1 a la empresa 2, evitando duplicados por nombre
            $categories = Category::where('company_id', 1)->get();
            foreach ($categories as $category) {
                $exists = Category::where('company_id', 2)
                    ->where('name', $category->name)
                    ->exists();
                if (!$exists) {
                    $newCategory = $category->replicate();
                    $newCategory->company_id = 2;
                    $newCategory->created_by = $adminEmpresa2->id;
                    $newCategory->parent_id = 0;
                    $newCategory->save();
                }
            }





        }
    }
}