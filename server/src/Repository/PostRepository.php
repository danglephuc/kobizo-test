<?php

namespace App\Repository;

use App\Document\Post;
use App\Exception\PostNotFoundException;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\LockException;
use Doctrine\ODM\MongoDB\Mapping\MappingException;
use Doctrine\ODM\MongoDB\MongoDBException;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class PostRepository extends DocumentRepository
{
    public function __construct(DocumentManager $dm)
    {
        $uow = $dm->getUnitOfWork();
        $classMetaData = $dm->getClassMetadata(Post::class);
        parent::__construct($dm, $uow, $classMetaData);
    }

    /**
     * @param string $title
     * @param string $content
     * @param int $status
     * @return Post
     * @throws MongoDBException
     */
    public function createPost(string $title, string $content, int $status): Post
    {
        $post = new Post();
        $post->setTitle($title)
            ->setContent($content)
            ->setStatus($status);

        $this->dm->persist($post);
        $this->dm->flush();

        return $post;
    }

    /**
     * @param string $id
     * @param string $title
     * @param string $content
     * @param int $status
     * @return Post
     * @throws PostNotFoundException
     * @throws MongoDBException|MappingException
     */
    public function updatePost(string $id, string $title, string $content, int $status): Post
    {
        /** @var Post $post */
        $post = $this->dm->getRepository(Post::class)->find($id);
        if (empty($post)) {
            throw new PostNotFoundException();
        }

        $post->setTitle($title)
            ->setContent($content)
            ->setStatus($status);

        $this->dm->persist($post);
        $this->dm->flush();

        return $post;
    }
}
