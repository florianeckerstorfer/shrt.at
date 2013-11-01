<?php
/**
 * This file is part of shrt.at
 * (c) 2013 Florian Eckerstorfer
 */

namespace Fe\ShrtUrlBundle\Controller;

use Doctrine\ORM\NoResultException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\EngineInterface as TemplatingEngineInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Fe\ShrtBundle\Entity\LinkManager;

/**
 * RedirectController
 *
 * @package    FeShrtUrlBundle
 * @subpackage Controller
 * @author     Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright  2013 Florian Eckerstorfer
 * @license    http://opensource.org/licenses/MIT The MIT License
 * @link       http://shrt.at Shrt.at
 */
class RedirectController
{
    /** @var LinkManager */
    private $linkManager;

    /** @var TemplatingEngineInterface */
    private $templating;

    /**
     * @param LinkManager               $linkManager
     * @param TemplatingEngineInterface $templating
     */
    public function __construct(LinkManager $linkManager, TemplatingEngineInterface $templating)
    {
        $this->linkManager = $linkManager;
        $this->templating = $templating;
    }

    /**
     * Redirects the user to URL associated with the given code.
     *
     * @param Request $request The request
     *
     * @return Response The response
     */
    public function redirectAction($code)
    {
        try {
            $link = $this->linkManager->findLinkByCode($code);

            $response = new Response(
                $this->templating->render('FeShrtUrlBundle:Redirect:redirect.html.twig',[ 'link' => $link ])
            );
            $response->headers->set('Location', $link->getUrl());
            $response->setStatusCode(302);
        } catch (NoResultException $e) {
            throw new NotFoundHttpException;
        }

        return $response;
    }
}
