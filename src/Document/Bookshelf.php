<?php

declare(strict_types=1);

namespace App\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document]
class Bookshelf
{
    #[ODM\Id]
    private string $id;
    #[ODM\EmbedMany(targetDocument: Book::class)]
    private Collection $books;

    public function __construct(array $books)
    {
        $this->books = new ArrayCollection($books);
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return Book[]
     */
    public function getBooks(): array
    {
        return $this->books->toArray();
    }

    public function getBook(string $name): Book
    {
        foreach ($this->books as $book) {
            if($book->getName() === $name) {
                return $book;
            }
        }
    }

    public function addBook(Book $book): void
    {
        $this->books->add($book);
    }

    public function removeBook(Book $book): void
    {
        $this->books->removeElement($book);
    }
}
