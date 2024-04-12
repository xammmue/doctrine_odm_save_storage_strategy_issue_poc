<?php

declare(strict_types=1);

namespace App\Repository;

use App\Document\Bookshelf;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;

class BookshelfRepository extends ServiceDocumentRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bookshelf::class);
    }
    public function save(Bookshelf $bookshelf): void
    {
        $this->dm->persist($bookshelf);
        $this->dm->flush();
    }

    public function findById(string $id): Bookshelf
    {
        return $this->find($id);
    }
}
