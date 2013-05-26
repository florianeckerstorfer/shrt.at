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
        return new Response($this->get('fe_shrt_url.url_shortener')->shorten($request->get('u')));
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
