<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 20; $i++)
        {
            $user = new User();
            $user->setFirstname($faker->firstName);
            $user->setLastname($faker->lastName);
            $user->setEmail($faker->email);

            $plainPassword = $faker->password;
            $encoded = $this->encoder->encodePassword($user, $plainPassword);
            $user->setPassword($encoded);

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
