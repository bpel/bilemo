<?php

namespace App\DataFixtures;

use App\Entity\Phone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class PhoneFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $listPhone = ['Apple iPhone 11 Pro', 'BlackBerry KEY2', 'Google Pixel 3','Google Pixel 3 XL','HTC U12 Plus','Huawei P30 Pro','Huawei P30'
        ,'Huawei Mate 20','Huawei Mate 20 Pro','LG G7 ThinQ','OnePlus 7 Pro','OnePlus 6T','Razer Phone 2','Samsung Galaxy Note 10 Plus',
            'Samsung Galaxy Note 10','Samsung Galaxy S10','Samsung Galaxy S10 Plus','Samsung Galaxy S9','Sony XZ3','Xiaomi Mi Mix 3','Xiaomi Mi 9'];

        $listColour = ['black', 'white', 'gray', 'silver', 'red', 'gold', 'iron', 'green', 'blue', 'orange'];

        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < count($listPhone); $i++)
        {
            $phone = new Phone();
            $phone->setNamephone($listPhone[$i]);
            $phone->setColour($listColour[mt_rand('0','9')]);
            $phone->setGoStorage($faker->randomElement(array ('32','64','128','512','1000')));
            $phone->setPrice($faker->randomFloat(2,390,2300));
            $phone->setOs($this->getReference("osphone".mt_rand('0','6')));
            $phone->setBrand($this->getReference("brand".mt_rand('0','10')));
            $manager->persist($phone);
            $this->addReference('phone'.$i, $phone);
            $manager->flush($phone);
        }
    }

    public function getDependencies()
    {
        return array(
            BrandFixtures::class,
            OsPhoneFixtures::class,
        );
    }
}
