<?php

namespace ElleOL\AdminBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use ElleOL\SiteBundle\Entity\Product as Product;
use ElleOL\AdminBundle\Form\Type\ProductType as ProductType;

class ProductController extends Controller
{
    public function newAction()
    {
        return $this->render('ElleOLAdminBundle:Product:new.html.twig');
    }

    public function createAction()
    {
        return $this->render('ElleOLAdminBundle:Product:new.html.twig');
    }

    public function editAction($id)
    {
    	$repo = $this->getDoctrine()->getRepository("ElleOLSiteBundle:Product");
    	$product = $repo->find($id);

    	if(!$product instanceof Product) {
            throw new NotFoundHttpException('Product not found');    		
    	}	

	    $form = $this->createForm(new ProductType(), $product);

        return $this->render('ElleOLAdminBundle:Product:edit.html.twig', array("form"=>$form->createView(), "product" => $product));
    }    

    public function updateAction($id)
    {
        return $this->render('ElleOLAdminBundle:Product:edit.html.twig');
    }   

    public function deleteAction($id)
    {
        return $this->render('ElleOLAdminBundle:Default:index.html.twig');
    }

    public function imageUploadAction($id) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getEntityManager();
        $uh = $this->get('upload_helper');
        $result = $uh->handleUpload("/Applications/MAMP/htdocs/elleol/web/img/products/");
        if(array_key_exists("success", $result) && $result["success"] == true) {
            $return = array("success" => true);
            $p = $this->getDoctrine()->getRepository("ElleOLSiteBundle:Product")->find($id);
            if(!$p instanceof Product) {
                throw new NotFoundHttpException("Product not found");
            }
            $p->setImage("/img/products/" . $request->query->get('qqfile'));
            $em->persist($p);
            $em->flush();

        } else {
            $return = array("responseCode" => 400, "error" => "Didn't work");
        }

        $return=json_encode($return);//jscon encode the array
        return new Response($return,200,array('Content-Type'=>'application/json')); 
    }
}
