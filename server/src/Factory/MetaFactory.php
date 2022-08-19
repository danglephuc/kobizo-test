<?php

namespace App\Factory;

use App\Document\Meta;
use App\Document\Post;
use Doctrine\ODM\MongoDB\MongoDBException;

class MetaFactory extends AbstractFactory
{
    /**
     * @param Post $post
     * @param string $key
     * @param string $value
     * @return Meta
     * @throws MongoDBException
     */
    public function createMeta(Post $post, string $key, string $value): Meta
    {
        $meta = new Meta();
        $meta->setPost($post)
            ->setKey($key)
            ->setValue($value);

        $this->dm->persist($meta);
        $this->dm->flush();

        return $meta;
    }

    /**
     * @param Meta $meta
     * @param string $key
     * @param string $value
     * @return Meta
     * @throws MongoDBException
     */
    public function updateMeta(Meta $meta, string $key, string $value): Meta
    {
        $meta->setKey($key)
            ->setValue($value);

        $this->dm->persist($meta);
        $this->dm->flush();

        return $meta;
    }
}
