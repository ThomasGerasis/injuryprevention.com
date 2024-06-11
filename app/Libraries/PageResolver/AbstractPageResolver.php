<?php

namespace App\Libraries\PageResolver;

use App\Controllers\Page;

abstract class AbstractPageResolver
{
    protected string $pageId;
    protected Page $pageController;
    protected int $pageNumber;

    public function __construct(Page $pageController, $pageId, $pageNumber)
    {
        $this->pageController = $pageController;
        $this->pageId = $pageId;
        $this->pageNumber = $pageNumber;
    }

    abstract public function resolve(): string;
}
