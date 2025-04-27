<?php

declare(strict_types=1);

namespace App\Tests\Notification\Infrastructure\Mailer;

use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\RawMessage;

class InMemoryMailer implements MailerInterface
{
    private array $sentMessages = [];

    public function send(RawMessage $message, ?Envelope $envelope = null): void
    {
        $this->sentMessages[] = $message;
    }

    public function getSentMessages(): array
    {
        return $this->sentMessages;
    }

}
