<?php

namespace App\Controller\Api;

use App\Controller\Api\BaseApiController;
use App\CQRS\Command\CreateTransactionCommand;
use App\CQRS\Handler\CreateTransactionHandler;
use App\DTO\CreateTransactionRequestDTO;
use App\Transformer\TransactionResponseTransformer;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TransactionController extends BaseApiController
{
    #[OA\Tag(name: "Transaction")]
    #[OA\Post(
        path: "/api/transactions",
        summary: "Record a new transaction in the specified ledger",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["ledgerId", "type", "amount", "currency", "transactionId"],
                properties: [
                    new OA\Property(
                        property: "ledgerId",
                        description: "The unique identifier of the ledger",
                        type: "string",
                        example: "22737fd0-461b-441d-bdd7-09ce3ec21e0a"
                        // example: "a1b2c3d4-e5f6-7g8h-9i0j-k1l2m3n4o5p6"
                    ),
                    new OA\Property(
                        property: "type",
                        description: "Transaction type: 'debit' or 'credit'",
                        type: "string",
                        example: "debit"
                    ),
                    new OA\Property(
                        property: "amount",
                        description: "The transaction amount",
                        type: "number",
                        format: "float",
                        example: 100.50
                    ),
                    new OA\Property(
                        property: "currency",
                        description: "Currency of the transaction",
                        type: "string",
                        example: "USD"
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: JsonResponse::HTTP_CREATED,
                description: "Transaction recorded successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "id", type: "string", example: "d4e5f6g7-h8i9-j0k1-l2m3-n4o5p6q7r8s9"),
                        new OA\Property(property: "ledgerId", type: "string", example: "a1b2c3d4-e5f6-7g8h-9i0j-k1l2m3n4o5p6"),
                        new OA\Property(property: "type", type: "string", example: "debit"),
                        new OA\Property(property: "amount", type: "number", format: "float", example: 100.50),
                        new OA\Property(property: "currency", type: "string", example: "USD"),
                        new OA\Property(property: "createdAt", type: "string", example: "2025-02-19 15:30:00")
                    ]
                )
            ),
            new OA\Response(
                response: JsonResponse::HTTP_BAD_REQUEST,
                description: "Invalid input data"
            )
        ]
    )]
    #[Route('/api/transactions', name: 'app_api_create_transaction', methods: ['POST'])]
    public function create(
        Request $request,
        ValidatorInterface $validator,
        TransactionResponseTransformer $transformer,
        CreateTransactionHandler $createTransactionHandler
    ): JsonResponse {
        $dto = $this->deserializeRequest($request->getContent(), CreateTransactionRequestDTO::class);

        $errors = $validator->validate($dto);
        if (count($errors) > 0) {
            return $this->json($this->formatValidationErrors($errors), JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $command = new CreateTransactionCommand(
                $dto->ledgerId,
                $dto->type,
                $dto->amount,
                $dto->currency
            );
            $transaction = $createTransactionHandler->handle($command);
        } catch (\Exception $e) {
            return $this->json(
                ['error' => $e->getMessage()],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        $responseDTO = $transformer->transform($transaction);

        return $this->json($responseDTO, JsonResponse::HTTP_CREATED);
    }
}