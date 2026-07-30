<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Controllers
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

use App\Http\Controllers\Admin\{
    AllocationController,
    ChapterController,
    DashboardController,
    ExamController,
    ExportController,
    ImportController,
    QuestionController,
    StudentSubjectAllocationController,
    SubjectController,
    TopicContentController,
    TopicController,
    TopicQuestionMapController,
    UploadController,
    UserController
};

use App\Http\Controllers\Student\{
    AttemptController,
    BookmarkController,
    LearningController,
    LearningProgressController,
    MyExamController,
    ResultController,
    RevisionDashboardController,
    StudentDashboardController,
    TopicNoteController,
    TopicPracticeController
};

/*
|--------------------------------------------------------------------------
| Root Redirect
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (!Auth::check()) {
        return redirect('/login');
    }

    return match (Auth::user()->role->name) {
        'super_admin', 'exam_creator' => redirect('/admin/dashboard'),
        'student' => redirect()->route('student.dashboard'),
        default => abort(403),
    };
});

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);

    // Route::get('/register', [RegisterController::class, 'create']);
    // Route::post('/register', [RegisterController::class, 'store']);
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:super_admin,exam_creator'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

 Route::get(
    '/student-subject-allocation',
    [StudentSubjectAllocationController::class, 'create']
)->name('student-subject-allocation.create');

Route::post(
    '/student-subject-allocation',
    [StudentSubjectAllocationController::class, 'store']
)->name('student-subject-allocation.store');

      Route::resource('users', UserController::class);

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | Core Resources
        |--------------------------------------------------------------------------
        */
        Route::resource('exams', ExamController::class);
        Route::resource('subjects', SubjectController::class);
        Route::resource('chapters', ChapterController::class);
        Route::resource('topics', TopicController::class);

        /*
        |--------------------------------------------------------------------------
        | Exam Questions
        |--------------------------------------------------------------------------
        */
        Route::prefix('exams/{exam}/questions')
            ->name('questions.')
            ->group(function () {

                Route::get('/', [QuestionController::class, 'index'])
                    ->name('index');

                Route::get('/create', [QuestionController::class, 'create'])
                    ->name('create');

                Route::post('/', [QuestionController::class, 'store'])
                    ->name('store');
            });

        Route::prefix('questions/{question}')
            ->group(function () {

                Route::get('/edit', [QuestionController::class, 'edit'])
                    ->name('questions.edit');

                Route::put('/', [QuestionController::class, 'update'])
                    ->name('questions.update');

                Route::delete('/', [QuestionController::class, 'destroy'])
                    ->name('questions.destroy');
            });

        /*
        |--------------------------------------------------------------------------
        | Topic Question Mapping
        |--------------------------------------------------------------------------
        */
        Route::get(
            '/topics/{topic}/questions/edit',
            [TopicQuestionMapController::class, 'edit']
        )->name('topic-questions.edit');

        Route::put(
            '/topics/{topic}/questions',
            [TopicQuestionMapController::class, 'update']
        )->name('topic-questions.update');

        /*
        |--------------------------------------------------------------------------
        | Topic Content
        |--------------------------------------------------------------------------
        */
        Route::prefix('topic-content')
            ->name('topic-content.')
            ->group(function () {

                Route::get('/', [TopicContentController::class, 'index'])
                    ->name('index');

                Route::get('/{topic}/edit', [TopicContentController::class, 'edit'])
                    ->name('edit');

                Route::put('/{topic}', [TopicContentController::class, 'update'])
                    ->name('update');
            });

        /*
        |--------------------------------------------------------------------------
        | Allocations
        |--------------------------------------------------------------------------
        */
        Route::get('/allocations/create', [AllocationController::class, 'create'])
            ->name('allocations.create');

        Route::post('/allocations', [AllocationController::class, 'store'])
            ->name('allocations.store');

        /*
        |--------------------------------------------------------------------------
        | Imports
        |--------------------------------------------------------------------------
        */
        Route::prefix('imports')
            ->name('imports.')
            ->group(function () {

                Route::get('/students', [ImportController::class, 'createStudents'])
                    ->name('students.create');

                Route::post('/students', [ImportController::class, 'storeStudents'])
                    ->name('students.store');

                Route::get('/questions', [ImportController::class, 'createQuestions'])
                    ->name('questions.create');

                Route::post('/questions', [ImportController::class, 'storeQuestions'])
                    ->name('questions.store');
            });

        /*
        |--------------------------------------------------------------------------
        | Reports / Exports
        |--------------------------------------------------------------------------
        */
        Route::get('/reports', [ExportController::class, 'index'])
            ->name('reports.index');

        Route::prefix('exports')
            ->name('exports.')
            ->group(function () {

                Route::get('/results', [ExportController::class, 'examResults'])
                    ->name('results');

                Route::get('/results/{exam}', [ExportController::class, 'examResults'])
                    ->name('results.exam');

                Route::get('/results-pdf', [ExportController::class, 'examResultsPdf'])
                    ->name('results.pdf');

                Route::get('/results-pdf/{exam}', [ExportController::class, 'examResultsPdf'])
                    ->name('results.pdf.exam');
            });


            });

            Route::post('/admin/upload-image', [UploadController::class, 'store']);
            Route::post('/editor/upload', [UploadController::class, 'store'])->name('editor.upload');

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])
            ->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | Exams
        |--------------------------------------------------------------------------
        */
        Route::get('/my-exams', [MyExamController::class, 'index'])
            ->name('my-exams');

        Route::post('/exam/{exam}/start', [AttemptController::class, 'start'])
            ->name('exam.start');

        Route::get('/attempt/{attempt}', [AttemptController::class, 'show'])
            ->name('attempt.show');

        Route::post('/attempt/{attempt}/save-answer', [AttemptController::class, 'saveAnswer'])
            ->name('attempt.save-answer');

        Route::post('/attempt/{attempt}/submit', [AttemptController::class, 'submit'])
            ->name('attempt.submit');

        /*
        |--------------------------------------------------------------------------
        | Results
        |--------------------------------------------------------------------------
        */
        Route::get('/results', [ResultController::class, 'index'])
            ->name('results.index');

        Route::get('/results/{attempt}', [ResultController::class, 'show'])
            ->name('results.show');

        /*
        |--------------------------------------------------------------------------
        | Learning Hub
        |--------------------------------------------------------------------------
        */
        Route::prefix('learning')
            ->name('learning.')
            ->group(function () {

                Route::get('/', [LearningController::class, 'index'])
                    ->name('index');

                Route::get('/topic/{topic}', [LearningController::class, 'showTopic'])
                    ->name('topic.show');

                Route::post('/topic/{topic}/complete', [LearningProgressController::class, 'markComplete'])
                    ->name('topic.complete');

                Route::post('/topic/{topic}/bookmark', [BookmarkController::class, 'toggle'])
                    ->name('bookmark');

                Route::post('/topic/{topic}/notes', [TopicNoteController::class, 'save'])
                    ->name('notes');
            });

        /*
        |--------------------------------------------------------------------------
        | Topic Practice
        |--------------------------------------------------------------------------
        */
        Route::get('/learning/topic/{topic}/practice', [TopicPracticeController::class, 'show'])
            ->name('topic-practice.show');

        Route::post('/learning/topic/{topic}/practice', [TopicPracticeController::class, 'submit'])
            ->name('topic-practice.submit');

        Route::get('/learning/practice-result/{attempt}', [TopicPracticeController::class, 'result'])
            ->name('topic-practice.result');

        /*
        |--------------------------------------------------------------------------
        | Revision Dashboard
        |--------------------------------------------------------------------------
        */
        Route::get('/revision-dashboard', [RevisionDashboardController::class, 'index'])
            ->name('revision.dashboard');
    });
