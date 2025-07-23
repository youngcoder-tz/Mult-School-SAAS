<?php

namespace App\Http\Controllers;

use App\Traits\General;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use File;
use ZipArchive;

class VersionUpdateController extends Controller
{
    use General;

    public function __construct()
    {
        // Logger removed as it's not needed
    }

    public function versionUpdate(Request $request)
    {
        return redirect('/');
    }

    public function processUpdate(Request $request)
    {
        Artisan::call('migrate --force');
        return redirect('/');
    }

    public function versionFileUpdate(Request $request)
    {
        if (!auth()->user()->can('manage_version_update')) {
            abort(403);
        }
    
        $data = [
            'title' => __('Version Update'),
            'subNavVersionUpdateActiveClass' => 'mm-active',
            'latestVersion' => '6.0',
            'latestBuildVersion' => '6.0.0',
            'addons' => [],
        ];
    
        $path = storage_path('app/source-code.zip');
        $data['uploadedFile'] = file_exists($path) ? 'source-code.zip' : '';
    
        try {
            $results = DB::select(DB::raw('select version()'));
            $data['mysql_version'] = $results[0]->{'version()'};
            $data['databaseType'] = str_contains($data['mysql_version'], 'Maria') ? 'MariaDB Version' : 'MySQL Version';
        } catch (\Exception $e) {
            $data['mysql_version'] = null;
        }
    
        return view('admin.version_update.create', $data);
    }
    
    public function versionFileUpdateStore(Request $request)
    {
        $request->validate([
            'update_file' => 'bail|required|mimes:zip'
        ]);
    
        set_time_limit(1200);
        $path = storage_path('app/source-code.zip');
    
        if (file_exists($path)) {
            File::delete($path);
        }
    
        try {
            $request->update_file->storeAs('/', 'source-code.zip');
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function executeUpdate()
    {
        set_time_limit(1200);
        $path = storage_path('app/source-code.zip');
        $demoPath = storage_path('app/updates');
        
        $response = [
            'success' => false,
            'message' => 'File not exist on storage!'
        ];

        if(file_exists($path)){
            $zip = new ZipArchive;

            if(is_dir($demoPath)){
                File::deleteDirectory($demoPath);
            }

            File::makeDirectory($demoPath, 0777, true, true);
            
            if ($zip->open($path) === true) {
                try {
                    $zip->extractTo($demoPath);
                    $versionFile = file_get_contents($demoPath.DIRECTORY_SEPARATOR.'update_note.json');
                    $updateNote = json_decode($versionFile);
                    $codeVersion = $updateNote->build_version;
                    $codeRootPath = $updateNote->root_path;
                    $currentVersion = getCustomerCurrentBuildVersion();

                    if($codeVersion > $currentVersion){
                        $allMoveFilePath = (array)($updateNote->code_path);
                        foreach($allMoveFilePath as $filePath => $type){ 
                            if($type == 'file'){
                                File::copy($demoPath.DIRECTORY_SEPARATOR.$codeRootPath.DIRECTORY_SEPARATOR.$filePath, base_path($filePath));
                            } else {
                                File::copyDirectory($demoPath.DIRECTORY_SEPARATOR.$codeRootPath.DIRECTORY_SEPARATOR.$filePath, base_path($filePath));
                            }
                        }
                        $response['success'] = true;
                        $response['message'] = 'Successfully done';
                    } else {
                        $response['message'] = 'Your code is not up to date';
                    }
                    
                    File::deleteDirectory($demoPath);
                    File::delete($path);
                    
                } catch(\Exception $e) {
                    Log::info($e->getMessage());
                    $response['message'] = $e->getMessage();
                }
                $zip->close();
            }
        }
                
        return $response;
    }
    
    public function versionUpdateExecute()
    {
        $response = $this->executeUpdate();
        if(!$response['success']) {
            $this->showToastrMessage('error', $response['message']);
        }
        return back();
    }
   
    public function versionFileUpdateDelete()
    {
        $path = storage_path('app/source-code.zip');
        if (file_exists($path)) {
            File::delete($path);
        }
    }
}