<?php

namespace Fe\ShrtBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render(
            'FeShrtBundle:Default:index.html.twig',
            array()
        );
    }
}
