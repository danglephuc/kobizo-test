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

    /**
     * @param int $page
     * @param int $limit
     * @return array
     * @throws MongoDBException
     */
    public function getPostsByPage(int $page = 1, int $limit = 10)
    {
        $total = $this->dm->getRepository(Post::class)->createQueryBuilder()
            ->count()->getQuery()->execute();

        $results = $this->dm->getRepository(Post::class)->createQueryBuilder()
            ->sort('created_at', 'desc')
            ->skip(($page - 1) * $limit)
            ->limit($limit)
            ->getQuery()->execute();

        return [
            'results' => $results,
            'pagination' => [
                'current' => $page,
                'limit' => $limit,
                'total' => $total,
            ],
        ];
    }
}
