<?php

namespace App\Tests\Entity;

use App\Entity\Manga;
use PHPUnit\Framework\TestCase;

class MangaTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $manga = new Manga();

        $manga->setTitle('Naruto');
        $manga->setDescription('A ninja story');
        $manga->setAuthor('Masashi Kishimoto');
        $manga->setCoverImage('https://example.com/naruto.jpg');
        $manga->setReleaseYear(1999);
        $manga->setGenres(['action', 'adventure']);
        $manga->setStatus('completed');
        $manga->setCreatedAt(new \DateTimeImmutable('2024-01-01'));

        self::assertSame('Naruto', $manga->getTitle());
        self::assertSame('A ninja story', $manga->getDescription());
        self::assertSame('Masashi Kishimoto', $manga->getAuthor());
        self::assertSame('https://example.com/naruto.jpg', $manga->getCoverImage());
        self::assertSame(1999, $manga->getReleaseYear());
        self::assertSame(['action', 'adventure'], $manga->getGenres());
        self::assertSame('completed', $manga->getStatus());
        self::assertNull($manga->getId());
    }

    public function testDefaultUserMangasIsEmpty(): void
    {
        $manga = new Manga();
        self::assertCount(0, $manga->getUserMangas());
    }

    public function testCreatedAtIsDateTimeImmutable(): void
    {
        $manga = new Manga();
        $date = new \DateTimeImmutable();
        $manga->setCreatedAt($date);

        self::assertInstanceOf(\DateTimeImmutable::class, $manga->getCreatedAt());
    }

    public function testReleaseYearIsInteger(): void
    {
        $manga = new Manga();
        $manga->setReleaseYear(2000);

        self::assertIsInt($manga->getReleaseYear());
    }

    public function testGenresIsArray(): void
    {
        $manga = new Manga();
        $manga->setGenres(['shonen', 'action']);

        self::assertIsArray($manga->getGenres());
        self::assertCount(2, $manga->getGenres());
    }
}