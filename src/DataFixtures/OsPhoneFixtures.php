<?php

namespace App\DataFixtures;

use App\Entity\OsPhone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class OsPhoneFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $listPhoneOs = ['android', 'ios', 'windows phone','blackberry','harmony','oxygen','miui'];

        for ($i = 0; $i < count($listPhoneOs); $i++)
        {
            $osPhone = new OsPhone();
            $osPhone->setNameOs($listPhoneOs[$i]);
            $manager->persist($osPhone);
            $this->addReference('osphone'.$i, $osPhone);
        }

        $manager->flush();
    }
}
