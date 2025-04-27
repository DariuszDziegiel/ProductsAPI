<?php

declare(strict_types=1);

namespace Api\Infrastructure\Persistence\Doctrine;

use Api\Domain\Entity\Category;
use Api\Domain\Entity\Product;
use Api\Domain\Repository\ProductRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

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

    public function findAll(int $limit, int $page): array
    {
        $page = max($page, 1);
        $limit = max($limit, 1);
        $offset = $limit * ($page - 1);

        $query = $this->entityManager
            ->createQueryBuilder()
            ->select('p, c')
            ->from(Product::class, 'p')
            ->leftJoin('p.categories', 'c')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery();

        $paginator = new Paginator($query, true);

        $results = [];
        foreach ($paginator as $product) {
            $results[] = $product;
        }

        return $results;
    }
}
