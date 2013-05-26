<?php
/**
 * This file is part of shrt.at
 * (c) 2013 Florian Eckerstorfer
 */

namespace Fe\ShrtUrlBundle\Controller;

use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * ApiController
 *
 * @package    FeShrtUrlBundle
 * @subpackage Controller
 * @author     Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright  2013 Florian Eckerstorfer
 * @license    http://opensource.org/licenses/MIT The MIT License
 */
class ApiController extends Controller
{
    /**
     * Returns a short URL for the given URL.
     *
     * @param Request $request The request
     *
     * @return Response The response
     */
    public function shortAction(Request $request)
    {
        $linkManager    = $this->get('fe_shrt.link_manager');
        $url            = $request->get('u');

        try {
            // Let's try if we find the URL in the database
            $link = $linkManager->findLinkByUrl($url);
        } catch (NoResultException $e) {
            // URL is not in the database, create a new link
            $link = $linkManager->createLink($url);
            $linkManager->updateLink($link);
            $link->setCode(base_convert($link->getId(), 10, 36));
            $linkManager->updateLink($link);
        }

        return new Response(sprintf(
            '%s/%s',
            $this->container->getParameter('fe_shrt.base_url'),
            $link->getCode()
        ));
    }

    /**
     * Returns the long URL of the given code.
     *
     * @param Request $request The request
     *
     * @return Response The response
     */
    public function longAction(Request $request)
    {
        $linkManager    = $this->get('fe_shrt.link_manager');
        $code           = $request->get('c');

        try {
            $link = $linkManager->findLinkByCode($code);

            $response = new Response($link->getUrl());
        } catch (NoResultException $e) {
            $response = new Response('');
            $response->setStatusCode(404);
        }

        return $response;
    }
}
