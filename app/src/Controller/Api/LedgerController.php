<?php

namespace App\Controller\Api;

use App\CQRS\Command\CreateLedgerCommand;
use App\CQRS\Handler\CreateLedgerHandler;
use App\DTO\CreateLedgerRequestDTO;
use App\Enum\CurrencyEnum;
use App\Transformer\LedgerResponseTransformer;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class LedgerController extends BaseApiController
{
    #[OA\Tag(name: "Ledger")]
    #[OA\Post(
        path: "/api/ledgers",
        summary: "Create new ledger",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["initialCurrency"],
                properties: [
                    new OA\Property(
                        property: "initialCurrency",
                        description: "Currency for ledger",
                        type: CurrencyEnum::class,
                        example: "USD"
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Ledger created successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "id", type: "string", example: "b2d3b63c-9339-49ad-b39d-9b5af45134bb"),
                        new OA\Property(property: "initialCurrency", type: CurrencyEnum::class, example: "USD"),
                        new OA\Property(property: "createdAt", type: "string", example: "2025-02-19 15:20:00")
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Wrong Data"
            )
        ]
    )]
    #[Route('/api/ledgers', name: 'app_api_create_ledger', methods: ['POST'])]
    public function create(
        Request $request,
        ValidatorInterface $validator,
        LedgerResponseTransformer $transformer,
        CreateLedgerHandler $createLedgerHandler
    ): JsonResponse {
        $dto = $this->deserializeRequest($request->getContent(), CreateLedgerRequestDTO::class);

        $errors = $validator->validate($dto);
        if (count($errors) > 0) {
            return $this->json($this->formatValidationErrors($errors), JsonResponse::HTTP_BAD_REQUEST);
        }

        $command = new CreateLedgerCommand($dto->initialCurrency);
        $ledger = $createLedgerHandler->handle($command);

        $responseDTO = $transformer->transform($ledger);
        return $this->json(
            $responseDTO,
            JsonResponse::HTTP_CREATED
        );
    }

//    #[Route('/api/ledgers', name: 'app_api_ledger', methods: ['POST'])]
//    public function index(): Response
//    {
//        return $this->render('api/ledger/index.html.twig', [
//            'controller_name' => 'LedgerController',
//        ]);
//    }
}
