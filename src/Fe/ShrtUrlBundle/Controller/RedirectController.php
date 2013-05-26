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
 * RedirectController
 *
 * @package    FeShrtUrlBundle
 * @subpackage Controller
 * @author     Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright  2013 Florian Eckerstorfer
 * @license    http://opensource.org/licenses/MIT The MIT License
 */
class RedirectController extends Controller
{
    /**
     * Redirects the user to URL associated with the given code.
     *
     * @param Request $request The request
     *
     * @return Response The response
     */
    public function redirectAction($code)
    {
        $linkManager = $this->get('fe_shrt.link_manager');

        try {
            $link = $linkManager->findLinkByCode($code);

            $response = new Response($this->renderView(
                'FeShrtUrlBundle:Redirect:redirect.html.twig',
                array('link' => $link)
            ));
            $response->headers->set('Location', $link->getUrl());
            $response->setStatusCode(302);
        } catch (NoResultException $e) {
            $response = new Response($this->renderView('FeShrtUrlBundle:Redirect:404.html.twig'));
            $response->setStatusCode(404);
        }

        return $response;
    }
}
