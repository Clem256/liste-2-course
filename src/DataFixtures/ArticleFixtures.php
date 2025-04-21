<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Categorie;
use App\model\SucreSale;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public const ARTICLE_BANANE_REFERENCE = 'article_banane';

    public function load(ObjectManager $manager): void
    {
        $article = new Article();
        $article->setName("Banane");
        $article->setDescription("c'est une banane");
        $article->setQuantity(1);
        $article->setUnit("pièce");
        $article->setCategorie($this->getReference(CategorieFixtures::CATEGORIE_ALIMENTS_REFERENCE, Categorie::class));
        $article->setPrice(1.2);
        $article->setType(SucreSale::sucre);
        $this->addReference(self::ARTICLE_BANANE_REFERENCE, $article);

        $manager->persist($article);

        $manager->flush();
    }

    public function getDependencies(): array {
        return [
            CategorieFixtures::class
        ];
    }
}
