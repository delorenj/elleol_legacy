<?php

namespace ElleOL\SiteBundle\DataFixtures\ORM;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use ElleOL\SiteBundle\Entity\Product;

class LoadProductData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    public function getOrder() {
        return 2;
    }

    public function load(ObjectManager $manager)
    {
        $yaml = new Parser();
        try {
            $value = $yaml->parse(file_get_contents('src/ElleOL/SiteBundle/DataFixtures/data/products.yml'));
        } catch (ParseException $e) {
            printf("Unable to parse the YAML string: %s", $e->getMessage());
        }   
        foreach($value as $product) {
            $p = new Product();
            $p->setName($product["name"]);
            $p->setDescription($product["description"]);
            $p->setPrice($product["price"]);
            $p->setImage($product["image"]);
            $p->setCategory($manager->merge($this->getReference($product["category"])));
            $manager->persist($p);
        }     
        $manager->flush();
    }
}