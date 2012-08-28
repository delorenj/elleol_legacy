<?php

namespace ElleOL\AdminBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException as NFE;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use ElleOL\SiteBundle\Entity\Category as Category;
use ElleOL\AdminBundle\Form\Type\CategoryType as CategoryType;

class CategoryController extends Controller
{
    public function indexAction() {
        $cats = $this->getDoctrine()->getRepository('ElleOLAdminBundle:Category')->find('all');
        return $this->render('ElleOLAdminBundle:Category:index.html.twig', array('categories' => $cats));
    }

    public function newAction()
    {
        $request = $this->getRequest();
        $repo = $this->getDoctrine()->getRepository("ElleOLSiteBundle:Category");
        $category = new Category();    
        $form = $this->createForm(new CategoryType(), $category);
        return $this->render('ElleOLAdminBundle:Category:new.html.twig', array("form" => $form->createView(), "category" => $category));
    }   

    public function createAction()
    {        
        $request = $this->getRequest();
    }

    public function editAction()
    {
        return $this->render('ElleOLAdminBundle:Category:index.html.twig');
    }   

    public function updateAction()
    {
        return $this->render('ElleOLAdminBundle:Category:index.html.twig');
    }   


}
