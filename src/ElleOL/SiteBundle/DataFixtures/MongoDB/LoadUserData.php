<?php

namespace ElleOL\SiteBundle\DataFixtures\MongoDB;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use ElleOL\SiteBundle\Document\User;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }


    public function load(ObjectManager $manager)
    {
        $yaml = new Parser();
        $factory = $this->container->get('security.encoder_factory');

        try {
            $value = $yaml->parse(file_get_contents('src/ElleOL/SiteBundle/DataFixtures/MongoDB/data/users.yml'));
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
            $manager->persist($p);
        }     
        $manager->flush();
    }
}