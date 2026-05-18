<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setPassword('hashed_password');

        self::assertSame('test@example.com', $user->getEmail());
        self::assertSame('hashed_password', $user->getPassword());
        self::assertNull($user->getId());
    }

    public function testDefaultRoleIsUser(): void
    {
        $user = new User();
        self::assertContains('ROLE_USER', $user->getRoles());
    }

    public function testAdminRole(): void
    {
        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);

        self::assertContains('ROLE_ADMIN', $user->getRoles());
        self::assertContains('ROLE_USER', $user->getRoles());
    }

    public function testUserIdentifierIsEmail(): void
    {
        $user = new User();
        $user->setEmail('test@example.com');

        self::assertSame('test@example.com', $user->getUserIdentifier());
    }

    public function testDefaultUserMangasIsEmpty(): void
    {
        $user = new User();
        self::assertCount(0, $user->getUserMangas());
    }
}