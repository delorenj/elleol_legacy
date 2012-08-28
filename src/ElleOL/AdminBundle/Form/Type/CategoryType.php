<?php

namespace ElleOL\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', null, 
            array('attr' => 
                array('placeholder' => 'Enter a category name')));

        $builder->add('description', 'textarea', 
            array('attr' => 
                array('placeholder' => 'Describe the category, or leave blank.')));

    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
	    $resolver->setDefaults(array(
	        'data_class' => 'ElleOL\SiteBundle\Entity\Category',
	    ));
	}

    public function getName()
    {
        return 'category';
    }
}