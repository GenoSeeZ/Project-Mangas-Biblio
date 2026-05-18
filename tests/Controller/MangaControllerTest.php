<?php

namespace App\Tests\Controller;

use App\Entity\Manga;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class MangaControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->followRedirects();
        $this->manager = static::getContainer()->get('doctrine')->getManager();

        foreach ($this->manager->getRepository(Manga::class)->findAll() as $manga) {
            foreach ($manga->getUserMangas() as $userManga) {
                $this->manager->remove($userManga);
            }
            $this->manager->remove($manga);
        }
        foreach ($this->manager->getRepository(User::class)->findAll() as $user) {
            $this->manager->remove($user);
        }
        $this->manager->flush();
    }

    private function createAdminUser(): User
    {
        $user = new User();
        $user->setEmail('admin@test.com');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword('$2y$13$hashed_password_for_tests');
        $this->manager->persist($user);
        $this->manager->flush();
        return $user;
    }

    private function createManga(): Manga
    {
        $manga = new Manga();
        $manga->setTitle('Test Manga');
        $manga->setDescription('Test Description');
        $manga->setAuthor('Test Author');
        $manga->setCoverImage('https://example.com/cover.jpg');
        $manga->setReleaseYear(2020);
        $manga->setGenres(['action']);
        $manga->setStatus('ongoing');
        $manga->setCreatedAt(new \DateTimeImmutable());
        $this->manager->persist($manga);
        $this->manager->flush();
        return $manga;
    }

    public function testIndexIsAccessibleByEveryone(): void
    {
        $this->client->request('GET', '/manga');
        self::assertResponseIsSuccessful();
    }

    public function testShowIsAccessibleByEveryone(): void
    {
        $manga = $this->createManga();
        $this->client->request('GET', '/manga/' . $manga->getId());
        self::assertResponseIsSuccessful();
    }

    public function testNewRedirectsIfNotAdmin(): void
    {
        $this->client->followRedirects(false);
        $this->client->request('GET', '/manga/new');
        self::assertResponseRedirects('/login');
    }

    public function testAdminCanAccessNewForm(): void
    {
        $admin = $this->createAdminUser();
        $this->client->loginUser($admin);
        $this->client->request('GET', '/manga/new');
        self::assertResponseIsSuccessful();
    }

    public function testAdminCanCreateManga(): void
    {
        $admin = $this->createAdminUser();
        $this->client->loginUser($admin);
        $this->client->request('GET', '/manga/new');

        $this->client->submitForm('Save', [
            'manga[title]' => 'One Piece',
            'manga[description]' => 'A pirate adventure',
            'manga[author]' => 'Eiichiro Oda',
            'manga[coverImage]' => 'https://example.com/onepiece.jpg',
            'manga[releaseYear]' => 1997,
            'manga[genres]' => ['action'],
            'manga[status]' => 'ongoing',
        ]);

        self::assertResponseIsSuccessful();
        self::assertSame(1, $this->manager->getRepository(Manga::class)->count([]));
    }

    public function testAdminCanEditManga(): void
    {
        $admin = $this->createAdminUser();
        $this->client->loginUser($admin);
        $manga = $this->createManga();
        $mangaId = $manga->getId();

        $this->client->request('GET', '/manga/' . $mangaId . '/edit');

        $this->client->submitForm('Update', [
            'manga[title]' => 'Updated Title',
            'manga[description]' => 'Updated Description',
            'manga[author]' => 'Updated Author',
            'manga[coverImage]' => 'https://example.com/updated.jpg',
            'manga[releaseYear]' => 2021,
            'manga[genres]' => ['action'],
            'manga[status]' => 'completed',
        ]);

        self::assertResponseIsSuccessful();
        $updated = $this->manager->getRepository(Manga::class)->find($mangaId);
        self::assertSame('Updated Title', $updated->getTitle());
    }

    public function testAdminCanDeleteManga(): void
    {
        $admin = $this->createAdminUser();
        $this->client->loginUser($admin);
        $manga = $this->createManga();

        $this->client->followRedirects(false);
        $this->client->request('GET', '/manga/' . $manga->getId());
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/manga');
        self::assertSame(0, $this->manager->getRepository(Manga::class)->count([]));
    }
}