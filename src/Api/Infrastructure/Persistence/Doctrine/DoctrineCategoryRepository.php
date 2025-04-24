<?php

declare(strict_types=1);

namespace Api\Infrastructure\Persistence\Doctrine;

use Api\Domain\Entity\Category;
use Api\Domain\Repository\CategoryRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

readonly class DoctrineCategoryRepository implements CategoryRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function save(Category $category): void
    {
        $this->entityManager->persist($category);
        $this->entityManager->flush();
    }

    public function findOneByCode(string $code): ?Category
    {
        return $this->entityManager
            ->getRepository(Category::class)
            ->findOneBy(['code' => $code]);
    }
}
