<?php

namespace App\DataFixtures;

use App\Entity\ShoppingList;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ShoppingListFixtures extends Fixture implements DependentFixtureInterface {
    public const SHOPPING_LIST_COURSES_REFERENCE = 'shopping_list_courses';

    public function load(ObjectManager $manager): void {
        $shoppingList = new ShoppingList();
        $shoppingList->setNom("Ma liste de courses");
        $shoppingList->addConsommateur($this->getReference(UserFixtures::ADMIN_USER_REFERENCE, User::class));
        $this->addReference(self::SHOPPING_LIST_COURSES_REFERENCE, $shoppingList);

        $manager->persist($shoppingList);

        $manager->flush();
    }

    public function getDependencies(): array {
        return [
            UserFixtures::class
        ];
    }
}
