<?php

namespace ElleOL\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ElleOLAdminBundle:Default:index.html.twig');
    }
}
