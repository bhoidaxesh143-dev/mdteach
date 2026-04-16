<?php

namespace App\Http\Controllers\Student;

use Inertia\Inertia;
use App\Services\AllocationService;
use App\Http\Controllers\Controller;

class MyExamController extends Controller
{
    public function __construct(
        protected AllocationService $allocationService
    ) {}

  public function index()
{
    return Inertia::render('Student/Exams/MyExams', [
        'exams' => $this->allocationService->getStudentExams(auth()->id())
    ]);
}
}
