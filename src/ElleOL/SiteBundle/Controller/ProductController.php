<?php

namespace ElleOL\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProductController extends Controller
{
    public function indexAction()
    {
        return $this->render('ElleOLSiteBundle:Product:index.html.twig');
    }
}
