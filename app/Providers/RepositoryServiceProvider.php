<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\ExamRepository;
use App\Repositories\QuestionRepository;
use App\Repositories\SubjectRepository;

use App\Repositories\Interfaces\ExamRepositoryInterface;
use App\Repositories\Interfaces\QuestionRepositoryInterface;
use App\Repositories\Interfaces\SubjectRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            ExamRepositoryInterface::class,
            ExamRepository::class
        );

        $this->app->bind(
            QuestionRepositoryInterface::class,
            QuestionRepository::class
        );

        $this->app->bind(
            SubjectRepositoryInterface::class,
            SubjectRepository::class
        );
    }

    public function boot(): void
    {
        //
    }
}
