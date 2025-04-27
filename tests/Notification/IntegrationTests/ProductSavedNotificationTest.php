<?php

declare(strict_types=1);

namespace App\Tests\Notification\IntegrationTests;

use Api\Domain\Entity\Product;
use Api\Domain\Event\ProductSavedEvent;
use Api\Domain\ValueObject\Money;
use PHPUnit\Framework\Attributes\TestDox;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\MailerAssertionsTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Uuid;

class ProductSavedNotificationTest extends KernelTestCase
{
    use MailerAssertionsTrait;

    private static $product;
    private static $productId;

    public static function setUpBeforeClass(): void
    {
        $product = self::createStub(Product::class);
        $productId = Uuid::v7()->toString();
        $product
            ->method('id')
            ->willReturn($productId);
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

        self::$product = $product;
        self::$productId = $productId;
    }

    #[TestDox('Email notification is sent when product saved')]
    public function test_product_saved_email_notification_is_sent(): void
    {
        self::bootKernel();

        /** @var MessageBusInterface $eventBus */
        $eventBus = self::getContainer()->get('event.bus');

        $eventBus->dispatch(
            new ProductSavedEvent(self::$product)
        );

        $this->assertQueuedEmailCount(1);
    }

    #[TestDox('File notification is logged to file when product saved')]
    public function test_product_saved_info_is_logged_to_file(): void
    {
        self::bootKernel();

        $logsDir = self::getContainer()->getParameter('kernel.logs_dir');
        $logFilePath = "{$logsDir}/test.product.saved.event.log";

        /** @var MessageBusInterface $eventBus */
        $eventBus = self::getContainer()->get('event.bus');
        $eventBus->dispatch(
            new ProductSavedEvent(self::$product)
        );

        $logFileContent = file_get_contents($logFilePath);

        $this->assertNotFalse($logFileContent, 'No content in test.product.saved.event.log file');
        $this->assertStringContainsString(self::$productId, $logFileContent);

        unlink($logFilePath);
    }

}
