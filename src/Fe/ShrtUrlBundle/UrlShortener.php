<?php
/**
 * This file is part of shrt.at
 * (c) 2013 Florian Eckerstorfer
 */

namespace Fe\ShrtUrlBundle;

use Fe\ShrtBundle\Entity\LinkManager;
use Fe\ShrtBundle\Entity\Link;

use Doctrine\ORM\NoResultException;

/**
 * UrlShortener
 *
 * @package    FeShrtUrlBundle
 * @author     Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright  2013 Florian Eckerstorfer
 * @license    http://opensource.org/licenses/MIT The MIT License
 * @link       http://shrt.at Shrt.at
 */
class UrlShortener
{
    /** @var LinkManager */
    private $linkManager;

    /** @var string */
    private $baseUrl;

    /**
     * Constructor.
     *
     * @param LinkManager $linkManager The link manager
     * @param string      $baseUrl     The base URL
     */
    public function __construct(LinkManager $linkManager, $baseUrl)
    {
        $this->linkManager  = $linkManager;
        $this->baseUrl      = $baseUrl;
    }

    /**
     * Shortens the given URL.
     *
     * @param string $url The long URL
     *
     * @return string The short URL
     */
    public function shorten($link)
    {
        if (true === is_string($link)) {
            $link = $this->linkManager->createLink($link);
        }

        try {
            // Let's try if we find the URL in the database
            $link = $this->linkManager->findLinkByUrl($link->getUrl());
        } catch (NoResultException $e) {
            // URL is not in the database, create a new link
            $this->linkManager->updateLink($link);
            $link->setCode(base_convert($link->getId(), 10, 36));
            $this->linkManager->updateLink($link);
        }

        return sprintf('%s/%s', $this->baseUrl, $link->getCode());
    }
}
