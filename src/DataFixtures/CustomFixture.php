<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\User;
use App\Repository\CustomerRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CustomFixture extends Fixture
{
    const Custom_REF = 'custom-ref';
    const Custom_REF_ORANGE = 'custom-ref-orange';
    const Custom_REF_BOUYGUES = 'custom-ref-bouygues';


    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }
    /**
     * Load data fixtures with the passed EntityManager
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
            $sfr = new Customer();
            $sfr->setName('Sfr');
            $sfr->setEmail('sfrFake@gmail.com');
            $sfr->setRoles(["ROLE_ADMIN"]);
            $sfr->setPassword($this->userPasswordHasher->hashPassword($sfr, "password"));

            $orange = new Customer();
            $orange->setName('Orange');
            $orange->setEmail('orangeFake@gmail.com');
            $orange->setRoles(["ROLE_ADMIN"]);
            $orange->setPassword($this->userPasswordHasher->hashPassword($orange, "password"));

            $bouygues = new Customer();
            $bouygues->setName('Bouygues');
            $bouygues->setEmail('bouyguesFake@gmail.com');
            $bouygues->setRoles(["ROLE_USER"]);
            $bouygues->setPassword($this->userPasswordHasher->hashPassword($bouygues, "password"));

            $manager->persist($sfr);
            $manager->persist($orange);
            $manager->persist($bouygues);


            $this->addReference(self::Custom_REF, $sfr);
            $this->addReference(self::Custom_REF_ORANGE, $orange);
            $this->addReference(self::Custom_REF_BOUYGUES, $bouygues);


        $manager->flush();
    }
}
