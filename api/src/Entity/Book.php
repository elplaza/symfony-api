<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * A book.
 */
#[ApiResource(mercure: true)]
#[ORM\Entity]
class Book
{
    /**
     * The entity ID
     */
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    /** The ISBN of this book (or null if doesn't have one). */
    #[ORM\Column(nullable: true)]
    #[Assert\Isbn]
    public ?string $isbn = null;

    /** The title of this book. */
    #[ORM\Column]
    #[Assert\NotBlank]
    public string $title = '';

    /** The description of this book. */
    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    public string $description = '';

    /** The author of this book. */
    #[ORM\Column]
    #[Assert\NotBlank]
    public string $author = '';

    /** The publication date of this book. */
    #[ORM\Column(type: 'datetime')]
    #[Assert\NotNull]
    public ?\DateTimeInterface $publicationDate = null;

    /**
     * @var Review[] Available reviews for this book.
     */
    #[ORM\OneToMany(targetEntity: "Review", mappedBy: "book", cascade: ["persist", "remove"])]
    public iterable $reviews;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}