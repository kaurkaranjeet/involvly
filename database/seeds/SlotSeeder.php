<?php

use Illuminate\Database\Seeder;

class SlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data = [
        	['start_time'=>'12am', 'end_time'=> '3am'],
        	['start_time'=>'3am', 'end_time'=> '6am'],
        	['start_time'=>'6am', 'end_time'=> '9am'],
        	['start_time'=>'9am', 'end_time'=> '12pm'],
        	['start_time'=>'6am', 'end_time'=> '9am'],
        	['start_time'=>'12pm', 'end_time'=> '3pm'],
        	['start_time'=>'3pm', 'end_time'=> '6pm'],
        	['start_time'=>'6pm', 'end_time'=> '9pm'],
        	['start_time'=>'9pm', 'end_time'=> '12pm'],
];

         DB::table('slots')->insert($data);
    }
}
