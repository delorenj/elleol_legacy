<?php

namespace ElleOL\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
        $builder->add('price', 'money', array('currency' => 'USD'));
        $builder->add('description', 'textarea');
        $builder->add('image', 'file');
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