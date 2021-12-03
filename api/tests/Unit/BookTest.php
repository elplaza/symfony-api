<?php 

use PHPUnit\Framework\TestCase;
use App\Entity\Book;

final class BookTest extends TestCase
{
    public function testBookExists(): void
    {
        $this->assertInstanceOf(Book::class, new Book);
    }

    public function testBookAttributes(): void
    {
        $book = new Book();

        $this->assertObjectHasAttribute('id', $book);
        $this->assertObjectHasAttribute('isbn', $book);
        $this->assertObjectHasAttribute('title', $book);
        $this->assertObjectHasAttribute('description', $book);
        $this->assertObjectHasAttribute('author', $book);
        $this->assertObjectHasAttribute('publicationDate', $book);
        $this->assertObjectHasAttribute('reviews', $book);
    }
}