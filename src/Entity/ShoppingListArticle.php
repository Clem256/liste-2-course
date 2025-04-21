<?php

namespace App\Entity;

use App\Repository\ShoppingListArticleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShoppingListArticleRepository::class)]
class ShoppingListArticle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $qty = null;

    #[ORM\ManyToOne(inversedBy: 'shoppingListArticles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Article $article = null;

    #[ORM\ManyToOne(inversedBy: 'shoppingListArticles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ShoppingList $shopping_list = null;

    #[ORM\ManyToOne(inversedBy: 'shoppingListArticles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $editeur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQty(): ?int
    {
        return $this->qty;
    }

    public function setQty(int $qty): static
    {
        $this->qty = $qty;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): static
    {
        $this->article = $article;

        return $this;
    }

    public function getShoppingList(): ?ShoppingList
    {
        return $this->shopping_list;
    }

    public function setShoppingList(?ShoppingList $shopping_list): static
    {
        $this->shopping_list = $shopping_list;

        return $this;
    }


    public function getEditeur(): ?User
    {
        return $this->editeur;
    }

    public function setEditeur(?User $editeur): static
    {
        $this->editeur = $editeur;

        return $this;
    }
}
