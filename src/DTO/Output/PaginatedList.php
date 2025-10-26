<?php

namespace App\DTO\Output;

use Knp\Component\Pager\Pagination\PaginationInterface;

class PaginatedList
{
    public int $current_page;
    public int $total_items;
    public int $size;
    public int $total_pages;
    public array $data;

    public function __construct(PaginationInterface $pagination)
    {
        $this->current_page = $pagination->getCurrentPageNumber();
        $this->total_items = $pagination->getTotalItemCount();
        $this->size = $pagination->getItemNumberPerPage();
        $this->total_pages = (int)ceil($pagination->getTotalItemCount() / $pagination->getItemNumberPerPage());
        $this->data = $pagination->getItems();
    }
}
