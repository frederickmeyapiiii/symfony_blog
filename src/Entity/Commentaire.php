<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    private ?Article $article = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateTime = null;

    public function __construct()
    {
        $this->dateTime = new \DateTimeImmutable(); // Définit la date de création à maintenant
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

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

    public function getDateTime(): ?\DateTimeImmutable
    {
        return $this->dateTime;
    }

    public function setDateTime(\DateTimeImmutable $dateTime): static
    {
        $this->dateTime = $dateTime;

        return $this;
    }
}
