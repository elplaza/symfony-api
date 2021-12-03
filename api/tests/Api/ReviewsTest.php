<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Review;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class ReviewsTest extends ApiTestCase
{
    // This trait provided by AliceBundle will take care of refreshing the database content to a known state before each test
    use RefreshDatabaseTrait;

    public function testGetCollection(): void
    {
        // The client implements Symfony HttpClient's `HttpClientInterface`, and the response `ResponseInterface`
        $response = static::createClient()->request('GET', '/reviews');

        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        // Asserts that the returned JSON is a superset of this one
        $this->assertJsonContains([
            '@context' => '/contexts/Review',
            '@id' => '/reviews',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 200,
            'hydra:view' => [
                '@id' => '/reviews?page=1',
                '@type' => 'hydra:PartialCollectionView',
                'hydra:first' => '/reviews?page=1',
                'hydra:last' => '/reviews?page=7',
                'hydra:next' => '/reviews?page=2',
            ],
        ]);

        // Because test fixtures are automatically loaded between each test, you can assert on them
        $this->assertCount(30, $response->toArray()['hydra:member']);

        // Asserts that the returned JSON is validated by the JSON Schema generated for this resource by API Platform
        // This generated JSON Schema is also used in the OpenAPI spec!
        $this->assertMatchesResourceCollectionJsonSchema(Review::class);
    }

    public function testCreateReview(): void
    {
        $getBooks = static::createClient()->request('GET', '/books?page=1');
        $books = $getBooks->toArray()['hydra:member'];

        $bookIri = $books[0]["@id"];

        $response = static::createClient()->request('POST', '/reviews', ['json' => [
            'rating' => 4,
            'body' => 'Good',
            'author' => 'Margaret Atwood',
            'publicationDate' => '1985-07-31T00:00:00+00:00',
            'book' => $bookIri
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/contexts/Review',
            '@type' => 'Review',
            'rating' => 4,
            'body' => 'Good',
            'author' => 'Margaret Atwood',
            'publicationDate' => '1985-07-31T00:00:00+00:00',
            'book' => $bookIri
        ]);
        $this->assertMatchesRegularExpression('~^/reviews/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(Review::class);
    }

    public function testCreateInvalidReview(): void
    {
        static::createClient()->request('POST', '/reviews', ['json' => [
            'rating' => 7,
        ]]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/contexts/ConstraintViolationList',
            '@type' => 'ConstraintViolationList',
            'hydra:title' => 'An error occurred',
            'hydra:description' => 'rating: This value should be between 0 and 5.
body: This value should not be blank.
author: This value should not be blank.
publicationDate: This value should not be null.
book: This value should not be null.',
        ]);
    }

    public function testUpdateReview(): void
    {
        $client = static::createClient();
        // findIriBy allows to retrieve the IRI of an item by searching for some of its properties.
        // Because Alice seed 200 reviews with rating beetwen 0 and 5, we're sure that almost one with rating 3 will always be generated.
        $iri = $this->findIriBy(Review::class, ['rating' => 3]);

        $client->request('PUT', $iri, ['json' => [
            'author' => 'updated author',
        ]]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@id' => $iri,
            'rating' => 3,
            'author' => 'updated author',
        ]);
    }

    public function testDeleteReview(): void
    {
        $client = static::createClient();
        $iri = $this->findIriBy(Review::class, ['rating' => 3]);

        $client->request('DELETE', $iri);

        $id = intval($iri);

        $this->assertResponseStatusCodeSame(204);
        $this->assertNull(
            // Through the container, you can access all your services from the tests, including the ORM, the mailer, remote API clients...
            static::$container->get('doctrine')->getRepository(Review::class)->findOneBy(['id' => $id])
        );
    }

}