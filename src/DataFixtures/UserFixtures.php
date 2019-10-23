<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 20; $i++)
        {
            $user = new User();
            $user->setFirstname($faker->firstName);
            $user->setLastname($faker->lastName);
            $user->setEmail($faker->email);
            $user->setEnterprise($this->getReference("enterprise".mt_rand('0','14')));
            $manager->persist($user);
            $this->addReference('user'.$i, $user);
            $manager->flush($user);
        }
    }

    public function getDependencies()
    {
        return array(
            EnterpriseFixtures::class,
        );
    }
}
