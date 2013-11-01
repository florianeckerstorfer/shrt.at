<?php
/**
 * This file is part of shrt.at
 * (c) 2013 Florian Eckerstorfer
 */

namespace Fe\ShrtUrlBundle\Controller;

use Doctrine\ORM\NoResultException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Constraints\UrlValidator;
use Symfony\Component\Validator\Constraints\Url as UrlConstraint;
use Symfony\Component\Validator\Validator;

use Fe\ShrtUrlBundle\UrlShortener;
use Fe\ShrtBundle\Entity\LinkManager;

/**
 * ApiController
 *
 * @package    FeShrtUrlBundle
 * @subpackage Controller
 * @author     Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright  2013 Florian Eckerstorfer
 * @license    http://opensource.org/licenses/MIT The MIT License
 * @link       http://shrt.at
 */
class ApiController
{
    /** @var UrlShortener */
    private $shortener;

    /** @var LinkManager */
    private $linkManager;

    /** @var Validator */
    private $validator;

    /**
     * @param UrlShortener $shortener
     * @param LinkManager  $linkManager
     */
    public function __construct(UrlShortener $shortener, LinkManager $linkManager, Validator $validator)
    {
        $this->shortener = $shortener;
        $this->linkManager = $linkManager;
        $this->validator = $validator;
    }

    /**
     * Returns a short URL for the given URL.
     *
     * @param Request $request The request
     *
     * @return Response The response
     */
    public function shortAction(Request $request)
    {
        $link = $this->linkManager->createLink($request->get('url'));

        if (count($this->validator->validate($link, [ 'short' ])) > 0) {
            throw new BadRequestHttpException('The parameter "url" is not valid.');
        }

        return new Response($this->shortener->shorten($link));
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
        $link = $this->linkManager->createLink(null, $request->get('code'));

        if (count($this->validator->validate($link, [ 'long' ])) > 0) {
            throw new BadRequestHttpException('The parameter "code" is not valid.');
        }

        try {
            $link = $this->linkManager->findLinkByCode($link->getCode());

            $response = new Response($link->getUrl());
        } catch (NoResultException $e) {
            $response = new Response('');
            $response->setStatusCode(404);
        }

        return $response;
    }
}
