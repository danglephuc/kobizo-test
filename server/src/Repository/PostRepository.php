<?php

namespace App\Repository;

use App\Document\Post;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;
use JetBrains\PhpStorm\ArrayShape;

class PostRepository extends DocumentRepository
{
    public function __construct(DocumentManager $dm)
    {
        $uow = $dm->getUnitOfWork();
        $classMetaData = $dm->getClassMetadata(Post::class);
        parent::__construct($dm, $uow, $classMetaData);
    }

    /**
     * @param int $page
     * @param int $limit
     * @return array
     * @throws MongoDBException
     */
    #[ArrayShape(['results' => "mixed", 'pagination' => "array"])]
    function getPostsByPage(int $page = 1, int $limit = 10): array
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
