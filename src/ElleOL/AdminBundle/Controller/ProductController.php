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


    public function updateAction($id)
    {
        $request = $this->getRequest();
        $repo = $this->getDoctrine()->getRepository("ElleOLSiteBundle:Product");
        $product = $repo->find($id);        
        $form = $this->createForm(new ProductType(), $product);

        if($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if($form->isValid()) {
                $this->get('logger')->info('UPDATE: here!');
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($product);
                $em->flush();
                $this->get('session')->setFlash("notice", "Product Updated Successfully!");
                return $this->redirect($this->generateUrl('ElleOLAdminBundle_homepage'));
            } else {
                $this->get('logger')->info('UPDATE: ' . json_encode($this->getErrorMessages($form)));
            }
        }
        return $this->render('ElleOLAdminBundle:Product:update.html.twig', array("form" => $form->createView(), "product" => $product));
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
            $oldImage = $p->getImage();
            $p->setImage("/img/products/" . $request->query->get('qqfile'));            
            $validator = $this->get('validator');
            $errors = $validator->validate($p);

            if (count($errors) > 0) {
                $return = array("responseCode" => 400, "error" => "This product was already posted?");
                $p->setImage($oldImage);
            } else {
                $em->persist($p);
                $em->flush();
            }            
        } else {
            $return = array("responseCode" => 400, "error" => "Error uploading file");
        }

        $return=json_encode($return);//jscon encode the array
        return new Response($return,200,array('Content-Type'=>'application/json')); 
    }

    private function getErrorMessages(\Symfony\Component\Form\Form $form) {
        $errors = array();
        foreach ($form->getErrors() as $key => $error) {
            $template = $error->getMessageTemplate();
            $parameters = $error->getMessageParameters();

            foreach($parameters as $var => $value){
                $template = str_replace($var, $value, $template);
            }

            $errors[$key] = $template;
        }
        if ($form->hasChildren()) {
            foreach ($form->getChildren() as $child) {
                if (!$child->isValid()) {
                    $errors[$child->getName()] = $this->getErrorMessages($child);
                }
            }
        }
        return $errors;
    }    
}
