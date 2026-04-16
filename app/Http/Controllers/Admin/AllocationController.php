<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Exam;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\AllocationService;
use App\Http\Controllers\Controller;

class AllocationController extends Controller
{
    public function __construct(
        protected AllocationService $allocationService
    ) {}

    public function create()
    {
        return Inertia::render('Admin/Allocations/Create', [
            'students' => User::whereHas('role', fn ($q) =>
                $q->where('name', 'student')
            )->get(),

            'exams' => Exam::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:users,id'],
            'exam_id' => ['required', 'exists:exams,id'],
        ]);

        $this->allocationService->allocate(
            $validated['student_id'],
            $validated['exam_id']
        );

        return back()->with(
            'success',
            'Exam allocated successfully.'
        );
    }
}
