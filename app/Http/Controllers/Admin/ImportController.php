<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Imports\StudentsImport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Imports\QuestionsImport;
use App\Models\Exam;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function createStudents()
    {
        return Inertia::render('Admin/Imports/Students');
    }

    public function storeStudents(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,csv'],
        ]);

        Excel::import(
            new StudentsImport,
            $request->file('file')
        );

        return back()->with(
            'success',
            'Students imported successfully.'
        );
    }

    public function createQuestions()
{
    return Inertia::render('Admin/Imports/Questions', [
        'exams' => Exam::all()
    ]);
}

public function storeQuestions(Request $request)
{
    $validated = $request->validate([
        'exam_id' => ['required', 'exists:exams,id'],
        'file' => ['required', 'file', 'mimes:xlsx,csv'],
    ]);

    Excel::import(
        new QuestionsImport($validated['exam_id']),
        $request->file('file')
    );

    return back()->with(
        'success',
        'Questions imported successfully.'
    );
}
}
