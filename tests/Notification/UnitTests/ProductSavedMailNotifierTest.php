<?php

declare(strict_types=1);

namespace App\Tests\Notification\UnitTests;

use Api\Domain\Entity\Product;
use Api\Domain\Event\ProductSavedEvent;
use Api\Domain\ValueObject\Money;
use App\Tests\Notification\Infrastructure\Mailer\InMemoryMailer;
use Notification\Infrastructure\ProductSavedNotifier\ProductSavedMailNotifier;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mime\Email;
use Symfony\Component\Uid\Uuid;


class ProductSavedMailNotifierTest extends TestCase
{
    #[TestDox('Email is send on product saved event')]
    public function test_email_is_send_on_product_saved_event(): void
    {
        $mailer = new InMemoryMailer();
        $productSavedMailNotifier = new ProductSavedMailNotifier($mailer);

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

        $productSavedMailNotifier->notify(
            new ProductSavedEvent(
                $product
            )
        );

        $this->assertCount(1, $mailer->getSentMessages());
        $email = $mailer->getSentMessages()[0];
        $this->assertInstanceOf(Email::class, $email);
        $this->assertStringContainsString('Test product', $email->getHtmlBody());
        $this->assertStringContainsString('price: 100', $email->getHtmlBody());
        $this->assertStringContainsString('categories: [CAT1, CAT2]', $email->getHtmlBody());
    }
}
