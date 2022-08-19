<?php

namespace App\Repository;

use App\Document\Meta;
use App\Document\Post;
use App\Exception\MetaNotBelongToPostException;
use App\Exception\MetaNotFoundException;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\LockException;
use Doctrine\ODM\MongoDB\Mapping\MappingException;
use Doctrine\ODM\MongoDB\MongoDBException;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class MetaRepository extends DocumentRepository
{
    public function __construct(DocumentManager $dm)
    {
        $uow = $dm->getUnitOfWork();
        $classMetaData = $dm->getClassMetadata(Meta::class);
        parent::__construct($dm, $uow, $classMetaData);
    }



    /**
     * @param string $postId
     * @return array
     */
    public function getMetaByPostId(string $postId)
    {
        return $this->dm->getRepository(Meta::class)
            ->findBy([
                'post_id' => $postId,
            ]);
    }
}
