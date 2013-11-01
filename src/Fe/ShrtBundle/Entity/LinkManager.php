<?php
/**
 * This file is part of shrt.at
 * (c) 2013 Florian Eckerstorfer
 */

namespace Fe\ShrtBundle\Entity;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;

/**
 * LinkManager
 *
 * @package    FeShrtBundle
 * @subpackage Entity
 * @author     Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright  2013 Florian Eckerstorfer
 * @license    http://opensource.org/licenses/MIT The MIT License
 * @link       http://shrt.at Shrt.at
 */
class LinkManager
{
    /** @var string */
    private $className;

    /** @var ObjectManager */
    private $objectManager;

    /** @var ObjectRepository */
    protected $repository;

    /**
     * @param ObjectManager $objectManager
     * @param string        $className
     */
    public function __construct(ObjectManager $objectManager, $className)
    {
        $this->objectManager = $objectManager;
        $this->repository    = $objectManager->getRepository($className);

        $metadata = $objectManager->getClassMetadata($className);
        $this->className = $metadata->getName();
    }

    /**
     * @param string $url
     *
     * @return Link
     */
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

    /**
     * @param string $code
     *
     * @return Link
     */
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

    /**
     * @param string $url
     * @param string $code
     *
     * @return Link
     */
    public function createLink($url = null, $code = null)
    {
        $className = $this->className;
        $link = new $className();

        $link->setCreatedAt(new \DateTime(null, new \DateTimeZone('UTC')))
             ->setCode($code)
             ->setUrl($url);

        return $link;
    }

    /**
     * @param Link    $link
     * @param boolean $andFlush
     *
     * @return void
     */
    public function updateLink(Link $link, $andFlush = true)
    {
        $link->setUpdatedAt(new \DateTime(null, new \DateTimeZone('UTC')));
        $this->objectManager->persist($link);

        if (true === $andFlush) {
            $this->flush();
        }
    }

    /**
     * @return void
     */
    public function flush()
    {
        $this->objectManager->flush();
    }
}
