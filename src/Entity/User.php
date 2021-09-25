<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
  /**
   * @ORM\Id()
   * @ORM\GeneratedValue()
   * @ORM\Column(type="integer")
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=180, unique=true)
   */
  private $username;

  /**
   * @ORM\Column(type="json")
   */
  private $roles = [];

  /**
   * @var string The hashed password
   * @ORM\Column(type="string")
   */
  private $password;

  /**
   * @ORM\OneToMany(targetEntity="App\Entity\Blog", mappedBy="author")
   */
  private $blog;

  /**
   * @ORM\OneToMany(targetEntity="App\Entity\Likes", mappedBy="user_id")
   */
  private $likes;

  public function __construct()
  {
      $this->likes = new ArrayCollection();
  }


  public function getId(): ?int
  {
    return $this->id;
  }

  /**
   * A visual identifier that represents this user.
   *
   * @see UserInterface
   */
  public function getUsername(): string
  {
    return (string) $this->username;
  }

  public function setUsername(string $username): self
  {
    $this->username = $username;

    return $this;
  }

  /**
   * @see UserInterface
   */
  public function getRoles(): array
  {
    $roles = $this->roles;
    // guarantee every user at least has ROLE_USER
    $roles[] = 'ROLE_USER';

    return array_unique($roles);
  }

  public function setRoles(array $roles): self
  {
    $this->roles = $roles;

    return $this;
  }

  /**
   * @see UserInterface
   */
  public function getPassword(): string
  {
    return (string) $this->password;
  }

  public function setPassword(string $password): self
  {
    $this->password = $password;

    return $this;
  }

  /**
   * @see UserInterface
   */
  public function getSalt()
  {
    // not needed when using the "bcrypt" algorithm in security.yaml
  }

  /**
   * @see UserInterface
   */
  public function eraseCredentials()
  {
    // If you store any temporary, sensitive data on the user, clear it here
    // $this->plainPassword = null;
  }

  public function getBlog(): ?Blog
  {
      return $this->blog;
  }

  public function setBlog(Blog $blog): self
  {
      $this->blog = $blog;

      // set the owning side of the relation if necessary
      if ($blog->getAuthor() !== $this) {
          $blog->setAuthor($this);
      }

      return $this;
  }

  /**
   * @return Collection|Likes[]
   */
  public function getLikes(): Collection
  {
      return $this->likes;
  }

  public function addLike(Likes $like): self
  {
      if (!$this->likes->contains($like)) {
          $this->likes[] = $like;
          $like->setUserId($this);
      }

      return $this;
  }

  public function removeLike(Likes $like): self
  {
      if ($this->likes->contains($like)) {
          $this->likes->removeElement($like);
          // set the owning side to null (unless already changed)
          if ($like->getUserId() === $this) {
              $like->setUserId(null);
          }
      }

      return $this;
  }
}
