<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Facades\AddonFacade as AddonFacade;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\AddOn;
use Illuminate\Support\Facades\Artisan;
use ZipArchive;

class AddOnController extends Controller
{
    public function index()
    {
        if (Auth::user()->hasRole('admin')) {

            try {
                $modules = AddonFacade::allModules();
                $exploreAddons = json_decode(file_get_contents('https://demo.workdo.io/ticketgo/cronjob/ticketgo_addon.json', true));
            } catch (Exception $e) {
                return redirect()->back()->with('error', 'Opps Something Went Wrong !!');
            }

            return view('admin.addon-manager.index', compact('modules', 'exploreAddons'));
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }
    public function addonEnable(Request $request)
    {
        $addOn = AddonFacade::find($request->name);
        if (!empty($addOn)) {
            sideMenuCacheForget('all');
            App::setLocale('en');
            if ($addOn->isEnabled()) {
                $checkChildModule = $this->checkChildAddOn($addOn);
                if ($checkChildModule == true) {
                    $module = AddonFacade::find($request->name);
                    $module->disableAddon();
                    AddonFacade::moduleCacheForget($request->name);
                    return redirect()->back()->with('success', __('Module Disable Successfully!'));
                } else {
                    return redirect()->back()->with('error', __($checkChildModule['msg']));
                }
            } else {
                $getAddOn = AddOn::where('name', $request->name)->first();

                if (empty($getAddOn)) {
                    Artisan::call('migrate --path=/packages/workdo/' . $request->name . '/src/Database/Migrations');
                    Artisan::call('package:seed ' . $request->name);
                    $filePath = base_path('packages/workdo/' . $request->name . '/module.json');
                    $jsonContent = file_get_contents($filePath);
                    $data = json_decode($jsonContent, true);

                    $addon = new AddOn;
                    $addon->name = $data['name'];
                    $addon->package_name = $data['package_name'];
                    $addon->alias_name = $data['alias'];
                    $addon->save();
                    AddonFacade::moduleCacheForget($request->name);
                }
                $addOn = AddonFacade::find($request->name);
                $checkParentModule = $this->checkParentAddOn($addOn);
                if ($checkParentModule['status'] == true) {
                    Artisan::call('migrate --path=/packages/workdo/' . $request->name . '/src/Database/Migrations');
                    Artisan::call('package:seed ' . $request->name);
                    $addOn = AddonFacade::find($request->name);
                    $addOn->enableAddon();
                    AddonFacade::moduleCacheForget($request->name);
                    return redirect()->back()->with('success', __('Module Enable Successfully!'));
                } else {
                    return redirect()->back()->with('error', __($checkParentModule['msg']));
                }
            }
        } else {
            return redirect()->back()->with('error', 'AddOn Not Found.');
        }
    }

    public function checkParentAddOn($addon)
    {
        $path = $addon->getPath() . '/module.json';
        $json = json_decode(file_get_contents($path), true);
        $data['status'] = true;
        $data['msg'] = '';
        if (isset($json['parent_module']) && !empty($json['parent_module'])) {
            foreach ($json['parent_module'] as $key => $value) {
                $modules = implode(',', $json['parent_module']);
                $parent_module = moduleIsActive($value);
                if ($parent_module == true) {
                    $module = AddonFacade::find($value);
                    if ($module) {
                        $this->checkParentAddOn($module);
                    }
                } else {
                    $data['status'] = false;
                    $data['msg'] = 'Please Activate This Module ' . $modules;
                    return $data;
                }
            }
            return $data;
        } else {
            return $data;
        }
    }

    public function checkChildAddOn($addon)
    {
        $path = $addon->getPath() . '/module.json';
        $json = json_decode(file_get_contents($path), true);
        $status = true;
        if (isset($json['child_module']) && !empty($json['child_module'])) {
            foreach ($json['child_module'] as $key => $value) {
                $child_module = moduleIsActive($value);
                if ($child_module == true) {
                    $module = AddonFacade::find($value);
                    $module->disableAddon();
                    if ($module) {
                        $this->checkChildAddOn($module);
                    }
                }
            }
            return true;
        } else {
            return true;
        }
    }

    public function addAddOn()
    {
        if (Auth::user()->hasRole('admin')) {
            return view('admin.addon-manager.addon-add');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function installAddon(Request $request)
    {
        $zip = new ZipArchive;
        $fileName = $request->file('file')->getClientOriginalName();
        $fileName = str_replace('.zip', '', $fileName); // Remove .zip from the file name

        try {
            $res = $zip->open($request->file);
            if ($res !== TRUE) {
                return error_res('Unable to open the ZIP file.');
            }
        } catch (Exception $e) {
            return error_res($e->getMessage());
        }

        // Prepare the extraction path
        $extractPath = 'packages/workdo/' . $fileName;
        $this->createDirectory($extractPath);

        // After extracting to the temporary directory
        $tempPath = 'packages/workdo/tmp_' . uniqid();
        $zip->extractTo($tempPath);
        $zip->close();

        // Determine the root folder name in the zip (if needed)
        $rootFolder = array_diff(scandir($tempPath), ['.', '..']);

        if (empty($rootFolder) || !file_exists($tempPath . '/' . $fileName . '/module.json')) {
            // Remove the temporary directory
            $this->deleteDirectory($tempPath);
            return error_res(__('You have uploaded an invalid file. Please upload a valid file.'));
        }

        $rootFolderName = array_values($rootFolder)[0]; // Get the first folder name in the zip

        // Move files to the target directory
        $this->moveExtractedFiles($tempPath, $extractPath, $rootFolderName);

        // Remove the temporary directory
        $this->deleteDirectory($tempPath);
        $this->setPermissions($extractPath);
        // Process the `module.json` file
        $filePath = base_path('packages/workdo/' . $fileName . '/module.json');
        $data = $this->parseJsonFile($filePath);

        $addon = AddOn::where('name', $fileName)->first();
        if (empty($addon)) {
            $addon = new AddOn;
            $addon->name = $data['name'];
            $addon->alias_name = $data['alias'];
            $addon->is_enable = 0;
            $addon->package_name = $data['package_name'];
            $addon->save();
        }

        // Forget the cache for the module
        AddonFacade::moduleCacheForget($addon->name);

        return success_res($data['name'] . ' ' . __('Installed successfully.'));
    }


    // create new directory once upload the addon
    private function createDirectory($path)
    {
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
            $this->setPermissions($path);
        } else {
            $this->setPermissions($path);
        }
    }

    // Set directory permissions
    private function setPermissions($path)
    {
        if (function_exists('chmod')) {
            @chmod($path, 0777); // Set permissions if possible
        }
    }

    private function moveExtractedFiles($source, $destination, $filename = null)
    {
        // Adjust the source directory if a root folder (e.g., $filename) exists in the zip
        if ($filename) {
            $source = $source . DIRECTORY_SEPARATOR . $filename;
        }

        $files = array_diff(scandir($source), ['.', '..']);
        foreach ($files as $file) {
            $srcPath = $source . DIRECTORY_SEPARATOR . $file;
            $destPath = $destination . DIRECTORY_SEPARATOR . $file;

            if (is_dir($srcPath)) {
                // Recursively move subdirectories
                if (!is_dir($destPath)) {
                    mkdir($destPath, 0777, true);
                }
                // Check if chmod exists
                if (function_exists('chmod')) {
                    @chmod($destPath, 0777); // Set permissions if possible
                }
                $this->moveExtractedFiles($srcPath, $destPath);
            } else {
                // Move file
                rename($srcPath, $destPath);
                // Check if chmod exists
                if (function_exists('chmod')) {
                    @chmod($destPath, 0777); // Set permissions if possible
                }
            }
        }
    }

    private function deleteDirectory($dirPath)
    {
        if (!is_dir($dirPath)) {
            return false;
        }

        $items = array_diff(scandir($dirPath), ['.', '..']);
        foreach ($items as $item) {
            $path = $dirPath . DIRECTORY_SEPARATOR . $item;
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }

        return rmdir($dirPath);
    }

    private function parseJsonFile($filePath)
    {
        if (!file_exists($filePath)) {
            throw new Exception('module.json file is missing.');
        }
        $jsonContent = file_get_contents($filePath);
        return json_decode($jsonContent, true);
    }
}
