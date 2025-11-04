<?php

namespace App\DTO\Output\Pagination;

use Knp\Component\Pager\Pagination\PaginationInterface;

class PaginatedList
{
    public Pagination $pagination;
    public array $data;

    public function __construct(PaginationInterface $pagination)
    {
        $this->pagination = new Pagination($pagination);
        $this->data = $pagination->getItems();
    }
}
