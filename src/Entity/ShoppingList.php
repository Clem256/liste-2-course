<?php

namespace App\Entity;

use App\Repository\ShoppingListRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShoppingListRepository::class)]
class ShoppingList
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'shoppingLists')]
    private Collection $consommateur;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    /**
     * @var Collection<int, ShoppingListArticle>
     */
    #[ORM\OneToMany(targetEntity: ShoppingListArticle::class, mappedBy: 'shopping_list', orphanRemoval: true)]
    private Collection $shoppingListArticles;

    public function __construct()
    {
        $this->consommateur = new ArrayCollection();
        $this->shoppingListArticles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * @return Collection<int, User>
     */
    public function getConsommateur(): Collection
    {
        return $this->consommateur;
    }

    public function addConsommateur(User $consommateur): static
    {
        if (!$this->consommateur->contains($consommateur)) {
            $this->consommateur->add($consommateur);
        }

        return $this;
    }

    public function removeConsommateur(User $consommateur): static
    {
        $this->consommateur->removeElement($consommateur);

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, ShoppingListArticle>
     */
    public function getShoppingListArticles(): Collection
    {
        return $this->shoppingListArticles;
    }

    public function addShoppingListArticle(ShoppingListArticle $shoppingListArticle): static
    {
        if (!$this->shoppingListArticles->contains($shoppingListArticle)) {
            $this->shoppingListArticles->add($shoppingListArticle);
            $shoppingListArticle->setShoppingList($this);
        }

        return $this;
    }

    public function removeShoppingListArticle(ShoppingListArticle $shoppingListArticle): static
    {
        if ($this->shoppingListArticles->removeElement($shoppingListArticle)) {
            // set the owning side to null (unless already changed)
            if ($shoppingListArticle->getShoppingList() === $this) {
                $shoppingListArticle->setShoppingList(null);
            }
        }

        return $this;
    }
}
