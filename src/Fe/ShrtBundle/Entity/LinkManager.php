<?php

namespace Fe\ShrtBundle\Entity;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;

class LinkManager
{
    /** @var string */
    private $className;

    /** @var ObjectManager */
    private $objectManager;

    /** @var ObjectRepository */
    protected $repository;

    public function __construct(ObjectManager $objectManager, $className)
    {
        $this->objectManager    = $objectManager;
        $this->repository       = $objectManager->getRepository($className);

        $metadata = $objectManager->getClassMetadata($className);
        $this->className = $metadata->getName();
    }

    public function findLinkByUrl($url)
    {
        return $this->objectManager->createQueryBuilder()
            ->select('l')
            ->from($this->className, 'l')
            ->where('l.url = :url')
            ->setParameter('url', $url)
            ->getQuery()
            ->getSingleResult();
    }

    public function findLinkByCode($code)
    {
        return $this->objectManager->createQueryBuilder()
            ->select('l')
            ->from($this->className, 'l')
            ->where('l.code = :code')
            ->setParameter('code', $code)
            ->getQuery()
            ->getSingleResult();
    }

    public function createLink($url = null, $code = null)
    {
        $className = $this->className;
        $link = new $className();
        $link->setCreatedAt(new \DateTime(null, new \DateTimeZone('UTC')));

        if ($code) {
            $link->setCode($code);
        }
        if ($url) {
            $link->setUrl($url);
        }

        return $link;
    }

    public function updateLink(Link $link, $andFlush = true)
    {
        $link->setUpdatedAt(new \DateTime(null, new \DateTimeZone('UTC')));
        $this->objectManager->persist($link);

        if ($andFlush) {
            $this->flush();
        }
    }

    public function flush()
    {
        $this->objectManager->flush();
    }
}