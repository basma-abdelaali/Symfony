<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LikesRepository")
 */
class Likes
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="likes")
     */
    private $user_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\blog", inversedBy="likes2")
     */
    private $blog_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getBlogId(): ?blog
    {
        return $this->blog_id;
    }

    public function setBlogId(?blog $blog_id): self
    {
        $this->blog_id = $blog_id;

        return $this;
    }
}
