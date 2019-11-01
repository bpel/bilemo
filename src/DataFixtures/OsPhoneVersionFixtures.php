<?php

namespace App\DataFixtures;

use App\Entity\OsPhoneVersion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class OsPhoneVersionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $listPhoneOsVersion = ['gingerbread', 'ice cream sandwich', 'jelly bean', 'kitkat', 'lollipop'
        ,'ios 11', 'ios 10', 'ios 9', 'apollo', 'blue', 'threshold', 'blackberry 10', 'emui 10', 'miui v10'];

        for ($i = 0; $i < count($listPhoneOsVersion); $i++)
        {
            $osPhone = new OsPhoneVersion();
            $osPhone->setOs($this->getReference("osphone".mt_rand('0','5')));
            $osPhone->setVersion($listPhoneOsVersion[$i]);
            $manager->persist($osPhone);
            $this->addReference('osphoneversion'.$i, $osPhone);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            OsPhoneFixtures::class,
        );
    }
}
