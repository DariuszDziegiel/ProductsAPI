<?php

declare(strict_types=1);

namespace Api\Interface\Http\Controller\Product;

use Api\Application\UseCase\ProductDelete\ProductDeleteCommand;
use Api\Interface\Http\Controller\Product\Trait\ProductDeleteExceptionsHandlingTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

class ProductDeleteController extends AbstractController
{
    use ProductDeleteExceptionsHandlingTrait;

    public function __construct(
        private readonly MessageBusInterface $commandBus,
    ) {}

    #[Route(
        '/products/{id}',
        requirements: ['id' => '[0-9a-fA-F\-]{36}'],
        methods: ['DELETE'],
    )]
    public function __invoke(string $id): Response
    {
        try {
            $this->commandBus->dispatch(
                new ProductDeleteCommand($id)
            );

            return new Response(
                null,
                Response::HTTP_NO_CONTENT
            );
        } catch (HandlerFailedException $e) {
            return $this->handleHandlerFailedException($e);
        } catch (\Throwable $e) {
            return $this->json([
                'message' => 'Unexpected error - we will fix it as soon as possible - please try again later'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
