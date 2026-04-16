<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\User;
use App\Models\Subject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\StudentSubjectAllocation;

class StudentSubjectAllocationController extends Controller
{
    public function create(Request $request)
    {
        $selectedStudentId = $request->student_id;

        $allocatedSubjectIds = [];

        if ($selectedStudentId) {
            $allocatedSubjectIds = StudentSubjectAllocation::where(
                'student_id',
                $selectedStudentId
            )
            ->pluck('subject_id');
        }

        return Inertia::render(
            'Admin/Users/AllocateSubjects',
            [
                'students' => User::whereHas(
                    'role',
                    fn ($q) => $q->where('name', 'student')
                )
                ->select('id', 'name')
                ->get(),

                'subjects' => Subject::select(
                    'id',
                    'name'
                )->get(),

                'selectedStudentId' => $selectedStudentId,
                'allocatedSubjectIds' => $allocatedSubjectIds,
            ]
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:users,id'],
            'subject_ids' => ['nullable', 'array'],
            'subject_ids.*' => ['exists:subjects,id'],
        ]);

        StudentSubjectAllocation::where(
            'student_id',
            $validated['student_id']
        )->delete();

        foreach ($validated['subject_ids'] as $subjectId) {
            StudentSubjectAllocation::create([
                'student_id' => $validated['student_id'],
                'subject_id' => $subjectId,
                'allocated_by' => auth()->id(),
            ]);
        }

        return back()->with(
            'success',
            'Subjects updated successfully.'
        );
    }
}
