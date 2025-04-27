<?php

declare(strict_types=1);

namespace App\Tests\Notification\UnitTests;

use Api\Domain\Entity\Product;
use Api\Domain\Event\ProductSavedEvent;
use Api\Domain\ValueObject\Money;
use App\Tests\Notification\Infrastructure\Logger\InMemoryLogger;
use Notification\Infrastructure\ProductSavedNotifier\ProductSavedFileLogNotifier;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;


class ProductSavedFileLogNotifierTest extends TestCase
{
    #[TestDox('Log saved to file on product saved event')]
    public function test_file_log_on_product_saved_event(): void
    {
        $logger = new InMemoryLogger();
        $productSavedFileLogNotifier = new ProductSavedFileLogNotifier($logger);

        $product = $this->createStub(Product::class);
        $product
            ->method('id')
            ->willReturn(Uuid::v7()->toString());
        $product
            ->method('title')
            ->willReturn('Test product');
        $product
            ->method('price')
            ->willReturn(Money::createFromPrice('100'));
        $product
            ->method('createdAt')
            ->willReturn(new \DateTimeImmutable());
        $product
            ->method('updatedAt')
            ->willReturn(new \DateTimeImmutable());
        $product
            ->method('categoriesCodes')
            ->willReturn(['CAT1' , 'CAT2']);

        $productSavedFileLogNotifier->notify(
            new ProductSavedEvent($product)
        );

        $this->assertCount(1, $logger->collectedLogs());
        $log = $logger->collectedLogs()[0];
        $this->assertIsArray($log);

        $this->assertStringContainsString('Product saved', $log['message']);
        $this->assertArrayHasKey('context', $log);
        $this->assertEquals('100', $log['context']['price']);
        $this->assertEquals(['CAT1', 'CAT2'], $log['context']['categories']);
    }
}
