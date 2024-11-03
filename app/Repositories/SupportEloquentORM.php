<?php

use app\DTO\CreateSupportDTO;
use app\DTO\UpdateSupportDTO;
use App\Models\Support;
use App\Repositories\SupportRepositoryInterface;

class SupportEloquentORM implements SupportRepositoryInterface
{
    public function __construct(
        protected Support $model
    ){}

    public function paginate(int $page = 1, int $totalPerPage = 15, string $filter = null): PaginationInterface
    {
        $result = $this->model
                        ->where(function ($query) use ($filter) {
                            if ($filter) {
                                $query->where('subject', $filter);
                                $query->orWhere('body', 'like', "%{$filter}%");
                            }
                        })
                        ->paginate($totalPerPage, ['*'], 'page', $page)
                        ->toArray();
    }

    public function getAll(string $filter = null): array
    {
        return $this->model
                    ->where(function ($query) use ($filter) {
                        if ($filter) {
                            $query->where('subject', $filter);
                            $query->orWhere('body', 'like', "%{$filter}%");
                        }
                    })
                    ->all()
                    ->toArray();
    }

    public function findOne(string $id): stdClass|null
    {
        $support = $this->model->find($id);
        if (!$support){
            return null;
        }
        return (object) $support->toArray();
    }


    public function delete(string $id): void
    {
        $this->model->findOrFail($id)->delete();
    }


    public function new(CreateSupportDTO $dto): stdClass
    {

    }


    public function update(UpdateSupportDTO $dto): stdClass|null
    {

    }
}