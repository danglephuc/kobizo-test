<?php

namespace App\Repository;

use App\Document\Meta;
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
     * @param string $key
     * @param string $value
     * @return Meta
     * @throws MongoDBException
     */
    public function createMeta(string $postId, string $key, string $value): Meta
    {
        $meta = new Meta();
        $meta->setPostId($postId)
            ->setKey($key)
            ->setValue($value);

        $this->dm->persist($meta);
        $this->dm->flush();

        return $meta;
    }

    /**
     * @param string $postId
     * @param string $id
     * @param string $key
     * @param string $value
     * @return Meta
     * @throws LockException
     * @throws MappingException
     * @throws MetaNotBelongToPostException
     * @throws MetaNotFoundException
     * @throws MongoDBException
     */
    public function updateMeta(string $postId, string $id, string $key, string $value): Meta
    {
        /** @var Meta $meta */
        $meta = $this->dm->getRepository(Meta::class)->find($id);
        if (empty($meta)) {
            throw new MetaNotFoundException();
        }
        if($meta->getPostId() != $postId) {
            throw new MetaNotBelongToPostException();
        }

        $meta->setKey($key)
            ->setValue($value);

        $this->dm->persist($meta);
        $this->dm->flush();

        return $meta;
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
