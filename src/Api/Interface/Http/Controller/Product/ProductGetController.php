<?php

declare(strict_types=1);

namespace Api\Interface\Http\Controller\Product;

use Api\Application\UseCase\ProductGet\DTO\ProductGetDTO;
use Api\Application\UseCase\ProductGet\ProductGetQuery;
use Api\Interface\Http\Controller\Product\Trait\ProductGetExceptionsHandlingTrait;
use Api\Interface\Http\Controller\Product\Trait\ValidationErrorHandlingTrait;
use Nelmio\ApiDocBundle\Model\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Attribute\Route;

class ProductGetController extends AbstractController
{
    use ValidationErrorHandlingTrait;
    use ProductGetExceptionsHandlingTrait;

    public function __construct(
        #[Target('query.bus')]
        private readonly MessageBusInterface $queryBus,
    ) {}

    #[Route(
        '/products/{id}',
        requirements: ['id' => '[0-9a-fA-F\-]{36}'],
        methods: ['GET', 'HEAD'],
    )]
    public function __invoke(string $id): JsonResponse
    {
        try {
            $envelope = $this->queryBus->dispatch(
                new ProductGetQuery($id)
            );
            $handled = $envelope->last(HandledStamp::class);
            /** @var ProductGetDTO $product */
            $product = $handled->getResult();

            return $this->json($product);
        } catch (ValidationFailedException $e) {
            return $this->handleValidationErrorResponse($e);
        } catch (HandlerFailedException $e) {
            return $this->handleHandlerFailedException($e);
        } catch (\Throwable $e) {
            return $this->json([
                'message' => 'Unexpected error - we will fix it as soon as possible - please try again later'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
