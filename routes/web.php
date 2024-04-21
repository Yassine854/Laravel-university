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

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::group(['middleware' => 'auth'], function() {
    Route::group(['middleware' => 'role:student', 'prefix' => 'student', 'as' => 'student.'], function() {
        Route::resource('lessons', \App\Http\Controllers\Students\LessonController::class);
    });
   Route::group(['middleware' => 'role:teacher', 'prefix' => 'teacher', 'as' => 'teacher.'], function() {
       Route::resource('courses', \App\Http\Controllers\Teachers\CourseController::class);
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



        Route::resource('news', \App\Http\Controllers\Admin\NewController::class);
        Route::resource('subjects', \App\Http\Controllers\Admin\SubjectController::class);
        Route::resource('departments', \App\Http\Controllers\Admin\DepartmentController::class);
        Route::resource('fields', \App\Http\Controllers\Admin\FieldController::class);

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
