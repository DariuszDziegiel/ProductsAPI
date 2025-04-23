<?php

declare(strict_types=1);

namespace Api\Interface\Controller\Product;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ProductAddController extends AbstractController
{
    #[Route(
        '/products',
        methods: ['POST']
    )]
    public function __invoke(Request $request): JsonResponse
    {

        dd($request->getPayload()->all());

        return $this->json(['ok']);
    }
}
