<?php

declare(strict_types=1);

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\EmbeddedDocument]
class Page
{
    #[ODM\Field]
    private int $pageNumber;
    #[ODM\Field]
    private string $font;

    public function __construct(int $pageNumber, string $font)
    {
        $this->pageNumber = $pageNumber;
        $this->font = $font;
    }

    public function getPageNumber(): int
    {
        return $this->pageNumber;
    }

    public function setPageNumber(int $pageNumber): void
    {
        $this->pageNumber = $pageNumber;
    }

    public function getFont(): string
    {
        return $this->font;
    }

    public function setFont(string $font): void
    {
        $this->font = $font;
    }
}
