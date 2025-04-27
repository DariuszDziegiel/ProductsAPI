<?php

declare(strict_types=1);

namespace Notification\Infrastructure\ProductSavedNotifier;

use Api\Domain\Event\ProductSavedEvent;
use Notification\Domain\ProductSavedNotifierInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


#[Autoconfigure(tags: ['product.saved.event.notifier'])]
readonly class ProductSavedMailNotifier implements ProductSavedNotifierInterface
{
    public function __construct(
        private MailerInterface $mailer
    ) {}

    public function notify(ProductSavedEvent $productSavedEvent): void
    {
        $product = $productSavedEvent->product;

        $productDateUpdate = $product->updatedAt()
            ? $product->updatedAt()->format('Y-m-d H:i:s')
            : '---'
        ;
        $productCategoriesCodes = implode(', ', $product->categoriesCodes());

        $email = new Email()
            ->to('noreply@productsapi.local')
            ->subject('Product Saved')
            ->html("
                Product was saved <br />
                id : {$product->id()} <br />
                title: {$product->title()} <br />
                price: {$product->price()->value()} <br />
                categories: [{$productCategoriesCodes}] <br />
                date creation: {$product->createdAt()->format('Y-m-d H:i:s')} <br />
                date update: {$productDateUpdate} <br />
            ");

        $this->mailer->send($email);
    }
}
