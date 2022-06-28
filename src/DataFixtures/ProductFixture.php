<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixture extends Fixture
{
    /**
     * Load data fixtures with the passed EntityManager
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {

            $product = new Product();
            $product->setName('Phone name '. $i);
            $product->setBrand('Phone brand '.$i);
            $product->setColor('Phone color '. $i);
            $product->setPrice(50.00);
            $product->setProcessor('Phone processor'. $i);
            $product->setRam(160);
            $manager->persist($product);
        }
        $manager->flush();
    }
}
