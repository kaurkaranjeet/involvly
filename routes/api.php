<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
  |--------------------------------------------------------------------------
  | API Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register API routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | is assigned the "api" middleware group. Enjoy building your API!
  |
 */

Route::prefix('auth')->group(function () {
    // Below mention routes are public, user can access those without any restriction.
    // Admin Routes
    Route::post('admin-login', 'UserController@adminLogin');
    Route::any('/manage-admin-users', ['as' => 'manage.teachers', 'uses' => 'UserController@manageAdminUsers']);

    // Create New User
    Route::post('register', 'UserController@register');
    // Login User
    Route::post('login', 'UserController@login');
    Route::any('/manage-users/{id}', ['as' => 'manage.users', 'uses' => 'UserController@manageUsers']);
    Route::any('/fetch-user/{id}', ['as' => 'fetch.user', 'uses' => 'UserController@fetchUser']);
    Route::post('update-profile', 'UserController@UpdateProfile');
    Route::get('delete-user/{id}', 'UserController@RemoveUser');
    Route::get('user', 'UserController@getAuthenticatedUser');
    Route::get('get_total_statistic/{id}', 'UserController@gettotalStatistic');
    Route::get('get_total_records', 'UserController@gettotalRecords');
    Route::get('requests/{id}', 'UserController@getRequest');
    Route::get('teacher_requests', 'UserController@getteacherRequest');
    Route::get('web_school_admins', 'UserController@WebSchoolAdmins');
     
    Route::get('student_requests/{id}', 'UserController@getStudentRequest');
    Route::get('parent_requests/{id}', 'UserController@getParentRequest');

    Route::post('approve_teacher/{id}', 'TeacherController@Approveteacher');
    Route::post('disapprove_teacher/{id}', 'TeacherController@DisApproveteacher');
    //classes
    Route::any('/manage-classes/{id}', ['as' => 'manage.classes', 'uses' => 'ClassController@manageClasses']);
    Route::any('/list_of_subjects/{id}', ['as' => 'manage.list_of_subjects', 'uses' => 'SubjectController@manageSchoolSubjects']);
    Route::any('/save-class-code', ['as' => 'save.classes', 'uses' => 'ClassController@saveClassCode']);
    Route::any('/delete-class-code/{id}', ['as' => 'delete.classes', 'uses' => 'ClassController@deleteClassCode']);
    Route::any('/fetch-class-detail/{id}', ['as' => 'fetch.classes', 'uses' => 'ClassController@fetchClassCodeDetail']);
    Route::any('/edit-class-code', ['as' => 'edit.classes', 'uses' => 'ClassController@editClassCode']);
    //classes - subjects
    Route::any('/manage-subjects/{id}', ['as' => 'manage.subjects', 'uses' => 'SubjectController@manageSubjects']);
    Route::any('/save-subject', ['as' => 'save.subjects', 'uses' => 'SubjectController@saveSubject']);
    Route::any('/delete-subject/{id}', ['as' => 'delete.subjects', 'uses' => 'SubjectController@deleteSubject']);
    Route::any('/fetch-subject-detail/{id}', ['as' => 'fetch.subjects', 'uses' => 'SubjectController@fetchSubjectDetail']);
    Route::any('/edit-subject', ['as' => 'edit.subjects', 'uses' => 'SubjectController@editSubject']);

    //school - subjects
    Route::any('/manage-school-subjects/{id}', ['as' => 'manage.subjects', 'uses' => 'SubjectController@manageSubjectsAccToSchool']);
    Route::any('/save-school-subject', ['as' => 'save.subjects', 'uses' => 'SubjectController@saveSchoolSubject']);

    Route::post('/add-subject', ['as' => 'add.subject', 'uses' => 'SubjectController@AddSubject']);
    Route::post('/remove-subject', ['as' => 'remove.subject', 'uses' => 'SubjectController@RemoveSubject']);

    Route::any('/fetch-assigned-teachers', ['as' => 'assigned.teachers', 'uses' => 'TeacherController@fetchAssignedTeachersToClasses']);
    Route::post('/add-teacher-subject', ['as' => 'add.teacher', 'uses' => 'TeacherController@AddTeacherSubject']);
    Route::post('/remove-teacher-subject', ['as' => 'remove.teacher', 'uses' => 'TeacherController@RemoveTeacherSubject']);
    Route::post('refresh-token', ['as' => 'refresh', 'uses' => 'UserController@RefreshToken']);
    
});
// Mobile Apis



Route::prefix('v1')->group(function () {
    Route::post('forgot-password', 'Api\V1\LoginController@forgot_password');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('change-password', 'Auth\AuthController@change_password');
    });
    Route::post('login', 'Api\V1\LoginController@login');
    Route::post('logout', 'Api\V1\LoginController@Logout');
    Route::post('signup_student', 'Api\V1\StudentController@StudentRegister');
    Route::post('signup_teacher', 'Api\V1\TeacherController@TeacherRegister');
    Route::post('signup_parent', 'Api\V1\ParentController@ParentRegister');
    Route::post('get_cities', 'Api\V1\CommonController@GetCities');
    Route::get('list_states', 'Api\V1\CommonController@GetStates');
    Route::post('list_schools', 'Api\V1\CommonController@GetSchools');
    Route::get('list_subjects', 'Api\V1\CommonController@GetSubjects');
    Route::post('list_students', 'Api\V1\ParentController@GetStudents');
    Route::post('verify_otp', 'Api\V1\LoginController@VerifyOtp');
    Route::post('check_classcode', 'Api\V1\StudentController@Checkifclassvalid');
    Route::post('change_password', 'Api\V1\LoginController@ChnagePassword');
    Route::post('add_children', 'Api\V1\ParentController@AddChildren');
    Route::post('email_exist', 'Api\V1\LoginController@EmailExist');
    Route::post('join_community', 'Api\V1\CommonController@Joincommunity');
    Route::post('add_post', 'Api\V1\PostController@AddPost');
    Route::post('add_comment', 'Api\V1\PostController@AddComments');
    Route::get('run_migration', 'Api\V1\CommonController@RunMigration');
    Route::get('get_home_feed', 'Api\V1\PostController@GetPostHomefeed');
    Route::post('get_comments', 'Api\V1\PostController@GetComments');
    Route::post('like_post', 'Api\V1\PostController@LikeUnlikePost');
    Route::post('list_home_students', 'Api\V1\ParentController@GethomeStudents');
Route::post('all_notifications', 'Api\V1\NotificationController@AllNotifications');
    Route::post('add_reply_comment', 'Api\V1\PostController@AddReplyComments');
    Route::post('get_reply_comment', 'Api\V1\PostController@GetReplyComments');
    //Task
    Route::post('add_schedule_task', 'Api\V1\ParentController@AddScheduleTask');
    Route::post('get_schedule_task', 'Api\V1\ParentController@GetScheduleTask');
    Route::post('get_schedule_task_detail', 'Api\V1\ParentController@GetScheduleTaskDetail');
    Route::post('delete_schedule_task', 'Api\V1\ParentController@RemoveScheduleTask');
    //Assignment
    Route::post('add_assignment', 'Api\V1\AssignmentController@AddAssignment');
    Route::post('get_classes_by_teacher', 'Api\V1\AssignmentController@GetClassesByTeacher');
    Route::post('get_students_by_classes', 'Api\V1\AssignmentController@GetStudentsByClass');
    Route::post('add_assigned_assignments', 'Api\V1\AssignmentController@AddAssignedAssignment');
    Route::post('get_assignments_list', 'Api\V1\AssignmentController@GetAssignmentList');
    Route::post('get_assignments_details', 'Api\V1\AssignmentController@GetAssignmentDetails');
    Route::post('get_submitted_assignments', 'Api\V1\AssignmentController@GetSubmittedAssignment');
    Route::post('get_submitted_assignments_details', 'Api\V1\AssignmentController@GetSubmittedAssignmentDetails');
    Route::post('remove_assignment', 'Api\V1\AssignmentController@RemoveAssignments');

    //Route::post('add_report', 'Api\V1\PostController@AddReport');
    Route::post('get_related_parents', 'Api\V1\ParentController@GetRelatedParents');
    Route::post('delete_post', 'Api\V1\PostController@RemovePost');
    Route::post('getclasses', 'Api\V1\CommonController@GetClasses');
    Route::post('get_subjects_by_class', 'Api\V1\CommonController@GetSubjectsByClass');
    
    //update user profile
    Route::post('update_user_image', 'Api\V1\CommonController@UpdateUserImage');
    Route::post('update_user_name', 'Api\V1\CommonController@UpdateUserName');
    Route::post('update_user_password', 'Api\V1\CommonController@UpdateUserPassword');
    Route::post('delete_my_account', 'Api\V1\CommonController@DeleteAccount');
    
    Route::post('join_class_by_student', 'Api\V1\StudentController@JoinStudentByClass');
    Route::post('leave_class_by_student', 'Api\V1\StudentController@LeaveStudentByClass');
});

