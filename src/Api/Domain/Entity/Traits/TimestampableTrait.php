<?php

declare(strict_types=1);

namespace Api\Domain\Entity\Traits;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;

trait TimestampableTrait
{
    #[Column(type: 'datetime_immutable', nullable: false)]
    private \DateTimeImmutable $createdAt;

    #[Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    #[PrePersist]
    public function setCreatedAt()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    #[PreUpdate]
    public function setUpdatedAt()
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

}
