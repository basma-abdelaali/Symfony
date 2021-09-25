<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100000)
     */
    private $comment;

    /**
     * @ORM\Column(type="date")
     */
    private $date_comment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $user_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Blog", inversedBy="comments")
     */
    private $blog_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getDateComment(): ?\DateTimeInterface
    {
        return $this->date_comment;
    }

    public function setDateComment(\DateTimeInterface $date_comment): self
    {
        $this->date_comment = $date_comment;

        return $this;
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

    public function getBlogId(): ?Blog
    {
        return $this->blog_id;
    }

    public function setBlogId(?Blog $blog_id): self
    {
        $this->blog_id = $blog_id;

        return $this;
    }
}
