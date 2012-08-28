<?php

namespace ElleOL\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', null, 
            array('attr' => 
                array('placeholder' => 'Enter the products name')));

        $builder->add('price', 'money', 
            array(  'currency' => 'USD',
                    'attr' => 
                    array('placeholder' => 'Enter the price of the product')));
        
        $builder->add('description', 'textarea', 
            array('attr' => 
                array('placeholder' => 'Describe the product, or leave blank.')));
        
        $builder->add('image', null, array("attr" => array("readonly" => true, "placeholder" => "No image uploaded yet!")));
        
        $builder->add('category', 'entity', array(
            'class' => 'ElleOL\SiteBundle\Entity\Category',
            'multiple' => false,
            'query_builder' => function($repository) { return $repository->createQueryBuilder('p')->orderBy('p.id', 'ASC'); },
            'property' => 'name',
            // 'attr' => array("style" => "width: 400px")
        ));
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
	    $resolver->setDefaults(array(
	        'data_class' => 'ElleOL\SiteBundle\Entity\Product',
	    ));
	}

    public function getName()
    {
        return 'product';
    }
}