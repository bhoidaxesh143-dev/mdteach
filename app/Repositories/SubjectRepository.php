<?php

namespace App\Repositories;

use App\Models\Subject;
use App\Repositories\Interfaces\SubjectRepositoryInterface;

class SubjectRepository extends BaseRepository implements SubjectRepositoryInterface
{
    public function __construct(Subject $subject)
    {
        $this->model = $subject;
    }
}
