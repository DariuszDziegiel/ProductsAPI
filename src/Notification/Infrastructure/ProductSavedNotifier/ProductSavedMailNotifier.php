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

        $email = new Email()
            ->to('noreply@productsapi.local')
            ->subject('Product Saved')
            ->html("
                Product was saved <br />
                id : {$product->id()} <br />
                title: {$product->title()} <br />
                price: {$product->price()->value()} <br />
            ");

        $this->mailer->send($email);
    }
}
