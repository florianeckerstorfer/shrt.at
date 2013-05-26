<?php
/**
 * This file is part of shrt.at
 * (c) 2013 Florian Eckerstorfer
 */

namespace Fe\ShrtFileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * ApiController
 *
 * @package    FeShrtFileBundle
 * @subpackage Controller
 * @author     Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright  2013 Florian Eckerstorfer
 * @license    http://opensource.org/licenses/MIT The MIT License
 */
class ApiController extends Controller
{
    /**
     * Upload action.
     *
     * @param Request $request The request
     *
     * @return Response The response
     */
    public function uploadAction(Request $request)
    {
        // Only POST requests are allowed
        if (!$request->isMethod('POST')) {
            $response = new Response();
            $response->setStatusCode(405);

            return $response;
        }

        // Upload the file
        $url = $this->get('fe_shrt_file.uploader')->upload($request->files->get('media'));
        // Create a short URL for the file
        $shortUrl = $this->get('fe_shrt_url.shortener')->shorten($url);

        return new JsonResponse(array('url' => $shortUrl));
    }
}
