<?php

namespace ElleOL\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

class ProfileController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ElleOLUserBundle:Profile:index.html.twig', array('name' => $name));
    }

}
