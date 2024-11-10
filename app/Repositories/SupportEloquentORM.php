<?php

namespace App\Repositories;

use app\DTO\Supports\CreateSupportDTO;
use app\DTO\Supports\UpdateSupportDTO;
use App\Models\Support;
use App\Repositories\PaginationInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use stdClass;

class SupportEloquentORM implements SupportRepositoryInterface
{
    public function __construct(
        protected Support $model
    ) {}

    /**
     * Paginate the results based on the given parameters.
     */
    public function paginate(int $page = 1, int $totalPerPage = 15, string $filter = null): PaginationInterface
    {
        $paginator = $this->model
                        ->where(function ($query) use ($filter) {
                            if ($filter) {
                                $query->where('subject', $filter);
                                $query->orWhere('body', 'like', "%{$filter}%");
                            }
                        })
                        ->paginate($totalPerPage, ['*'], 'page', $page);

        // Map to PaginationInterface
        return new class($paginator->items(), $paginator->total(), $paginator->currentPage(), $paginator->lastPage()) implements PaginationInterface {
            public function __construct(
                protected array $items,
                protected int $total,
                protected int $currentPage,
                protected int $lastPage
            ) {}

            public function items(): array
            {
                return $this->items;
            }

            public function total(): int
            {
                return $this->total;
            }

            public function isFirstPage(): bool
            {
                return $this->currentPage === 1;
            }

            public function isLastPage(): bool
            {
                return $this->currentPage === $this->lastPage;
            }

            public function currentPage(): int
            {
                return $this->currentPage;
            }

            public function getNumberNextPage(): int
            {
                return $this->currentPage + 1;
            }

            public function getNumberPreviousPage(): int
            {
                return $this->currentPage - 1;
            }
        };
    }

    /**
     * Get all records with optional filtering.
     */
    public function getAll(string $filter = null): array
    {
        return $this->model
                    ->where(function ($query) use ($filter) {
                        if ($filter) {
                            $query->where('subject', $filter);
                            $query->orWhere('body', 'like', "%{$filter}%");
                        }
                    })
                    ->get()
                    ->toArray();
    }

    /**
     * Find one record by its ID.
     */
    public function findOne(string $id): stdClass|null
    {
        $support = $this->model->find($id);
        if (!$support) {
            return null;
        }
        return (object) $support->toArray();
    }

    /**
     * Delete a record by its ID.
     */
    public function delete(string $id): void
    {
        $this->model->findOrFail($id)->delete();
    }

    /**
     * Create a new record using CreateSupportDTO.
     */
    public function new(CreateSupportDTO $dto): stdClass
    {
        $support = $this->model->create((array) $dto);
        return (object) $support->toArray();
    }

    /**
     * Update a record using UpdateSupportDTO.
     */
    public function update(UpdateSupportDTO $dto): stdClass|null
    {
        $support = $this->model->find($dto->id);

        if (!$support) {
            return null;
        }

        $support->update((array) $dto);
        return (object) $support->toArray();
    }
}

