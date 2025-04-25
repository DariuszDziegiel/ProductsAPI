<?php

declare(strict_types=1);

namespace Api\Infrastructure\Persistence\Doctrine;

use Api\Domain\Entity\Product;
use Api\Domain\Repository\ProductRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

readonly class DoctrineProductRepository implements ProductRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function save(Product $product): void
    {
        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }

    public function findById(string $id): ?Product
    {
        return $this->entityManager->find(Product::class, $id);
    }

    public function remove(Product $product): void
    {
        $this->entityManager->remove($product);
        $this->entityManager->flush();
    }
}
