<?php

namespace App\Repositories;

use stdClass;

interface PaginationInterface
{
    /**
     * Retorna uma lista de itens.
     *
     * @return array
     */
    public function items(): array;

    /**
     * Retorna o total de itens.
     *
     * @return int
     */
    public function total(): int;

    /**
     * Verifica se está na primeira página.
     *
     * @return bool
     */
    public function isFirstPage(): bool;

    /**
     * Verifica se está na última página.
     *
     * @return bool
     */
    public function isLastPage(): bool;

    /**
     * Retorna o número da página atual.
     *
     * @return int
     */
    public function currentPage(): int;

    /**
     * Retorna o número da próxima página.
     *
     * @return int
     */
    public function getNumberNextPage(): int;

    /**
     * Retorna o número da página anterior.
     *
     * @return int
     */
    public function getNumberPreviousPage(): int;
}
