<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;


    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $username = null;

    /**
     * @var Collection<int, ShoppingList>
     */
    #[ORM\ManyToMany(targetEntity: ShoppingList::class, mappedBy: 'consommateur')]
    private Collection $shoppingLists;

    #[ORM\Column]
    private bool $isVerified = false;

    /**
     * @var Collection<int, ShoppingListArticle>
     */
    #[ORM\OneToMany(targetEntity: ShoppingListArticle::class, mappedBy: 'editeur')]
    private Collection $shoppingListArticles;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\OneToMany(targetEntity: Article::class, mappedBy: 'proprietaire')]
    private Collection $articles;

    public function __construct()
    {
        $this->shoppingLists = new ArrayCollection();
        $this->shoppingListArticles = new ArrayCollection();
        $this->articles = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }


    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }


    /**
     * @return Collection<int, ShoppingList>
     */
    public function getShoppingLists(): Collection
    {
        return $this->shoppingLists;
    }

    public function addShoppingList(ShoppingList $shoppingList): static
    {
        if (!$this->shoppingLists->contains($shoppingList)) {
            $this->shoppingLists->add($shoppingList);
            $shoppingList->addConsommateur($this);
        }

        return $this;
    }

    public function removeShoppingList(ShoppingList $shoppingList): static
    {
        if ($this->shoppingLists->removeElement($shoppingList)) {
            $shoppingList->removeConsommateur($this);
        }

        return $this;
    }


    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

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
            $shoppingListArticle->setEditeur($this);
        }

        return $this;
    }

    public function removeShoppingListArticle(ShoppingListArticle $shoppingListArticle): static
    {
        if ($this->shoppingListArticles->removeElement($shoppingListArticle)) {
            // set the owning side to null (unless already changed)
            if ($shoppingListArticle->getEditeur() === $this) {
                $shoppingListArticle->setEditeur(null);
            }
        }

        return $this;
    }
    public function getArticles(): Collection
    {
        return $this->articles;
    }
}
