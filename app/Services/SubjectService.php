<?php

namespace App\Services;

use App\Repositories\Interfaces\SubjectRepositoryInterface;

class SubjectService extends BaseService
{
    public function __construct(
        protected SubjectRepositoryInterface $subjectRepository
    ) {}

    public function paginate()
    {
        return $this->subjectRepository->paginate();
    }

    public function create(array $data)
    {
        return $this->subjectRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->subjectRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->subjectRepository->delete($id);
    }

    public function find(int $id)
    {
        return $this->subjectRepository->find($id);
    }
}
