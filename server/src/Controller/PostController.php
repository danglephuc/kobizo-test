<?php
namespace App\Controller;

use App\Document\Meta;
use App\Document\Post;
use App\Dto\Request\MetaRequest;
use App\Dto\Request\PostRequest;
use App\Services\PostService;
use Doctrine\ODM\MongoDB\MongoDBException;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class PostController extends ApiController
{
    /** @var PostService  */
    private PostService $postService;

    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        LoggerInterface $logger,
        PostService $postService,
    )
    {
        parent::__construct($serializer, $validator, $logger);
        $this->postService = $postService;
    }

    #[Route('/api/posts', name: 'post_list', methods: ['GET'])]
    public function listPostsAction(Request $request): Response
    {
        try {
            $page = (int)$request->query->get('page', 1);
            $limit = (int)$request->query->get('limit', 10);

            $data = $this->postService->getPosts($page, $limit);
            return new JsonResponse($this->serializer->serialize([
                'status' => self::STATUS_SUCCESS,
                'data' => $data,
            ], 'json', ['groups' => ['default', 'post_meta']]), Response::HTTP_OK, [], true);
        } catch (Exception $e) {
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

            $data = $this->postService->createPost($requestDto);
            return new JsonResponse($this->serializer->serialize([
                'status' => self::STATUS_SUCCESS,
                'data' => $data,
            ], 'json', ['groups' => ['default', 'post_meta']]), Response::HTTP_CREATED, [], true);
        } catch (Exception $e) {
            return $this->internalServerErrorResponse(__METHOD__, $e);
        }
    }

    /**
     * @param Post|null $post
     * @return Response
     */
    #[Route('/api/posts/{id}', name: 'post_get', methods: ['GET'])]
    public function getPostAction(Post $post = null): Response
    {
        try {
            if (!$post) {
                return $this->clientErrorResponse("Post Not Found", Response::HTTP_NOT_FOUND, 'errors.post_not_found');
            }

            return new JsonResponse($this->serializer->serialize([
                'status' => self::STATUS_SUCCESS,
                'data' => $post,
            ], 'json', ['groups' => ['default', 'post_meta']]), Response::HTTP_CREATED, [], true);
        } catch (Exception $e) {
            return $this->internalServerErrorResponse(__METHOD__, $e);
        }
    }

    #[Route('/api/posts/{id}', name: 'post_update', methods: ['PUT'])]
    public function updatePostAction(Request $request, Post $post = null): Response
    {
        try {
            if (!$post) {
                return $this->clientErrorResponse("Post Not Found", Response::HTTP_NOT_FOUND, 'errors.post_not_found');
            }

            /** @var PostRequest $requestDto */
            $requestDto = $this->serializer->deserialize($request->getContent(), PostRequest::class, 'json');
            $errors = $this->validator->validate($requestDto, null, ['OpCreate']);

            if (count($errors) > 0) {
                return $this->clientValidationErrorResponse($errors);
            }

            $data = $this->postService->updatePost($post, $requestDto);
            return new JsonResponse($this->serializer->serialize([
                'status' => self::STATUS_SUCCESS,
                'data' => $data,
            ], 'json', ['groups' => ['default', 'post_meta']]), Response::HTTP_OK, [], true);
        } catch (MongoDBException | Exception $e) {
            return $this->internalServerErrorResponse(__METHOD__, $e);
        }
    }

    #[Route('/api/posts/{postId}/meta', name: 'post_meta_create', methods: ['POST'])]
    public function createPostMetaAction(Request $request, Post $post): Response
    {
        try {
            if (!$post) {
                return $this->clientErrorResponse("Post Not Found", Response::HTTP_NOT_FOUND, 'errors.post_not_found');
            }

            $requestDto = $this->serializer->deserialize($request->getContent(), MetaRequest::class, 'json');
            $errors = $this->validator->validate($requestDto, null, ['OpCreate']);

            if (count($errors) > 0) {
                return $this->clientValidationErrorResponse($errors);
            }

            $data = $this->postService->createPostMeta($post, $requestDto);
            return new JsonResponse($this->serializer->serialize([
                'status' => self::STATUS_SUCCESS,
                'data' => $data,
            ], 'json', ['groups' => ['default', 'meta_post']]), Response::HTTP_CREATED, [], true);
        } catch (Exception $e) {
            return $this->internalServerErrorResponse(__METHOD__, $e);
        }
    }

    /**
     * @param Request $request
     * @param Post|null $post
     * @param Meta|null $meta
     *
     * @return Response
     * @ParamConverter("meta", options={"id" = "meta_id"})
     */
    #[Route('/api/posts/{id}/meta/{meta_id}', name: 'post_meta_update', methods: ['PUT'])]
    public function updatePostMetaAction(Request $request, Post $post = null, Meta $meta = null): Response
    {
        try {
            if (!$post) {
                return $this->clientErrorResponse("Post Not Found", Response::HTTP_NOT_FOUND, 'errors.post_not_found');
            }
            if (!$meta) {
                return $this->clientErrorResponse("Meta Not Found", Response::HTTP_NOT_FOUND, 'errors.meta_not_found');
            }
            if ($meta->getPost()->getId() !== $post->getId()) {
                return $this->clientErrorResponse("Meta Not Belong To Post", Response::HTTP_NOT_FOUND, 'errors.meta_not_belong_to_post');
            }

            /** @var MetaRequest $requestDto */
            $requestDto = $this->serializer->deserialize($request->getContent(), MetaRequest::class, 'json');
            $errors = $this->validator->validate($requestDto, null, ['OpCreate']);

            if (count($errors) > 0) {
                return $this->clientValidationErrorResponse($errors);
            }

            $data = $this->postService->updatePostMeta($meta, $requestDto);
            return new JsonResponse($this->serializer->serialize([
                'status' => self::STATUS_SUCCESS,
                'data' => $data,
            ], 'json', ['groups' => ['default', 'meta_post']]), Response::HTTP_OK, [], true);
        } catch (Exception $e) {
            return $this->internalServerErrorResponse(__METHOD__, $e);
        }
    }
}
