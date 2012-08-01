<?php

namespace ElleOL\SiteBundle\DataFixtures\MongoDB;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ElleOL\SiteBundle\Document\User;

class LoadUserData implements FixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $yaml = new Parser();
        try {
            $value = $yaml->parse(file_get_contents('src/ElleOL/SiteBundle/DataFixtures/MongoDB/data/users.yml'));
        } catch (ParseException $e) {
            printf("Unable to parse the YAML string: %s", $e->getMessage());
        }   
        foreach($value as $user) {
            $p = new User();
            $p->setUsername($user["username"]);
            $p->setEmail($user["email"]);
            $p->setPassword($user["password"]);
            $manager->persist($p);
        }     
        $manager->flush();
    }
}