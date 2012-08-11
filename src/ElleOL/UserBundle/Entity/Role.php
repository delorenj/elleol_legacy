<?php

namespace ElleOL\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * ElleOL\UserBundle\Entity\Role
 */
class Role implements RoleInterface
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $role
     */
    private $role;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $users;

    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
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
     * @return Role
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
     * Set role
     *
     * @param string $role
     * @return Role
     */
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }

    /**
     * Get role
     *
     * @return string 
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Add users
     *
     * @param ElleOL\UserBundle\Entity\User $users
     * @return Role
     */
    public function addUser(\ElleOL\UserBundle\Entity\User $users)
    {
        $this->users[] = $users;
        return $this;
    }

    /**
     * Remove users
     *
     * @param ElleOL\UserBundle\Entity\User $users
     */
    public function removeUser(\ElleOL\UserBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }
}