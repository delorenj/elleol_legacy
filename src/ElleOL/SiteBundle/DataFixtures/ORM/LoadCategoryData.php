<?php

namespace ElleOL\SiteBundle\DataFixtures\ORM;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use ElleOL\SiteBundle\Entity\Category;

class LoadCategoryData extends AbstractFixture implements fixtureInterface, OrderedFixtureInterface
{
    public function getOrder() {
        return 1;
    }

    public function load(ObjectManager $manager)
    {
        $yaml = new Parser();
        try {
            $keys = $yaml->parse(file_get_contents('src/ElleOL/SiteBundle/DataFixtures/data/categories.yml'));
        } catch (ParseException $e) {
            printf("Unable to parse the YAML string: %s", $e->getMessage());
        }   
        foreach($keys as $key) {
            $c = new Category();
            $c->setName($key["name"]);
            $c->setDescription("");
            $manager->persist($c);
            $this->addReference($c->getSlug(), $c);
        }     
        $manager->flush();
    }
}