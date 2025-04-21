<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\ShoppingList;
use App\Entity\ShoppingListArticle;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ShoppingListArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $shoppingListArticle = new ShoppingListArticle();
        $shoppingListArticle->setQty(3);
        $shoppingListArticle->setArticle($this->getReference(ArticleFixtures::ARTICLE_BANANE_REFERENCE, Article::class));
        $shoppingListArticle->setShoppingList($this->getReference(ShoppingListFixtures::SHOPPING_LIST_COURSES_REFERENCE, ShoppingList::class));
        $shoppingListArticle->setEditeur($this->getReference(UserFixtures::ADMIN_USER_REFERENCE, User::class));

        $manager->persist($shoppingListArticle);

        $manager->flush();
    }

    public function getDependencies(): array {
        return [
            ArticleFixtures::class,
            ShoppingListFixtures::class,
            UserFixtures::class,
        ];
    }
}
