<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Models\Company;

class CopyUploadsToCompany extends Command
{
    protected $signature = 'uploads:copy-to-company 
                            {company : Slug o ID de la empresa}
                            {--folders= : Carpetas a copiar (separadas por coma)}
                            {--dry-run : Mostrar qué se haría sin ejecutarlo realmente}
                            {--create-base : Crear carpetas base si no existen}';

    protected $description = 'Copia archivos uploads al directorio de una empresa específica (sin eliminar originales)';

    public function handle()
    {
        $companySlug = $this->argument('company');
        $dryRun = $this->option('dry-run');
        $createBase = $this->option('create-base');
        
        // Obtener la empresa
        $company = Company::where('slug', $companySlug)
                    ->orWhere('id', $companySlug)
                    ->first();

        if (!$company) {
            $this->error("Empresa no encontrada con slug/ID: $companySlug");
            return;
        }

        // Directorios a copiar
        $defaultFolders = [
            'users-avatar',
            'logo',
            'tickets',
            'chats',
            'metaevent',
            'dashboard'
        ];

        $folders = $this->option('folders') 
                    ? explode(',', $this->option('folders'))
                    : $defaultFolders;

        $this->info("Iniciando copia para empresa: {$company->name} ({$company->slug})");
        $this->line("Carpetas a copiar: " . implode(', ', $folders));

        if ($dryRun) {
            $this->info("*** MODO SIMULACIÓN (dry run) - No se copiarán archivos ***");
        }

        $basePath = base_path('uploads');
        $companyPath = "{$basePath}/{$company->slug}";

        // Crear directorio de empresa si no existe
        if (!File::exists($companyPath)) {
            if (!$dryRun) {
                File::makeDirectory($companyPath, 0755, true);
                $this->info("Creado directorio de empresa: {$companyPath}");
            } else {
                $this->info("Se crearía directorio de empresa: {$companyPath}");
            }
        }

        $copiedCount = 0;
        $totalFiles = 0;
        $createdBaseFolders = 0;

        foreach ($folders as $folder) {
            $source = "{$basePath}/{$folder}";
            $destination = "{$companyPath}/{$folder}";

            // Crear carpeta base si no existe y la opción está activada
            if ($createBase && !File::exists($source)) {
                if (!$dryRun) {
                    File::makeDirectory($source, 0755, true);
                    $this->line("Creada carpeta base: {$source}");
                    $createdBaseFolders++;
                } else {
                    $this->line("Se crearía carpeta base: {$source}");
                }
            }

            if (!File::exists($source)) {
                $this->warn("La carpeta {$folder} no existe en uploads/ - omitiendo");
                continue;
            }

            if (File::exists($destination)) {
                $this->warn("La carpeta {$folder} ya existe en destino - omitiendo");
                continue;
            }

            $this->line("Procesando {$folder}...");

            if (!$dryRun) {
                // Copiar recursivamente el directorio
                File::copyDirectory($source, $destination);
                
                // Contar archivos copiados
                $fileCount = count(File::allFiles($destination));
                $totalFiles += $fileCount;
                $this->line("Copiados {$fileCount} archivos en {$folder}");
            } else {
                // En dry-run, estimar cantidad de archivos
                $estimatedFiles = File::exists($source) ? count(File::allFiles($source)) : 0;
                $this->line("Se copiarían ~{$estimatedFiles} archivos en {$folder}");
            }

            $copiedCount++;
        }

        $this->info("¡Operación completada!");
        $this->info("Total carpetas base creadas: {$createdBaseFolders}");
        $this->info("Total carpetas copiadas: {$copiedCount}");
        $this->info("Total archivos copiados: {$totalFiles}");

        if ($dryRun) {
            $this->info("Recordatorio: Esto fue una simulación. Usa el comando sin --dry-run para ejecutar realmente.");
        }
    }
}