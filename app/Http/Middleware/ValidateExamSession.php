<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ExamAttempt;

class ValidateExamSession
{
    public function handle(Request $request, Closure $next)
    {
        $attemptId = $request->route('attempt');

        $attempt = ExamAttempt::find($attemptId);

        if (!$attempt || $attempt->status !== 'in_progress') {
            abort(403, 'Exam session invalid.');
        }

        return $next($request);
    }
}
