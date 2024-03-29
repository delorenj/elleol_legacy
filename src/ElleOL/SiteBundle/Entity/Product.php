<?php

namespace ElleOL\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity as UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="ElleOL\SiteBundle\Entity\ProductRepository")
 * @ORM\Table(name="product")
 * @ORM\HasLifecycleCallbacks() 
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()          
     * @Assert\MaxLength(limit=100, message="Must be less than {{ limit }} characters")     
     * @Assert\MinLength(limit=3, message="Must be at least {{ limit }} characters")     
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Assert\MaxLength(limit=255, message="Must be less than {{ limit }} characters")
     */
    private $description;

    /**
     * @ORM\Column(type="float", scale=2)
     * @Assert\NotBlank()     
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $image;

    /**
     * @ORM\Column(type="datetime")     
     */
    private $created_at;


    /**
     *  @ORM\ManyToOne(targetEntity="Category", inversedBy="products")
     */
    private $category;


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
     * @return Product
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
     * @return Product
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

    /**
     * Set price
     *
     * @param float $price
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * Get price
     *
     * @return float 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return Product
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set created_at
     *
     * @param datetime $createdAt
     * @return Product
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
        return $this;
    }

    /**
     * Get created_at
     *
     * @return datetime 
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set category
     *
     * @param ElleOL\SiteBundle\Entity\Category $category
     * @return Product
     */
    public function setCategory(\ElleOL\SiteBundle\Entity\Category $category = null)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * Get category
     *
     * @return ElleOL\SiteBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @ORM\PrePersist
     */
    public function initCreatedAt()
    {
        $this->setCreatedAt(new \DateTime());
    }        
}