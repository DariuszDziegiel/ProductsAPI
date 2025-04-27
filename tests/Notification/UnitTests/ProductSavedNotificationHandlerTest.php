<?php

declare(strict_types=1);

namespace App\Tests\Notification\UnitTests;

use Api\Domain\Event\ProductSavedEvent;
use Notification\Application\ProductSavedNotificationHandler;
use Notification\Domain\ProductSavedNotifierInterface;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;


class ProductSavedNotificationHandlerTest extends TestCase
{

    #[TestDox('All product saved notifiers are called')]
    public function test_all_product_saved_notifiers_are_called(): void
    {
        $productSavedEvent = $this->createMock(ProductSavedEvent::class);

        $productSavedMailNotifier    = $this->createMock(ProductSavedNotifierInterface::class);
        $productSavedFileLogNotifier = $this->createMock(ProductSavedNotifierInterface::class);

        $productSavedMailNotifier->expects($this->once())
            ->method('notify')
            ->with($productSavedEvent);

        $productSavedFileLogNotifier->expects($this->once())
            ->method('notify')
            ->with($productSavedEvent);

        $productSavedNotificationHandler = new ProductSavedNotificationHandler([
            $productSavedMailNotifier,
            $productSavedFileLogNotifier
        ]);

        $productSavedNotificationHandler($productSavedEvent);
    }

}
