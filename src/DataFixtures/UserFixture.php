<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Repository\CustomerRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class UserFixture extends Fixture
{

    /**
     * Load data fixtures with the passed EntityManager
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $sfr = $this->getReference(CustomFixture::Custom_REF);
        $orange = $this->getReference(CustomFixture::Custom_REF_ORANGE);
        $bouygues = $this->getReference(CustomFixture::Custom_REF_BOUYGUES);
        $tabCustomer = [$sfr,$orange,$bouygues];

        //foreach ($tabCustomer){
            for ($i = 0; $i < 2; $i++) {
                $user = new User();
                $user->setFirstName('name'.$i);
                $user->setLastName('Nom' .$i);
                $user->setEmail('client'.$i.'@gmail.com');
                $user->setCustomer($sfr);
                $manager->persist($user);
            }
        //}

            $manager->flush();
    }

    public function getDependencies() {

        return [
            CustomFixture::class
        ];
    }
}
