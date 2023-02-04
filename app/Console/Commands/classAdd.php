<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\School;
use App\Models\Subject;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use PDO;

class classAdd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:class_add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will help to add remaing classes to all existing schools';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    { 
        
        $class_list = ["Infants", "Toddlers", "IEP", "Pre-K3", "Pre-K4", "General", "Special Needs", "Gifted Programs"];
        $classes = DB::table('class_code')->select('school_id')->groupBy('school_id')->get();

        if (!empty($count = count($class_list))) {
            foreach ($class_list as $key => $class_name) {
           
                $final_list = [];
                foreach ($classes as $data) {
                    $randomnum = rand(999, 9999);
                    $timezone = Carbon::now()->format('Y-m-d H:i:s');
                    $final_list[] = array(
                        'class_code' => $randomnum,
                        'approved' => '1',
                        'created_at' => $timezone,
                        'updated_at' => $timezone,
                        'class_name' =>  $class_name,
                        'school_id' => $data->school_id,
                    );
                }
                $final_chunck = array_chunk($final_list, 5000);

                foreach ($final_chunck as $insert_data) {
                    DB::table('class_code')->insert($insert_data);
                }
                // unset($final_chunck);
            }
            echo "Successfully Added Subjects!";
        } else {
            echo 'Already updated!';
        } 
    }
}
