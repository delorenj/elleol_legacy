<?php

namespace ElleOL\UserBundle\DataFixtures\ORM;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use ElleOL\UserBundle\Entity\User;
use ElleOL\UserBundle\Entity\Role;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    protected function createRoles($manager) {
        $yaml = new Parser();
        $factory = $this->container->get('security.encoder_factory');

        try {
            $value = $yaml->parse(file_get_contents('src/ElleOL/UserBundle/DataFixtures/data/roles.yml'));
        } catch (ParseException $e) {
            printf("Unable to parse the YAML string: %s", $e->getMessage());
        }   
        foreach($value as $role) {
            $g = new Role();
            $g->setName($role["name"]);
            $g->setRole($role["role"]);
            $manager->persist($g);
        }
        $manager->flush();
    }

    public function load(ObjectManager $manager)
    {
        $this->createRoles($manager);

        $yaml = new Parser();
        $factory = $this->container->get('security.encoder_factory');

        try {
            $value = $yaml->parse(file_get_contents('src/ElleOL/UserBundle/DataFixtures/data/users.yml'));
        } catch (ParseException $e) {
            printf("Unable to parse the YAML string: %s", $e->getMessage());
        }   
        foreach($value as $user) {
            $p = new User();
            $p->setUsername($user["username"]);
            $p->setEmail($user["email"]);            
            $encoder = $factory->getEncoder($p);  
            $encpassword = $encoder->encodePassword($user["password"], $p->getSalt());      
            $p->setPassword($encpassword);            
            $manager->persist($p);
            $manager->flush();

            foreach($user["roles"] as $role) {
                $go = $manager->getRepository("ElleOLUserBundle:Role")->findOneByName($role);
                //$go = array_values($go);
                $p->addRole($go);                
                $manager->persist($p);
                $manager->flush();
                $go->addUser($p);
                $manager->persist($go);
                $manager->flush();
            }            
            $manager->persist($p);
        }     
        $manager->flush();
    }
}