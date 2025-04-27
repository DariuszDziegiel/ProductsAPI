<?php

declare(strict_types=1);

namespace Api\Interface\Http\Controller\Product;

use Api\Application\UseCase\ProductReplace\ProductReplaceCommand;
use Api\Interface\Http\Controller\Product\Trait\ProductPartialUpdateExceptionsHandlingTrait;
use Api\Interface\Http\Controller\Product\Trait\ValidationErrorHandlingTrait;
use Api\Interface\Http\RequestDTO\ProductReplaceRequestDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

class ProductReplaceController extends AbstractController
{
    use ValidationErrorHandlingTrait;
    use ProductPartialUpdateExceptionsHandlingTrait;

    public function __construct(
        private readonly MessageBusInterface $commandBus
    ) {}

    #[Route(
        '/products/{id}',
        requirements: ['id' => '[0-9a-fA-F\-]{36}'],
        methods: ['PUT']
    )]
    public function __invoke(
        string $id,
        #[MapRequestPayload()] ProductReplaceRequestDTO $productReplaceRequestDTO
    ): Response
    {
        try {
            $this->commandBus->dispatch(
                new ProductReplaceCommand(
                    $id,
                    $productReplaceRequestDTO->title,
                    $productReplaceRequestDTO->price,
                    $productReplaceRequestDTO->categories
                )
            );

            return new Response(
                null,
                Response::HTTP_NO_CONTENT
            );
        } catch (ValidationFailedException $e) {
            return $this->handleValidationErrorResponse($e);
        } catch(HandlerFailedException $e) {
            return $this->handleHandlerFailedException($e);
        } catch (\Throwable $e) {
            return $this->json([
                'message' => 'Unexpected error - we will fix it as soon as possible - please try again later'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
