<?php

declare(strict_types=1);

namespace App\Tests\Document;

use App\Document\Book;
use App\Document\Bookshelf;
use App\Document\Page;
use App\Repository\BookshelfRepository;
use App\Tests\FixturesTrait;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UpdateBookshelfTest extends KernelTestCase
{
    use FixturesTrait;

    private BookshelfRepository $repository;

    protected function setUp(): void
    {
        $this->repository = static::getContainer()->get(BookshelfRepository::class);
    }

    protected function tearDown(): void
    {
        $this->tearDownMongoDB();
        parent::tearDown();
    }

    public function test_removeSinglePage(): void
    {
        //load fixtures
        $fixtures = $this->loadODMFixtureFiles([__DIR__ . '/fixtures/bookshelf.yaml']);
        $bookshelf = $fixtures['bookshelf'];
        $blueBook = $bookshelf->getBook('blue');
        $bluePageOne = $blueBook->getPages()[0];


        //remove first page of blue book
        $blueBook->removePage($bluePageOne);


        //save the bookshelf
        $bookshelf = $this->saveAndReloadBookshelf($bookshelf);


        //assert
        self::assertCount(3, $bookshelf->getBooks());
        self::assertCount(1, $bookshelf->getBooks()[0]->getPages());
        self::assertCount(1, $bookshelf->getBooks()[1]->getPages());
        self::assertCount(1, $bookshelf->getBooks()[2]->getPages());
    }

    public function test_removeSingleBook(): void
    {
        //load fixtures
        $fixtures = $this->loadODMFixtureFiles([__DIR__ . '/fixtures/bookshelf.yaml']);
        $bookshelf = $fixtures['bookshelf'];
        $redBook = $bookshelf->getBook('red');


        //remove the red book
        $bookshelf->removeBook($redBook);


        //save the bookshelf
        $bookshelf = $this->saveAndReloadBookshelf($bookshelf);


        //assert
        self::assertCount(2, $bookshelf->getBooks());
        self::assertCount(1, $bookshelf->getBooks()[0]->getPages());
        self::assertCount(2, $bookshelf->getBooks()[1]->getPages());
    }

    public function test_removeBookAndPage(): void
    {
        //load fixtures
        $fixtures = $this->loadODMFixtureFiles([__DIR__ . '/fixtures/bookshelf.yaml']);
        $bookshelf = $fixtures['bookshelf'];
        $redBook = $bookshelf->getBook('red');
        $blueBook = $bookshelf->getBook('blue');
        $bluePageOne = $blueBook->getPages()[0];


        //remove red book and first page of the blue book
        $bookshelf->removeBook($redBook);
        $blueBook->removePage($bluePageOne);


        //save the bookshelf
        $bookshelf = $this->saveAndReloadBookshelf($bookshelf);


        // this results in
        // - the red book being removed (as it should)
        // - the green book stays unchanged (as it should)
        // - no pages of the blue book being removed
        //
        // I expected that the first page of blue book was removed

        //assert
        self::assertCount(2, $bookshelf->getBooks());
        self::assertCount(1, $bookshelf->getBooks()[0]->getPages());
        self::assertCount(1, $bookshelf->getBooks()[1]->getPages());
    }

    public function test_reinitializePages(): void
    {
        //load fixtures
        $fixtures = $this->loadODMFixtureFiles([__DIR__ . '/fixtures/bookshelf.yaml']);
        $bookshelf = $fixtures['bookshelf'];
        $blueBook = $bookshelf->getBook('blue');


        //initialize the pages of the blue book again
        $blueBook->setPages($blueBook->getPages());


        //save the bookshelf
        $bookshelf = $this->saveAndReloadBookshelf($bookshelf);


        //assert
        self::assertCount(3, $bookshelf->getBooks());
        self::assertCount(1, $bookshelf->getBooks()[0]->getPages());
        self::assertCount(1, $bookshelf->getBooks()[1]->getPages());
        self::assertCount(2, $bookshelf->getBooks()[2]->getPages());
    }

    public function test_reinitializePagesAndDeleteOtherBook(): void
    {
        //load fixtures
        $fixtures = $this->loadODMFixtureFiles([__DIR__ . '/fixtures/bookshelf.yaml']);
        $bookshelf = $fixtures['bookshelf'];
        $redBook = $bookshelf->getBook('red');
        $blueBook = $bookshelf->getBook('blue');


        //remove red book and initialize the pages of the blue book again
        $bookshelf->removeBook($redBook);
        $blueBook->setPages($blueBook->getPages());


        //save the bookshelf
        $bookshelf = $this->saveAndReloadBookshelf($bookshelf);


        // this results in
        // - the red book being removed (as it should)
        // - the single page of the green book being removed
        // - the pages of the blue book being duplicated
        //
        // I expected that the red book would be deleted and the green and blue book would not change

        //assert
        self::assertCount(2, $bookshelf->getBooks(), 'should be 2 books as one has been removed');
        self::assertCount(
            1,
            $bookshelf->getBook('green')->getPages(),
            'should be 1 page as green book was not changed'
        );
        self::assertCount(
            2,
            $bookshelf->getBook('blue')->getPages(),
            'should be 2 pages as they have only been initialized again as in the first example'
        );
    }

    private function saveAndReloadBookshelf(Bookshelf $bookshelf): Bookshelf
    {
        $this->repository->save($bookshelf);

        $this->clearDocumentManager();

        return $this->repository->findById($bookshelf->getId());
    }

    private function clearDocumentManager(): void
    {
        static::getContainer()->get(DocumentManager::class)->clear();
    }
}
