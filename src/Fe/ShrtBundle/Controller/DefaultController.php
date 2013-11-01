<?php
/**
 * This file is part of shrt.at
 * (c) 2013 Florian Eckerstorfer
 */

namespace Fe\ShrtBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\EngineInterface as TemplatingEngineInterface;

/**
 * DefaultController
 *
 * @package    FeShrtBundle
 * @subpackage Controller
 * @author     Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright  2013 Florian Eckerstorfer
 * @license    http://opensource.org/licenses/MIT The MIT License
 * @link       http://shrt.at Shrt.at
 */
class DefaultController
{
    /** @var TemplatingEngineInterface */
    private $templating;

    /**
     * @param TemplatingEngineInterface $templating
     */
    public function __construct(TemplatingEngineInterface $templating)
    {
        $this->templating = $templating;
    }

    /**
     * @return Response
     */
    public function indexAction()
    {
        return new Response($this->templating->render('FeShrtBundle:Default:index.html.twig'));
    }
}
