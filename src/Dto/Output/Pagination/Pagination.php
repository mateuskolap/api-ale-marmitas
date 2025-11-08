<?php

namespace App\Dto\Output\Pagination;

use Knp\Component\Pager\Pagination\PaginationInterface;

class Pagination
{
    public int $current_page;
    public int $total_items;
    public int $per_page;
    public int $total_pages;

    public function __construct(PaginationInterface $pagination)
    {
        $this->current_page = $pagination->getCurrentPageNumber();
        $this->total_items = $pagination->getTotalItemCount();
        $this->per_page = $pagination->getItemNumberPerPage();
        $this->total_pages = (int)ceil($pagination->getTotalItemCount() / $pagination->getItemNumberPerPage());
    }
}
