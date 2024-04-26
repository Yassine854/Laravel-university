<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('new/{id}', [\App\Http\Controllers\DashboardController::class, 'indexNew'])->name('indexNew');

});


Route::group(['middleware' => 'auth'], function() {
    Route::group(['middleware' => 'role:student', 'prefix' => 'student', 'as' => 'student.'], function() {
        // Route::resource('lessons', \App\Http\Controllers\Students\LessonController::class
        //Form
        Route::get('forms', [\App\Http\Controllers\Students\FormController::class, 'index'])->name('forms.index');
        Route::post('form/create', [\App\Http\Controllers\Students\FormController::class, 'create'])->name('forms.create');
        Route::put('form/{id}', [\App\Http\Controllers\Students\FormController::class, 'update'])->name('forms.update');
        Route::delete('form/{id}', [\App\Http\Controllers\Students\FormController::class, 'destroy'])->name('forms.destroy');
        Route::get('form/{fileName}', [\App\Http\Controllers\Students\FormController::class, 'download'])->name('forms.download');

        //Liste de groupe
        Route::get('groupe', [\App\Http\Controllers\Admin\UserController::class, 'indexGroupList'])->name('groupe.index');

        //Calendrier d'examens
        Route::get('calendar', [\App\Http\Controllers\Admin\ExamCalendarController::class, 'indexStudent'])->name('calendar.indexStudent');

        //Emplois du temps pour enseignant
        Route::get('timetable', [\App\Http\Controllers\Students\TimetableController::class, 'index'])->name('timetable.index');

        //Supports de cours
        Route::get('subjects', [\App\Http\Controllers\Students\CourseController::class, 'indexSubjects'])->name('indexSubjects');
        Route::get('courses/{subject}', [\App\Http\Controllers\Students\CourseController::class, 'show'])->name('courses.show');
        Route::get('course/{fileName}', [\App\Http\Controllers\Students\CourseController::class, 'downloadCourse'])->name('courses.downloadCourse');

        //Notes examen
        Route::get('semesters', [\App\Http\Controllers\Students\MarkController::class, 'indexSemesters'])->name('indexSemesters');
        Route::get('marks/{semester}', [\App\Http\Controllers\Students\MarkController::class, 'show'])->name('marks.show');

    });


   Route::group(['middleware' => 'role:teacher', 'prefix' => 'teacher', 'as' => 'teacher.'], function() {
    //Courses
    Route::get('subjects', [\App\Http\Controllers\Teachers\CourseController::class, 'indexSubjects'])->name('indexSubjects');
    Route::get('courses/{subject}', [\App\Http\Controllers\Teachers\CourseController::class, 'show'])->name('courses.show');
    Route::post('course/create', [\App\Http\Controllers\Teachers\CourseController::class, 'create'])->name('courses.create');
    Route::put('course/{id}', [\App\Http\Controllers\Teachers\CourseController::class, 'update'])->name('courses.update');
    Route::delete('course/{id}', [\App\Http\Controllers\Teachers\CourseController::class, 'destroy'])->name('courses.destroy');
    Route::get('course/{fileName}', [\App\Http\Controllers\Teachers\CourseController::class, 'downloadCourse'])->name('courses.downloadCourse');

    //Marks
    Route::get('semesters', [\App\Http\Controllers\Teachers\MarkController::class, 'indexSemesters'])->name('indexSemesters');
    Route::get('marks/{semester}', [\App\Http\Controllers\Teachers\MarkController::class, 'showSubjects'])->name('marks.showSubjects');
    Route::get('marks/{semester}/{subject}', [\App\Http\Controllers\Teachers\MarkController::class, 'showStudents'])->name('marks.showStudents');
    Route::get('marks/{semester}/{subject}/{student}', [\App\Http\Controllers\Teachers\MarkController::class, 'showMarks'])->name('marks.showMarks');
    Route::post('mark/create', [\App\Http\Controllers\Teachers\MarkController::class, 'create'])->name('marks.create');
    Route::put('mark/{id}', [\App\Http\Controllers\Teachers\MarkController::class, 'update'])->name('marks.update');

    //Calendrier d'examens
    Route::get('calendar', [\App\Http\Controllers\Admin\ExamCalendarController::class, 'indexTeacher'])->name('calendar.indexTeacher');

    //Emplois du temps pour enseignant
    Route::get('timetable', [\App\Http\Controllers\Teachers\TimetableController::class, 'index'])->name('timetable.index');
});
    Route::group(['middleware' => 'role:admin', 'prefix' => 'admin', 'as' => 'admin.'], function() {
        // Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
        //Admins
        Route::get('admins', [\App\Http\Controllers\Admin\UserController::class, 'indexAdmin'])->name('users.indexAdmin');
        Route::post('admins/create', [\App\Http\Controllers\Admin\UserController::class, 'createAdmin'])->name('users.createAdmin');
        Route::put('admins/{user}/updateAdmin', [\App\Http\Controllers\Admin\UserController::class, 'updateAdmin'])->name('users.updateAdmin');
        Route::delete('admins/{user}/destroyAdmin', [\App\Http\Controllers\Admin\UserController::class, 'destroyAdmin'])->name('users.destroyAdmin');
        //Teachers
        Route::get('teachers', [\App\Http\Controllers\Admin\UserController::class, 'indexTeacher'])->name('users.indexTeacher');
        Route::post('teachers/create', [\App\Http\Controllers\Admin\UserController::class, 'createTeacher'])->name('users.createTeacher');
        Route::put('teachers/{user}/updateTeacher', [\App\Http\Controllers\Admin\UserController::class, 'updateTeacher'])->name('users.updateTeacher');
        Route::delete('teachers/{user}/destroyTeacher', [\App\Http\Controllers\Admin\UserController::class, 'destroyTeacher'])->name('users.destroyTeacher');
        //Students
        Route::get('students', [\App\Http\Controllers\Admin\UserController::class, 'indexStudent'])->name('users.indexStudent');
        Route::post('students/create', [\App\Http\Controllers\Admin\UserController::class, 'createStudent'])->name('users.createStudent');
        Route::put('students/{user}/updateStudent', [\App\Http\Controllers\Admin\UserController::class, 'updateStudent'])->name('users.updateStudent');
        Route::delete('students/{user}/destroyStudent', [\App\Http\Controllers\Admin\UserController::class, 'destroyStudent'])->name('users.destroyStudent');
        Route::get('students/{user}/show', [\App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');

        //Emplois du temps pour Etudiants
        Route::get('timetables/departments', [\App\Http\Controllers\Admin\TimetableController::class, 'indexDepartments'])->name('timetables.departments');
        Route::get('timetables/{department}/fields', [\App\Http\Controllers\Admin\TimetableController::class, 'indexFields'])->name('timetables.fields');
        Route::get('timetables/{department}/{field}', [\App\Http\Controllers\Admin\TimetableController::class, 'indexStudentsTimetable'])->name('timetables.StudentsTimetable');
        Route::post('timetables/students/create', [\App\Http\Controllers\Admin\TimetableController::class, 'createStudentsTimetable'])->name('timetables.createStudentsTimetable');
        Route::put('timetables/{department}/{field}/{id}', [\App\Http\Controllers\Admin\TimetableController::class, 'updateStudentsTimetable'])->name('timetables.updateStudentsTimetable');
        Route::get('timetables/{fileName}', [\App\Http\Controllers\Admin\TimetableController::class, 'downloadTimetable'])->name('timetables.downloadTimetable');
        Route::delete('timetables/{department}/{field}/{id}', [\App\Http\Controllers\Admin\TimetableController::class, 'destroyStudentsTimetable'])->name('timetables.destroyStudentsTimetable');

        //Emplois du temps pour enseignants
        Route::get('teachers/timetables', [\App\Http\Controllers\Admin\TimetableController::class, 'indexTeachersTimetable'])->name('timetables.TeachersTimetable');
        Route::post('timetables/teachers/create', [\App\Http\Controllers\Admin\TimetableController::class, 'createTeachersTimetable'])->name('timetables.createTeachersTimetable');
        Route::put('timetables/{id}', [\App\Http\Controllers\Admin\TimetableController::class, 'updateTeachersTimetable'])->name('timetables.updateTeachersTimetable');
        Route::delete('timetables/{id}', [\App\Http\Controllers\Admin\TimetableController::class, 'destroyTeachersTimetable'])->name('timetables.destroyTeachersTimetable');

        //Calendrier d'examens
        Route::get('calendars', [\App\Http\Controllers\Admin\ExamCalendarController::class, 'index'])->name('calendars.index');
        Route::post('calendar/create', [\App\Http\Controllers\Admin\ExamCalendarController::class, 'create'])->name('calendars.create');
        Route::put('calendar/{id}', [\App\Http\Controllers\Admin\ExamCalendarController::class, 'update'])->name('calendars.update');
        Route::delete('calendar/{id}', [\App\Http\Controllers\Admin\ExamCalendarController::class, 'destroy'])->name('calendars.destroy');
        Route::get('calendar/{fileName}', [\App\Http\Controllers\Admin\ExamCalendarController::class, 'download'])->name('calendars.download');

        //Form
        Route::get('forms', [\App\Http\Controllers\Admin\FormController::class, 'index'])->name('forms.index');
        Route::put('form/{id}', [\App\Http\Controllers\Admin\FormController::class, 'update'])->name('forms.update');
        Route::get('form/{fileName}', [\App\Http\Controllers\Admin\FormController::class, 'download'])->name('forms.download');


        Route::resource('news', \App\Http\Controllers\Admin\NewController::class);
        Route::resource('subjects', \App\Http\Controllers\Admin\SubjectController::class);
        Route::resource('departments', \App\Http\Controllers\Admin\DepartmentController::class);
        Route::resource('fields', \App\Http\Controllers\Admin\FieldController::class);

        //Notification
        Route::post('/mark-notification-as-read/{notification}', [\App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('markNotificationAsRead');

    });
});



// function updateFieldSelectEdit(user) {
//     const userId = user.id;
//     var selectedDepartmentIdEdit = user.field.department_id;
//     console.log(selectedDepartmentIdEdit);
//     var fieldSelectEdit = document.getElementById('field_idEdit' + userId);
//     fieldSelectEdit.value = user.field_id;
// console.log(fieldSelectEdit.value);

//     // Clear existing options
//     fieldSelectEdit.innerHTML = '';

//     if (selectedDepartmentIdEdit !== '') {
//         // Iterate over fields and add options belonging to the selected department
//         @foreach ($fields as $field)
//             if ('{{ $field->department_id }}' === selectedDepartmentIdEdit) {
//                 var option = new Option('{{ $field->name }}', '{{ $field->id }}');
//                 fieldSelectEdit.appendChild(option);
//             }
//         @endforeach
//     }
//     fieldSelectEdit.value = fieldSelectEdit;

// }
