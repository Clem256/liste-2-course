<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CategorieFixtures extends Fixture {
    public const CATEGORIE_ALIMENTS_REFERENCE = 'cat_aliments';

    public function load(ObjectManager $manager): void {
        $cat = new Categorie();
        $cat->setNom("Aliments");
        $this->addReference(self::CATEGORIE_ALIMENTS_REFERENCE, $cat);

        $manager->persist($cat);

        $manager->flush();
    }
}
