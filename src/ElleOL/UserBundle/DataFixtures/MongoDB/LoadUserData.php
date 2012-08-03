<?php

namespace ElleOL\UserBundle\DataFixtures\MongoDB;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use ElleOL\UserBundle\Document\User;
use ElleOL\UserBundle\Document\Group;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    protected function createGroups($manager) {
        $yaml = new Parser();
        $factory = $this->container->get('security.encoder_factory');

        try {
            $value = $yaml->parse(file_get_contents('src/ElleOL/UserBundle/DataFixtures/MongoDB/data/groups.yml'));
        } catch (ParseException $e) {
            printf("Unable to parse the YAML string: %s", $e->getMessage());
        }   
        foreach($value as $group) {
            $g = new Group();
            $g->setName($group["name"]);
            $g->setRole($group["role"]);
            $manager->persist($g);
        }
        $manager->flush();
    }

    public function load(ObjectManager $manager)
    {
        $this->createGroups($manager);

        $yaml = new Parser();
        $factory = $this->container->get('security.encoder_factory');

        try {
            $value = $yaml->parse(file_get_contents('src/ElleOL/UserBundle/DataFixtures/MongoDB/data/users.yml'));
        } catch (ParseException $e) {
            printf("Unable to parse the YAML string: %s", $e->getMessage());
        }   
        foreach($value as $user) {
            $p = new User();
            $manager->persist($p);
            $manager->flush();
            $encoder = $factory->getEncoder($p);  
            $encpassword = $encoder->encodePassword($user["password"], $p->getSalt());      
            $p->setPassword($encpassword);
            $p->setUsername($user["username"]);
            $p->setEmail($user["email"]);

            foreach($user["groups"] as $group) {
                $go = $manager->getRepository("ElleOLUserBundle:Group")->findOneByName($group);
                //$go = array_values($go);
                $p->addGroups($go);                
                $manager->persist($p);
                $manager->flush();
                $go->addUsers($p);
                $manager->persist($go);
                $manager->flush();
            }            
            $manager->persist($p);
        }     
        $manager->flush();
    }
}