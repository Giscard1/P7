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

        for ($i = 0; $i < 2; $i++) {
            $user2 = new User();
            $user2->setFirstName('name'.$i);
            $user2->setLastName('Nom' .$i);
            $user2->setEmail('client'.$i.'@gmail.com');
            $user2->setCustomer($orange);
            $manager->persist($user2);
        }

        for ($i = 0; $i < 2; $i++) {
            $user3 = new User();
            $user3->setFirstName('name'.$i);
            $user3->setLastName('Nom' .$i);
            $user3->setEmail('client'.$i.'@gmail.com');
            $user3->setCustomer($bouygues);
            $manager->persist($user3);
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
