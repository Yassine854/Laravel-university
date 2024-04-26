<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers\Teachers;

use App\Models\Mark;
use App\Models\User;
use App\Models\Course;
use App\Models\Subject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MarkController extends Controller
{
    public function indexSemesters()
    {
        $semesters = ['1','2'];
        return view('teacher.marks.semester', ['semesters' => $semesters]);
    }

    public function showSubjects($semester)
    {
        $subjects = auth()->user()->subjects->where('semester', $semester);
        return view('teacher.marks.subject', ['semester' => $semester,'subjects'=>$subjects]);
    }

    public function showStudents($semester,$subject)
    {
        $getSubject=Subject::where('id',$subject)->first();
        $students = User::where('field_id',$getSubject->field_id)->get();
        return view('teacher.marks.student', ['semester' => $semester,'subject'=>$subject,'students'=>$students,'getSubject'=>$getSubject]);
    }

    public function showMarks($semester, $subjectId, $studentId)
{
    $getSubject = Subject::find($subjectId);
    $getStudent = User::find($studentId);

    $marks = Mark::where('subject_id', $subjectId)
                 ->whereHas('subject', function ($query) use ($semester) {
                     $query->where('semester', $semester);
                 })
                 ->where('student_id', $studentId)
                 ->get();

    return view('teacher.marks.mark', [
        'semester' => $semester,
        'subject' => $subjectId,
        'student' => $studentId,
        'marks' => $marks,
        'getSubject' => $getSubject,
        'getStudent' => $getStudent
    ]);
}




    public function create(Request $request)
    {
        Session::put('form_type', 'create');


        $rules = [
            'note_exam' => 'required|numeric|min:0|max:20',
            'note_ds' => 'nullable|numeric|min:0|max:20',
            'note_tp' => 'nullable|numeric|min:0|max:20',
            'subject_id' => 'required',
            'student_id' => 'required',

        ];


        $messages = [
            'note_exam.required' => "La note d'examen est obligatoire.",
            'note_exam.numeric' => "La note d'examen doit être un nombre.",
            'note_exam.min' => "La note d'examen doit être supérieure ou égale à :min.",
            'note_exam.max' => "La note d'examen doit être inférieure ou égale à :max.",
            'note_ds.numeric' => "La note de DS doit être un nombre.",
            'note_ds.min' => "La note de DS doit être supérieure ou égale à :min.",
            'note_ds.max' => "La note de DS doit être inférieure ou égale à :max.",
            'note_tp.numeric' => "La note de TP doit être un nombre.",
            'note_tp.min' => "La note de TP doit être supérieure ou égale à :min.",
            'note_tp.max' => "La note de TP doit être inférieure ou égale à :max.",
            'subject_id.required' => "L'identifiant du sujet est obligatoire.",
            'student_id.required' => "L'identifiant de l'étudiant est obligatoire.",
        ];


        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with(['operation' => 'create']);
        }

        $semester=$request->input('semester');
        $mark = new Mark();

        $mark->note_exam = $request->input('note_exam');
        $mark->note_ds = $request->input('note_ds');
        $mark->note_tp = $request->input('note_tp');
        $mark->subject_id = $request->input('subject_id');
        $mark->student_id = $request->input('student_id');



        $mark->save();

        Session::flash('alert-success', 'success');
        return redirect()->route('teacher.marks.showMarks', ['semester' => $semester,'subject'=>$mark->subject_id,'student'=>$mark->student_id])->with('success', 'Notes ajoutées avec succées !');

    }


    public function update(Request $request,$id)
    {
        Session::put('form_type', 'edit');
        session()->put('edit_mark_id', $id);

        $mark = Mark::findOrFail($id);

        $rules = [
            'note_exam' => 'required|numeric|min:0|max:20',
            'note_ds' => 'nullable|numeric|min:0|max:20',
            'note_tp' => 'nullable|numeric|min:0|max:20',
            'subject_id' => 'required',
            'student_id' => 'required',

        ];


        $messages = [
            'note_exam.required' => "La note d'examen est obligatoire.",
            'note_exam.numeric' => "La note d'examen doit être un nombre.",
            'note_exam.min' => "La note d'examen doit être supérieure ou égale à :min.",
            'note_exam.max' => "La note d'examen doit être inférieure ou égale à :max.",
            'note_ds.numeric' => "La note de DS doit être un nombre.",
            'note_ds.min' => "La note de DS doit être supérieure ou égale à :min.",
            'note_ds.max' => "La note de DS doit être inférieure ou égale à :max.",
            'note_tp.numeric' => "La note de TP doit être un nombre.",
            'note_tp.min' => "La note de TP doit être supérieure ou égale à :min.",
            'note_tp.max' => "La note de TP doit être inférieure ou égale à :max.",
            'subject_id.required' => "L'identifiant du sujet est obligatoire.",
            'student_id.required' => "L'identifiant de l'étudiant est obligatoire.",
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with(['operation' => 'create']);
        }


        $mark->note_exam = $request->input('note_exam');
        $mark->note_tp = $request->input('note_tp');
        $mark->note_ds = $request->input('note_ds');



        $mark->save();

        Session::flash('alert-warning', 'warning');
        return redirect()->back()->with('warning', 'notes modifiées avec succées.');
    }




}
