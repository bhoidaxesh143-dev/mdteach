<?php

namespace App\Repositories;

use App\Repositories\Interfaces\BaseRepositoryInterface;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected $model;

    public function all()
    {
        return $this->model->all();
    }

    public function paginate(int $perPage = 15)
    {
        return $this->model->latest()->paginate($perPage);
    }

    public function find(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $record = $this->find($id);

        $record->update($data);

        return $record;
    }

    public function delete(int $id)
    {
        return $this->find($id)->delete();
    }
}
