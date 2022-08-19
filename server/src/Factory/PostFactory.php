<?php

namespace App\Factory;

use App\Document\Post;
use Doctrine\ODM\MongoDB\MongoDBException;

class PostFactory extends AbstractFactory
{
    /**
     * @param string $title
     * @param string $content
     * @param int $status
     *
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
     * @param Post $post
     * @param string $title
     * @param string $content
     * @param int $status
     *
     * @return Post
     * @throws MongoDBException
     */
    public function updatePost(Post $post, string $title, string $content, int $status): Post
    {
        $post->setTitle($title)
            ->setContent($content)
            ->setStatus($status);

        $this->dm->persist($post);
        $this->dm->flush();

        return $post;
    }
}
