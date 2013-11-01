<?php
/**
 * This file is part of shrt.at
 * (c) 2013 Florian Eckerstorfer
 */

namespace Fe\ShrtFileBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

use Fe\ShrtFileBundle\FileUploader;
use Fe\ShrtUrlBundle\UrlShortener;

/**
 * ApiController
 *
 * @package    FeShrtFileBundle
 * @subpackage Controller
 * @author     Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright  2013 Florian Eckerstorfer
 * @license    http://opensource.org/licenses/MIT The MIT License
 * @link       http://shrt.at Shrt.at
 */
class ApiController
{
    /** @var FileUploader */
    private $uploader;

    /** @var UrlShortener */
    private $shortener;

    /**
     * @param FileUploader $uploader
     * @param UrlShortener $shortener
     */
    public function __construct(FileUploader $uploader, UrlShortener $shortener)
    {
        $this->uploader  = $uploader;
        $this->shortener = $shortener;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function uploadAction(Request $request)
    {
        // Only POST requests are allowed
        if (false === $request->isMethod('POST')) {
            throw new MethodNotAllowedHttpException([ 'POST' ]);
        }

        // Upload the file
        $url = $this->uploader->upload($request->files->get('file'));
        // Create a short URL for the file
        $shortUrl = $this->shortener->shorten($url);

        return new JsonResponse([ 'url' => $shortUrl ]);
    }
}
