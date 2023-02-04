<?php

namespace App\Console\Commands;

use App\Models\School;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Console\Command;
use DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use PDO;

class SubjectAdd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:subject_add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will add subjects in subects with school information.';

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
        // return "jher";
        // it's the same instance.
        Log::Info('Memory now at: ' . memory_get_peak_usage());
        DB::connection()->getPdo() === DB::table('schools')->getConnection()->getPdo(); // true
        // set TRUE;
        DB::connection()->getPdo()->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
 
        ini_set('max_execution_time', '0'); // for infinite time of execution
        $subject_list = ["Math", "Test Preparation", "English", "Science", "Language", "Social Studies", "Computer", "Other", "Art", "Professional", "Music", "Athletics", "ACT", "SAT", "Actuarial Science", "Algebra 1", "Algebra 2", "Common Core", "Calculus", "Differential Equations", "Discrete Math", "Econometrics", "Elementary Math", "Finite Math", "Geometry", "Linear Algebra", "Logic", "Prealgebra", "Precalculus", "Probability", "Statistics", "Trigonometry", "ACT Writing", "ACT Math", "ACT Reading", "ACT Science", "Bar Exam", "CAHSEE", "CBEST", "CFA", "CLAST", "College Counseling", "COOP/HSPT", "DAT", "GED", "GMAT", "GRE", "IELTS", "ISEE", "LSAT", "MCAT", "OLSAT", "NCLEX", "Praxis", "PSAT", "Regents", "SAT Math", "SAT Reading", "SAT Writing", "SSAT", "STAAR", "TOEFL", "English (K-8)", "High School Level English", "College Level English", "ESL/ESOL", "Essay Writing", "Grammar", "Literature", "Reading &amp; Comprehension", "Phonics", "Proofreading", "Study Skills", "Vocabulary", "Anatomy", "Anthropology", "Archaeology", "Astronomy", "Biochemistry", "Biology", "Biomedical Engineering", "Biostatistics", "Botany", "Chemical Engineering", "Chemistry", "Civil Engineering", "Dentistry", "Earth Science", "Ecology", "Electrical Engineering", "Environmental Science", "Genetics", "Geology", "Health Science", "Life Sciences", "Mechanical Engineering", "Microbiology", "Nursing", "Nutrition", "Organic Chemistry", "Pharmacology", "Physical Science", "Physics", "Physiology", "Psychology", "Sociology", "Zoology", "Arabic", "Braille", "Bulgarian", "Cantonese", "Chinese", "Czech", "Dutch", "Farsi", "French", "German", "Greek", "Hebrew", "Hindi", "Hungarian", "Indonesian", "Italian", "Japanese", "Korean", "Latin", "Polish", "Portuguese", "Public Speaking", "Reading", "Romanian", "Russian", "Sign Language", "Spanish", "Thai", "Turkish", "Urdu", "Vietnamese", "U.S. History", "Bible Studies", "Classics", "Criminal Justice", "European History", "Geography", "Government &amp; Politics", "Music History", "Philosophy", "Political Science", "Religion", "Social History of Art", "World History", "Adobe Lightroom", "Adobe Photoshop", "C", "C#", "C++", "Computer Science", "Computer Gaming", "Dreamweaver", "Fortran", "HTML / CSS", "Java", "Mathematica", "MATLAB", "Microsoft Access", "Microsoft Excel", "Microsoft Outlook", "Microsoft PowerPoint", "Microsoft Project", "Microsoft Publisher", "Microsoft Word", "Networking", "Pascal", "PHP", "Python", "QuickBooks", "Revit", "Ruby", "SAS", "Sketchup", "SPSS", "SQL", "STATA", "UNIX", "Video Production", "Visual Basic", "Graphic Design", "Linux", "Macintosh", "JQuery", "Oracle", "Perl", "R", "JavaScript", "Angular", "Animation", "ASP.NET", "AutoCAD", "ADD/ADHD", "Aspergers", "Autism", "Dyslexia", "Etiquette Coach", "Hard of Hearing", "Homeschool", "Life Coach", "Social Coach", "Special Needs", "Architecture", "Art History", "Art Theory", "Ballroom Dancing", "Cosmetology", "Drawing", "Painting", "Photography", "Salsa Dancing", "Theatre", "Accounting", "Business", "Business Coach", "Career Development", "Finance", "Law", "Macroeconomics", "Marketing", "Online Marketing", "Microeconomics", "Project Management", "Tax Preparation", "Cello", "Clarinet", "Music Composition", "Drums", "Ear Training", "Flute", "French Horn", "General Music", "Guitar", "Harp", "Music Production", "Music Theory", "Oboe", "Piano", "Saxophone", "Sight Singing", "Songwriting", "Trombone", "Trumpet", "Violin", "Voice", "Ballet", "Baseball", "Basketball", "Bodybuilding", "Chess", "Cooking", "Fitness", "Football", "Golf", "Lacrosse", "Martial Arts", "Soccer", "Softball", "Swimming", "Tango", "Tennis", "Track &amp; Field", "Volleyball", "Water Polo", "Yoga", "Bass Guitar", "Upright Bass", "ASVAB", "Online", "Mandarin", "Language Arts", "History", "Reading", "Health,PE and Wellness", "Visual and Performing Arts", "Engineering", "Robotics", "Stem Program", "AP Class", "IB Class", "NSLC - (STEM and leadership summer program)", "NAGC - (gifted program)", "Junior ROTC program", "Pre - college immersive", "Discovery", "National Beta Club", "General Infants", "General Toddlers", "General Preschool"];
        //  $subject_list = ["Math", "Test Preparation"];
        $timezone = Carbon::now()->format('Y-m-d H:i:s');

        $subjects = DB::table('subjects')->select('school_id as id')->groupBy('school_id')->get()->toArray();
        $subjects_list = Arr::pluck($subjects, 'id');
        $school_loop = DB::table('schools')->select('id')->whereNotIn('id', $subjects_list)->get(); 
        
        if (!empty($count = count($school_loop))) {
            // echo "here";die;
            for ($i = 0; $i <= $count; $i++) {
                $subjects = DB::table('subjects')->select('school_id as id')->groupBy('school_id')->get()->toArray();
                $subjects_list = Arr::pluck($subjects, 'id');
                $schools_list = DB::table('schools')->select('id')->whereNotIn('id', $subjects_list)->take(65000)->get();
                    $final_list = [];
                    // $final_chunck = [];
                    foreach ($schools_list as $data) {
                        foreach ($subject_list as $sub_data) {
                            $final_list[] = array(
                                'school_id' => $data->id,
                                'created_at' => $timezone,
                                'updated_at' => $timezone,
                                'subject_name' => $sub_data,
                            );
                        }
                    }
                    $final_chunck = array_chunk($final_list, 5000);
                    // return $final_chunck;
                    foreach ($final_chunck as $faddaa) {
                        DB::table('subjects')->insert($faddaa);
                    }
                    $message =  "Successfully Added Subjects!";
            } 
        } 
        else {
            $message =  "List is upto dated!";  
        } 
        echo $message;
     
        // return array('error' => false, 'message' => $message);
    }
}
