<?php

namespace App\Entity;

use App\Repository\UriRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UriRepository::class)
 */
class Uri
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=1024)
     */
    private $uri;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $hash;

    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $maxRedirects = 0;

    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $numRedirects = 0;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $expiredAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUri(): ?string
    {
        return $this->uri;
    }

    public function setUri(string $uri): self
    {
        $this->uri = $uri;
        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;
        return $this;
    }

    public function getMaxRedirects(): ?int
    {
        return $this->maxRedirects;
    }

    public function setMaxRedirects(int $maxRedirects): self
    {
        $this->maxRedirects = $maxRedirects;
        return $this;
    }

    public function getNumRedirects(): ?int
    {
        return $this->numRedirects;
    }

    public function setNumRedirects(int $numRedirects): self
    {
        $this->numRedirects = $numRedirects;
        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getExpiredAt(): ?\DateTime
    {
        return $this->expiredAt;
    }

    public function setExpiredAt(\DateTime $expiredAt): self
    {
        $this->expiredAt = $expiredAt;
        return $this;
    }
}
