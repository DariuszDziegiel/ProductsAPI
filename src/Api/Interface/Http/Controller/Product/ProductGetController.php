<?php

declare(strict_types=1);

namespace Api\Interface\Http\Controller\Product;

use Api\Application\Exception\Product\ProductWithGivenIdNotExistsException;
use Api\Application\UseCase\ProductGet\ProductGetQuery;
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
    public function __construct(
        #[Target('query.bus')]
        private readonly MessageBusInterface $queryBus,
    ) {}

    #[Route(
        '/products/{id}',
        methods: ['GET', 'HEAD']
    )]
    public function __invoke(string $id): JsonResponse
    {
        try {
            $envelope = $this->queryBus->dispatch(
                new ProductGetQuery($id)
            );
            $handled = $envelope->last(HandledStamp::class);
            $product = $handled->getResult();

            return $this->json($product);
        } catch (ValidationFailedException $e) {
                return $this->buildValidationErrorResponse($e);
        } catch (HandlerFailedException $e) {
            foreach ($e->getWrappedExceptions() as $wrappedException) {
                if ($wrappedException instanceof ProductWithGivenIdNotExistsException) {
                    return $this->json([
                        'message' => 'Product not exists'
                    ], Response::HTTP_NOT_FOUND);
                }
            }
        } catch (\Throwable $e) {
            return $this->json([
                'message' => 'Unexpected error:' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function buildValidationErrorResponse(ValidationFailedException $e): JsonResponse
    {
        $violations = iterator_to_array($e->getViolations());
        $messages = array_map(fn($v) => $v->getMessage(), $violations);

        return $this->json([
            'message' => 'Validation failed: ' . implode(', ', $messages)
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
