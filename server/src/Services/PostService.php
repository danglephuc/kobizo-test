<?php

namespace App\Services;

use App\Document\Meta;
use App\Document\Post;
use App\Dto\Request\MetaRequest;
use App\Dto\Request\PostRequest;
use App\Factory\MetaFactory;
use App\Factory\PostFactory;
use App\Repository\MetaRepository;
use App\Repository\PostRepository;
use Doctrine\ODM\MongoDB\MongoDBException;
use JetBrains\PhpStorm\ArrayShape;

class PostService
{

    private PostRepository $postRepository;

    private MetaRepository $metaRepository;

    private PostFactory $postFactory;

    private MetaFactory $metaFactory;

    public function __construct(
        PostFactory $postFactory,
        MetaFactory $metaFactory,
        PostRepository $postRepository,
        MetaRepository $metaRepository,
    )
    {
        $this->postFactory = $postFactory;
        $this->metaFactory = $metaFactory;
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
        return $this->postFactory->createPost($postRequest->getTitle(), $postRequest->getContent(), $postRequest->getStatus());
    }

    /**
     * @param Post $post
     * @param PostRequest $postRequest
     * @return Post
     * @throws MongoDBException
     */
    public function updatePost(Post $post, PostRequest $postRequest): Post
    {
        return $this->postFactory->updatePost($post, $postRequest->getTitle(), $postRequest->getContent(), $postRequest->getStatus());
    }

    /**
     * @param int $page
     * @param int $limit
     * @return array
     * @throws MongoDBException
     */
    #[ArrayShape(['results' => "mixed", 'pagination' => "array"])]
    public function getPosts(int $page, int $limit): array
    {
        return $this->postRepository->getPostsByPage($page, $limit);
    }

    /**
     * @param Post $post
     * @param MetaRequest $requestDto
     *
     * @return Meta
     * @throws MongoDBException
     */
    public function createPostMeta(Post $post, MetaRequest $requestDto): Meta
    {
        return $this->metaFactory->createMeta($post, $requestDto->getKey(), $requestDto->getValue());
    }

    /**
     * @param Meta $meta
     * @param MetaRequest $requestDto
     * @return Meta
     * @throws MongoDBException
     */
    public function updatePostMeta(Meta $meta, MetaRequest $requestDto): Meta
    {
        return $this->metaFactory->updateMeta($meta, $requestDto->getKey(), $requestDto->getValue());
    }
}
