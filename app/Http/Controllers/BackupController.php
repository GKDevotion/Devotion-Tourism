<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // return view('home');
    }

    /**
     *
     */
    public function getFullDatabaseBackup(){

        try{
            //Delete folders older than 15 days
            $path = storage_path('app/backup'); // Adjust as needed

            if (File::exists($path)) {
                $directories = File::directories($path);

                foreach ($directories as $directory) {
                    $modifiedTime = File::lastModified($directory);
                    $lastModifiedDate = Carbon::createFromTimestamp($modifiedTime);

                    if ($lastModifiedDate->lt(Carbon::now()->subDays(15))) {
                        File::deleteDirectory($directory);
                        echo "Deleted:".$directory."<br>";
                    }
                }
            }

            $tables = DB::select('SHOW TABLES');
            $tableNames = array_map('current', $tables);
            $date = date('Y-m-d');

            // Get table data
            foreach ($tableNames as $table) {

                if( $table === "countries" || $table === "cities" || $table == "states" ){
                    continue;
                }

                // Step 1: Get Table Structure
                $structure = "DROP TABLE IF EXISTS `$table`;\n";
                $createTable = DB::select("SHOW CREATE TABLE `$table`")[0]->{'Create Table'};
                $structure .= $createTable . ";\n\n";

                $fullData = DB::table($table)->get();

                // Generate SQL Insert Statements
                $insertData = "";
                if ($fullData->isNotEmpty()) {

                    // Split the array into chunks of 20,000 records
                    $chunks = array_chunk( $fullData->toArray(), 20000 );

                    $insertChunkData = "";
                    foreach ($chunks as $data) {

                        $insertChunkData = "INSERT INTO `$table` VALUES\n";

                        foreach ($data as $row) {
                            $values = array_map(fn($value) => "'".addslashes($value)."'", (array)$row);
                            $insertChunkData .= "(" . implode(', ', $values) . "),\n";
                        }

                        $insertChunkData.= rtrim($insertChunkData, ",\n") . ";\n";
                    }

                    $insertData = $insertChunkData;
                }

                // Combine Structure + Data
                $sql = "-- Backup for table `$table`\n\n" . $structure . $insertData;

                // Step 3: Save the File in Storage
                Storage::disk('local')->put( "backups/".$date."/".$table, $sql);

                // Step 4: Download the File
                // return response()->download(storage_path("app/backups/{$fileName}"));
            }

            echo "Successfully Download Backup: ".$path;
            return true;

        } catch( Exception $e){
            session()->flash('error', "We couldn't complete your request. Please log in again.");
            return false;
        }
    }
}
