<?php
namespace App\Controller;

use App\Dto\Request\PostRequest;
use App\Exception\PostNotFoundException;
use App\Services\PostManagement\PostManagementService;
use Doctrine\ODM\MongoDB\Mapping\MappingException;
use Doctrine\ODM\MongoDB\MongoDBException;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PostController extends ApiController
{

    private SerializerInterface $serializer;

    private ValidatorInterface $validator;

    private PostManagementService $postManagementService;

    public function __construct(
        SerializerInterface   $serializer,
        ValidatorInterface    $validator,
        PostManagementService $postManagementService,
    )
    {
        $this->postManagementService = $postManagementService;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    #[Route('/api/posts', name: 'post_list', methods: ['GET'])]
    public function getPostsAction(Request $request)
    {
        try {
            $data = $this->postManagementService->getPosts();
            return new JsonResponse($this->serializer->serialize([
                'status' => self::STATUS_SUCCESS,
                'data' => $data,
            ], 'json'), Response::HTTP_OK, [], true);
        } catch (Exception $e) {
            var_dump($e);
            return $this->internalServerErrorResponse(__METHOD__, $e);
        }
    }

    #[Route('/api/posts', name: 'post_create', methods: ['POST'])]
    public function createPostAction(Request $request): Response
    {
        try {
            $requestDto = $this->serializer->deserialize($request->getContent(), PostRequest::class, 'json');
            $errors = $this->validator->validate($requestDto, null, ['OpCreate']);

            if (count($errors) > 0) {
                return $this->clientValidationErrorResponse($errors);
            }

            $data = $this->postManagementService->createPost($requestDto);
            return new JsonResponse($this->serializer->serialize([
                'status' => self::STATUS_SUCCESS,
                'data' => $data,
            ], 'json'), Response::HTTP_CREATED, [], true);
        } catch (Exception $e) {
            return $this->internalServerErrorResponse(__METHOD__, $e);
        }
    }

    #[Route('/api/posts/{id}', name: 'post_update', methods: ['POST'])]
    public function updatePostAction(Request $request, string $id): Response
    {
        try {
            /** @var PostRequest $requestDto */
            $requestDto = $this->serializer->deserialize($request->getContent(), PostRequest::class, 'json');
            $requestDto->setId($id);
            $errors = $this->validator->validate($requestDto, null, ['OpUpdate']);

            if (count($errors) > 0) {
                return $this->clientValidationErrorResponse($errors);
            }

            $data = $this->postManagementService->updatePost($requestDto);
            return new JsonResponse($this->serializer->serialize([
                'status' => self::STATUS_SUCCESS,
                'data' => $data,
            ], 'json'), Response::HTTP_CREATED, [], true);
        } catch (PostNotFoundException $e) {
            return $this->clientErrorResponse("Post Not Found", Response::HTTP_NOT_FOUND, 'errors.post_not_found');
        } catch (MappingException | MongoDBException | Exception $e) {
            return $this->internalServerErrorResponse(__METHOD__, $e);
        }
    }
}
