<?php
/**
 * This file is part of shrt.at
 * (c) 2013 Florian Eckerstorfer
 */

namespace Fe\ShrtBundle\Controller;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\EngineInterface as TemplatingEngineInterface;

use Fe\ShrtBundle\Entity\Link;
use Fe\ShrtUrlBundle\Form\Type\ShortUrlType;

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

    /** @var FormFactory */
    private $formFactory;

    /**
     * @param TemplatingEngineInterface $templating
     */
    public function __construct(TemplatingEngineInterface $templating, FormFactory $formFactory)
    {
        $this->templating  = $templating;
        $this->formFactory = $formFactory;
    }

    /**
     * @return Response
     */
    public function indexAction()
    {
        $form = $this->formFactory->create(new ShortUrlType, new Link);

        return new Response($this->templating->render(
            'FeShrtBundle:Default:index.html.twig',
            [ 'form' => $form->createView() ]
        ));
    }
}
