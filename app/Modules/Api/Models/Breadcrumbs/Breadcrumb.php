<?php


namespace Modules\Api\Models\Breadcrumbs;


class Breadcrumb
{
    private $link;
    private $title;
    private string $titleAttr = 'title';
    private string $linkAttr = 'link';

    public function __construct($link, $title)
    {
        $this->link = $link;
        $this->title = $title;
    }

    public function toArray(): array
    {
        return [
            $this->linkAttr => $this->link,
            $this->titleAttr => $this->title
        ];
    }

    /**
     * @param string $titleAttr
     * @return $this
     */
    public function setTitleAttr(string $titleAttr): self
    {
        $this->titleAttr = $titleAttr;
        return $this;
    }

    /**
     * @param string $linkAttr
     * @return $this
     */
    public function setLinkAttr(string $linkAttr): self
    {
        $this->linkAttr = $linkAttr;
        return $this;
    }
}
