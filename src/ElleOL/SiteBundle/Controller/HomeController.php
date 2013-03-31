<?php

namespace ElleOL\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction() {

        return $this->render('ElleOLSiteBundle:Home:index.html.twig');        
    }

}
