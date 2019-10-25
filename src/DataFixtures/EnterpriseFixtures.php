<?php

namespace App\DataFixtures;

use App\Entity\Enterprise;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class EnterpriseFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $listEnterprise = ['backmarket', 'cdiscount', 'darty','grosbill','rakuten','ariase','auchan'
            ,'boulanger','ubaldi','mobile24','leclerc','ldlc','mkcom','prixtel', 'bonial'];

        for ($i = 0; $i < count($listEnterprise); $i++)
        {
            $enterprise = new Enterprise();
            $enterprise->setNameEnterprise($listEnterprise[$i]);
            $manager->persist($enterprise);
            $this->addReference('enterprise'.$i, $enterprise);
            $manager->flush($enterprise);
        }
    }
}
