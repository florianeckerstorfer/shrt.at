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
    public function uploadAction(Request $request)
    {
        $response = new Response();

        if (!$request->isMethod('POST')) {
            $response->setStatusCode(405);

            return $response;
        }

        $message    = $request->get('message');
        $source     = $request->get('source');
        $media      = $request->files->get('media');

        $filename = sprintf('%d/%d/%s-%s', date('Y'), date('m'), uniqid(), $media->getClientOriginalName());

        $filesystem = $this->get('knp_gaufrette.filesystem_map')->get('s3');
        $adapter = $filesystem->getAdapter();
        $adapter->setMetadata($filename, array('contentType' => $media->getClientMimeType()));
        $adapter->write($filename, file_get_contents($media->getPathname()));

        $url = sprintf('%s/%s', $this->container->getParameter('fe_shrt_file.amazon_s3.base_url'), $filename);

        $shortUrl = $this->get('fe_shrt_url.shortener')->shorten($url);

        return new JsonResponse(array('url' => $shortUrl));
    }
}
