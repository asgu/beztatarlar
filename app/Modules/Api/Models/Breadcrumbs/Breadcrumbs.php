<?php


namespace Modules\Api\Models\Breadcrumbs;


use PhpParser\Node\Expr\Array_;

class Breadcrumbs
{
    private array $links = [];

    public function add(Breadcrumb $breadcrumb): self
    {
        $this->links[] = $breadcrumb;
        return $this;
    }

    public function toArray(): array
    {
        $res = [];
        foreach ($this->links as $link) {
            $res[] = $link->toArray();
        }
        return $res;
    }
}
