<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture {
    public const ADMIN_USER_REFERENCE = 'user_admin';

    public function __construct(private readonly UserPasswordHasherInterface $userPasswordHasher) {
    }

    public function load(ObjectManager $manager): void {
        $user = new User();
        $user->setUsername('admin');
        $user->setEmail('minh@ad.fr');
        $user->setPassword($this->userPasswordHasher->hashPassword($user, "minh"));
        $user->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $this->addReference(self::ADMIN_USER_REFERENCE, $user);

        $manager->persist($user);

        $manager->flush();
    }
}
