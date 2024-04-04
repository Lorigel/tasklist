<?php

namespace App\Entity;

use App\Repository\JobRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JobRepository::class)]
class Job
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $scheduled_date = null;

    #[ORM\ManyToOne]
    private ?Location $location = null;

    #[ORM\ManyToOne]
    private ?Auditor $auditor = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $estimated_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $completed_date = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $assessment = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

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

    public function getScheduledDate(): ?\DateTimeInterface
    {
        return $this->scheduled_date;
    }

    public function setScheduledDate(\DateTimeInterface $scheduled_date): static
    {
        $this->scheduled_date = $scheduled_date;

        return $this;
    }

    public function getLocationId(): ?Location
    {
        return $this->location;
    }

    public function setLocationId(?Location $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getAuditorId(): ?Auditor
    {
        return $this->auditor;
    }

    public function setAuditorId(?Auditor $auditor): static
    {
        $this->auditor = $auditor;

        return $this;
    }

    public function getEstimatedDate(): ?\DateTimeInterface
    {
        return $this->estimated_date;
    }

    public function setEstimatedDate(?\DateTimeInterface $estimated_date): static
    {
        $this->estimated_date = $estimated_date;

        return $this;
    }

    public function getCompletedDate(): ?\DateTimeInterface
    {
        return $this->completed_date;
    }

    public function setCompletedDate(?\DateTimeInterface $completed_date): static
    {
        $this->completed_date = $completed_date;

        return $this;
    }

    public function getAssessment(): ?string
    {
        return $this->assessment;
    }

    public function setAssessment(?string $assessment): static
    {
        $this->assessment = $assessment;

        return $this;
    }
}
