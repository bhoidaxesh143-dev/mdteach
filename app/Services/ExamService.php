<?php

namespace App\Services;

use App\Repositories\Interfaces\ExamRepositoryInterface;

class ExamService extends BaseService
{
    public function __construct(
        protected ExamRepositoryInterface $examRepository
    ) {}

    public function paginate()
    {
        return $this->examRepository->paginate();
    }

    public function create(array $data)
    {
        $data['created_by'] = auth()->id();

        return $this->examRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->examRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->examRepository->delete($id);
    }

    public function find(int $id)
    {
        return $this->examRepository->find($id);
    }
}
