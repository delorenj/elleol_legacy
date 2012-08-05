<?php

namespace ElleOL\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ElleOL\SiteBundle\Entity\Product as Product;

class DefaultController extends Controller
{
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$repo = $this->getDoctrine()->getRepository("ElleOLSiteBundle:Product");

    	$products = $repo->findAll();

        return $this->render('ElleOLAdminBundle:Default:index.html.twig', array("products" => $products));
    }
}
