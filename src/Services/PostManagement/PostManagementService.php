<?php

namespace App\Services\PostManagement;

use App\Document\Post;
use App\Dto\Request\PostRequest;
use App\Exception\PostNotFoundException;
use App\Repository\PostRepository;
use Doctrine\ODM\MongoDB\Mapping\MappingException;
use Doctrine\ODM\MongoDB\MongoDBException;

class PostManagementService {

    private PostRepository $postRepository;

    public function __construct(
        PostRepository $postRepository
    )
    {
        $this->postRepository = $postRepository;
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
     */
    public function getPosts() {
        return $this->postRepository->findAll();
    }
}
