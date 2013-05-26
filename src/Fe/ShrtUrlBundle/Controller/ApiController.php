<?php

namespace Fe\ShrtUrlBundle\Controller;

use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
}
