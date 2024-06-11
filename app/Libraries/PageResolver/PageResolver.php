<?php

namespace App\Libraries\PageResolver;

class PageResolver
{
    private AbstractPageResolver $resolverType;

    public function __construct(AbstractPageResolver $resolver)
    {
        $this->resolverType = $resolver;
    }

    public function resolve()
    {
        return $this->resolverType->resolve();
    }
}
