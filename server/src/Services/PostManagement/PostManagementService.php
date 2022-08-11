<?php

namespace App\Services\PostManagement;

use App\Document\Meta;
use App\Document\Post;
use App\Dto\Request\MetaRequest;
use App\Dto\Request\PostRequest;
use App\Exception\MetaNotBelongToPostException;
use App\Exception\MetaNotFoundException;
use App\Exception\PostNotFoundException;
use App\Repository\MetaRepository;
use App\Repository\PostRepository;
use Doctrine\ODM\MongoDB\LockException;
use Doctrine\ODM\MongoDB\Mapping\MappingException;
use Doctrine\ODM\MongoDB\MongoDBException;

class PostManagementService
{

    private PostRepository $postRepository;
    private MetaRepository $metaRepository;

    public function __construct(
        PostRepository $postRepository,
        MetaRepository $metaRepository,
    )
    {
        $this->postRepository = $postRepository;
        $this->metaRepository = $metaRepository;
    }

    /**
     * @param PostRequest $postRequest
     * @return Post
     * @throws MongoDBException
     */
    public function createPost(PostRequest $postRequest): Post
    {
        return $this->postRepository->createPost($postRequest->getTitle(), $postRequest->getContent(), $postRequest->getStatus());
    }

    /**
     * @param PostRequest $postRequest
     * @return Post
     * @throws MongoDBException
     * @throws PostNotFoundException
     * @throws MappingException
     */
    public function updatePost(PostRequest $postRequest): Post
    {
        return $this->postRepository->updatePost($postRequest->getId(), $postRequest->getTitle(), $postRequest->getContent(), $postRequest->getStatus());
    }

    /**
     * @return array
     * @throws MongoDBException
     */
    public function getPosts(int $page, int $limit): array
    {
        $posts = $this->postRepository->getPostsByPage($page, $limit);
        /** @var Post $post */
        foreach ($posts['results'] as $post) {
            $post->setMeta($this->metaRepository->getMetaByPostId($post->getId()));
        }
        return $posts;
    }

    /**
     * @throws MappingException
     * @throws LockException
     * @throws PostNotFoundException
     */
    public function getPost(string $postId): Post
    {
        /** @var Post $post */
        $post = $this->postRepository->find($postId);
        if (empty($post)) {
            throw new PostNotFoundException();
        }
        $post->setMeta($this->metaRepository->getMetaByPostId($postId));
        return $post;
    }

    /**
     * @param string $postId
     * @param MetaRequest $requestDto
     * @return Meta
     * @throws MongoDBException
     * @throws PostNotFoundException
     * @throws MappingException
     */
    public function createPostMeta(string $postId, MetaRequest $requestDto): Meta
    {
        $post = $this->postRepository->find($postId);
        if (empty($post)) {
            throw new PostNotFoundException();
        }
        return $this->metaRepository->createMeta($postId, $requestDto->getKey(), $requestDto->getValue());
    }

    /**
     * @param string $postId
     * @param MetaRequest $requestDto
     * @return Meta
     * @throws LockException
     * @throws MappingException
     * @throws MongoDBException
     * @throws PostNotFoundException
     * @throws MetaNotFoundException
     * @throws MetaNotBelongToPostException
     */
    public function updatePostMeta(string $postId, MetaRequest $requestDto): Meta
    {
        $post = $this->postRepository->find($postId);
        if (empty($post)) {
            throw new PostNotFoundException();
        }
        return $this->metaRepository->updateMeta($postId, $requestDto->getId(), $requestDto->getKey(), $requestDto->getValue());
    }
}
