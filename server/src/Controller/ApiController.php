<?php

namespace App\Controller;

use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiController extends AbstractController
{
    /////////////////////////
    // Response status codes
    /////////////////////////
    public const STATUS_SUCCESS = 1;
    public const STATUS_FAILURE = 0;

    ////////////////////////
    // Response error codes
    ////////////////////////

    // Error recognized as a client issue (unauthorized, bad request, etc)
    public const ERROR_CLIENT = 'CLIENT_ISSUE';
    // Error recognized as a server issue
    public const ERROR_SERVER = 'SERVER_ISSUE';

    /** @var SerializerInterface */
    protected SerializerInterface $serializer;

    /** @var ValidatorInterface */
    protected ValidatorInterface $validator;

    /** @var LoggerInterface */
    protected LoggerInterface $logger;

    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        LoggerInterface $logger)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->logger = $logger;
    }

    /**
     * Generate a json error response for client-errors (bad request, unauthorized, etc).
     * @param string $errorMessage the error message
     * @param int $statusCode the HTTP status code to send with the response
     * @param string $errorCode
     * @return JsonResponse
     */
    protected function clientErrorResponse(string $errorMessage, int $statusCode, string $errorCode = self::ERROR_CLIENT): JsonResponse
    {
        return new JsonResponse([
            'status' => self::STATUS_FAILURE,
            'msg' => $errorMessage,
            'errorCode' => $errorCode
        ], $statusCode);
    }

    protected function clientValidationErrorResponse(ConstraintViolationListInterface $violations): JsonResponse
    {
        $errors = [];
        /** @var ConstraintViolation $violation */
        foreach ($violations as $violation) {
            $property = preg_replace('/[\[\]]/', '', $violation->getPropertyPath());
            $errors[$property] = $violation->getMessage();
        }

        return new JsonResponse([
            'status' => self::STATUS_FAILURE,
            'errors' => $errors,
        ], Response::HTTP_BAD_REQUEST);
    }

    /**
     * Log an internal server error for an exception with a unique string for debugging. Send the same unique string in a json error response.
     * @param string $methodName the name of the method, for debug log
     * @param Exception $e the exception to log
     * @return JsonResponse
     */
    protected function internalServerErrorResponse(string $methodName, Exception $e): JsonResponse
    {
        // Log error and return response with a unique error id for debugging
        $errorId = uniqid("", true);
        $this->logger->error("{$methodName}: errorId {$errorId}, message {$e->getMessage()}, stack {$e->getTraceAsString()}");
        return new JsonResponse([
            'status' => self::STATUS_FAILURE,
            'msg' => "Server error: {$errorId}.",
            'errorCode' => self::ERROR_SERVER,
            'errorId' => $errorId,
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
