<?php

namespace App\Entity;

use App\model\SucreSale;
use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column(length: 255, enumType: SucreSale::class)]
    private SucreSale $type ;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(length: 1, nullable: true)]
    private ?string $nutriscore = null;

    #[ORM\Column(nullable: true)]
    private ?int $calorie = null;

    #[ORM\ManyToOne(inversedBy: 'Articles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $categorie = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $consommateur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $label = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $proprietaire = null;

    /**
     * @var Collection<int, ShoppingListArticle>
     */
    #[ORM\OneToMany(targetEntity: ShoppingListArticle::class, mappedBy: 'article', orphanRemoval: true)]
    private Collection $shoppingListArticles;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $unit = null;

    public function __construct()
    {
        $this->shoppingListArticles = new ArrayCollection();
        $this->type = SucreSale::autre;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getType(): SucreSale
    {
        return $this->type;
    }

    public function setType(SucreSale $type): void
    {
        $this->type = $type;
    }


    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getNutriscore(): ?string
    {
        return $this->nutriscore;
    }

    public function setNutriscore(?string $nutriscore): static
    {
        $this->nutriscore = $nutriscore;

        return $this;
    }

    public function getCalorie(): ?int
    {
        return $this->calorie;
    }

    public function setCalorie(?int $calorie): static
    {
        $this->calorie = $calorie;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getConsommateur(): ?User
    {
        return $this->consommateur;
    }

    public function setConsommateur(?User $consommateur): static
    {
        $this->consommateur = $consommateur;

        return $this;
    }

    public function getShoppingList(): ?ShoppingList
    {
        return $this->shoppingList;
    }
//    TODO : l'article ne doit pas contenir de shopping lists a sa creation
    public function setShoppingList(?ShoppingList $shoppingList): static
    {
        $this->shoppingList = $shoppingList;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): static
    {
        $this->label = $label;

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
            $shoppingListArticle->setArticle($this);
        }

        return $this;
    }

    public function removeShoppingListArticle(ShoppingListArticle $shoppingListArticle): static
    {
        if ($this->shoppingListArticles->removeElement($shoppingListArticle)) {
            // set the owning side to null (unless already changed)
            if ($shoppingListArticle->getArticle() === $this) {
                $shoppingListArticle->setArticle(null);
            }
        }

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(?string $unit): static
    {
        $this->unit = $unit;

        return $this;
    }

    public function getProprietaire(): ?User
    {
        return $this->proprietaire;
    }

    public function setProprietaire(?User $proprietaire): static
    {
        $this->proprietaire = $proprietaire;
        return $this;
    }
}
