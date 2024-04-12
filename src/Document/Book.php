<?php

declare(strict_types=1);

namespace App\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\EmbeddedDocument]
class Book
{
    #[ODM\Field]
    private string $name;
    #[ODM\EmbedMany(targetDocument: Page::class)]
    private Collection $pages;

    public function __construct(string $name, array $pages)
    {
        $this->name = $name;
        $this->pages = new ArrayCollection($pages);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPages(): array
    {
        return $this->pages->toArray();
    }

    public function setPages(array $pages): void
    {
        $this->pages = new ArrayCollection($pages);
    }

    public function removePage(Page $page): void
    {
        $this->pages->removeElement($page);
    }

    public function addPage(Page $page): void
    {
        $this->pages->add($page);
    }

}
