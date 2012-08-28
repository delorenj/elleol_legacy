<?php

namespace ElleOL\AdminBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException as NFE;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use ElleOL\SiteBundle\Entity\Product;
use ElleOL\SiteBundle\Entity\Category;
use ElleOL\AdminBundle\Form\Type\ProductType;

class ProductController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $products = $this->getDoctrine()->getRepository("ElleOLSiteBundle:Product")->findAll();
        $categories = $this->getDoctrine()->getRepository("ElleOLSiteBundle:Category")->findAll();
        return $this->render('ElleOLAdminBundle:Default:index.html.twig', array("products" => $products, "categories" => $categories));
    }

    public function indexFilterAction($cat)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $categories = $this->getDoctrine()->getRepository("ElleOLSiteBundle:Category")->findAll();
        $cat = $this->getDoctrine()->getRepository("ElleOLSiteBundle:Category")->findOneBySlug($cat);

        if(!$cat instanceof Category) {
            throw new NFE("Category not found!");
        }

        $products = $cat->getProducts();

        return $this->render('ElleOLAdminBundle:Default:index.html.twig', array("products" => $products, "categories" => $categories, "curcat" => $cat));
    }

    public function createAction()
    {        
        $request = $this->getRequest();
        $repo = $this->getDoctrine()->getRepository("ElleOLSiteBundle:Product");
        $categories = $this->getDoctrine()->getRepository("ElleOLSiteBundle:Category")->findAll();

        $product = new Product();    
        $product->setCreatedAt(new \DateTime());  
        $form = $this->createForm(new ProductType(), $product);
        $form->bindRequest($request);
        if($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($product);
            $em->flush();
            $this->get('session')->setFlash("notice", "Product Created Successfully!");
            return $this->redirect($this->generateUrl('admin_home'));
        } else {
            $this->get('logger')->info('CREATE: ' . json_encode($this->getErrorMessages($form)));
            return $this->render('ElleOLAdminBundle:Product:new.html.twig', array("form" => $form->createView(), "product" => $product, "categories" => $categories));
        }
    }

    public function newAction()
    {
        $request = $this->getRequest();
        $repo = $this->getDoctrine()->getRepository("ElleOLSiteBundle:Product");
        $categories = $this->getDoctrine()->getRepository("ElleOLSiteBundle:Category")->findAll();        
        $product = new Product();    
        $form = $this->createForm(new ProductType(), $product);
        return $this->render('ElleOLAdminBundle:Product:new.html.twig', array("form" => $form->createView(), "product" => $product, "categories" => $categories));
    }   


    public function deleteAction($id) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getEntityManager();
        $repo = $this->getDoctrine()->getRepository('ElleOLSiteBundle:Product');
        $product = $repo->find($id);
        $webRootDir = $_SERVER["DOCUMENT_ROOT"];

        if(!$product instanceof Product) {
            throw new NFE("This product does not exist");            
        }


        if(!is_null($product->getImage())) {
            if(file_exists($webRootDir . $product->getImage())) {
                unlink($webRootDir . $product->getImage());
            }
            
            if(file_exists(preg_replace("/_thumb/", "", $webRootDir . $product->getImage()))) {
                unlink(preg_replace("/_thumb/", "", $webRootDir . $product->getImage()));
            }
        }
        $em->remove($product);
        $em->flush();
        return $this->redirect($this->generateUrl('admin_home'));
    }

    public function updateAction($id)
    {
        $request = $this->getRequest();
        $repo = $this->getDoctrine()->getRepository("ElleOLSiteBundle:Product");
        $categories = $this->getDoctrine()->getRepository("ElleOLSiteBundle:Category")->findAll();        
        $product = $repo->find($id);        
        $form = $this->createForm(new ProductType(), $product);

        if($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($product);
                $em->flush();
                $this->get('session')->setFlash("notice", "Product Updated Successfully!");
                return $this->redirect($this->generateUrl('admin_home'));
            } else {
                $this->get('logger')->info('UPDATE: ' . json_encode($this->getErrorMessages($form)));
            }
        }
        return $this->render('ElleOLAdminBundle:Product:update.html.twig', array("form" => $form->createView(), "product" => $product, "categories" => $categories));
    }   


    public function imageUploadAction() { 
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getEntityManager();
        $uh = $this->get('upload_helper');
        $result = $uh->handleUpload($_SERVER["DOCUMENT_ROOT"] . "/img/products/");
        if(array_key_exists("success", $result) && $result["success"] == true) {
            $return = array("success" => true, "width" => $result["width"], "height" => $result["height"]);
        } else {
            $return = array("responseCode" => 400, "error" => "Error uploading file");
        }

        $return=json_encode($return);//jscon encode the array
        return new Response($return,200,array('Content-Type'=>'application/json')); 
    }

    public function imageCropAction() {
        $request = $this->getRequest();
        $x = intval($request->request->get('x'));
        $y = intval($request->request->get('y'));
        $w = intval($request->request->get('w'));
        $h = intval($request->request->get('h'));
        $filepath = $request->request->get('filepath');
        $em = $this->getDoctrine()->getEntityManager();
        $targ_w = 240;
        $targ_h = 340;
        $webRootDir = $_SERVER["DOCUMENT_ROOT"];
        $fileDir = dirname($filepath);
        $fileName = basename($filepath);
        $fileName = explode(".", $fileName);
        $fileName = strtolower($fileName[0]);
        $finalSrc = $fileDir . "/" . $fileName . "_thumb.jpg";
        $tempFilePath = $webRootDir . "/" . $fileDir . "/" . $fileName . ".jpg";
        $finalFilePath = $webRootDir . $finalSrc;

        $this->get('logger')->info('CROP: webroot: ' . $webRootDir);
        $this->get('logger')->info('CROP: fileDir: ' . $fileDir);
        $this->get('logger')->info('CROP: fileName: ' . $fileName);
        $this->get('logger')->info('CROP: finalSrc: ' . $finalSrc);
        $this->get('logger')->info('CROP: tempFilePath: ' . $tempFilePath);
        $this->get('logger')->info('CROP: finalFilePath: ' . $finalFilePath);
        
        $uh = $this->get('upload_helper');
        $result = $uh->cropImage($finalSrc, $finalFilePath, $tempFilePath, $x, $y, $w, $h, $targ_w, $targ_h);

        $return=json_encode($result);//jscon encode the array
        return new Response($return,200,array('Content-Type'=>'application/json'));         
    }

    private function getErrorMessages(Symfony\Component\Form\Form $form) {
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
                    $this->get('logger')->info('CREATE: ' . $child->getName());
                    $errors[$child->getName()] = $this->getErrorMessages($child);
                }
            }
        }
        return $errors;
    }    
}
