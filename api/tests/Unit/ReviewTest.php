<?php 

use PHPUnit\Framework\TestCase;
use App\Entity\Review;

final class ReviewTest extends TestCase
{
    public function testReviewExists(): void
    {
        $this->assertInstanceOf(Review::class, new Review);
    }

    public function testReviewAttributes(): void
    {
        $review = new Review();

        $this->assertObjectHasAttribute('id', $review);
        $this->assertObjectHasAttribute('rating', $review);
        $this->assertObjectHasAttribute('body', $review);
        $this->assertObjectHasAttribute('author', $review);
        $this->assertObjectHasAttribute('publicationDate', $review);
        $this->assertObjectHasAttribute('book', $review);
    }
}