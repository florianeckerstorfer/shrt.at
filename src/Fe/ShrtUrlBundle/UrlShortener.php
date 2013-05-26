<?php

namespace Fe\ShrtUrlBundle;

use Fe\ShrtBundle\Entity\LinkManager;

use Doctrine\ORM\NoResultException;

class UrlShortener
{
    /** @var LinkManager */
    private $linkManager;

    /** @var string */
    private $baseUrl;

    public function __construct(LinkManager $linkManager, $baseUrl)
    {
        $this->linkManager = $linkManager;
        $this->baseUrl = $baseUrl;
    }

    public function shorten($url)
    {
        try {
            // Let's try if we find the URL in the database
            $link = $this->linkManager->findLinkByUrl($url);
        } catch (NoResultException $e) {
            // URL is not in the database, create a new link
            $link = $this->linkManager->createLink($url);
            $this->linkManager->updateLink($link);
            $link->setCode(base_convert($link->getId(), 10, 36));
            $this->linkManager->updateLink($link);
        }

        return sprintf(
            '%s/%s',
            $this->baseUrl,
            $link->getCode()
        );
    }
}