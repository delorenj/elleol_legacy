<?php

namespace ElleOL\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ElleOL\SiteBundle\Entity\Product as Product;

class ProductController extends Controller
{
    public function indexAction()
    {
    	$repo = $this->get('doctrine')->getRepository('ElleOLSiteBundle:Product');
    	$products = $repo->findAll();
        return $this->render('ElleOLSiteBundle:Product:index.html.twig', array("products" => $products));
    }
}
