<?php

namespace ElleOL\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity as UniqueEntity;
use ElleOL\AdminBundle\Services\Helpers as Helpers;

/**
 * @ORM\Entity(repositoryClass="ElleOL\SiteBundle\Entity\CategoryRepository")
 * @ORM\Table(name="category")
 * @ORM\HasLifecycleCallbacks()
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()          
     * @Assert\MaxLength(limit=100, message="Must be less than {{ limit }} characters")     
     * @Assert\MinLength(limit=3, message="Must be at least {{ limit }} characters")     
     */
    private $name;

    /**
     *  @ORM\Column(type="string", length=100)
     */
    private $slug;
    
    /**
     * @ORM\Column(type="text")
     * @Assert\MaxLength(limit=255, message="Must be less than {{ limit }} characters")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="category")
     */
    private $products;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param text $description
     * @return Category
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     *
     * @return text 
     */
    public function getDescription()
    {
        return $this->description;
    }
    public function __construct()
    {
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set slug
     *
     * @param string $slug
     * @return Category
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Add products
     *
     * @param ElleOL\SiteBundle\Entity\Product $products
     * @return Category
     */
    public function addProduct(\ElleOL\SiteBundle\Entity\Product $products)
    {
        $this->products[] = $products;
        return $this;
    }

    /**
     * Remove products
     *
     * @param ElleOL\SiteBundle\Entity\Product $products
     */
    public function removeProduct(\ElleOL\SiteBundle\Entity\Product $products)
    {
        $this->products->removeElement($products);
    }

    /**
     * Get products
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @ORM\PrePersist
     */
    public function initSlug()
    {
        $this->setSlug(Helpers::slugify($this->name));
    }    
}