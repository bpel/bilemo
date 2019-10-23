<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class BrandFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $listBrand = ['apple', 'blackberry', 'google','htc','huawei','lg','oneplus','razer','samsung','sony','xiaomi'];

        for ($i = 0; $i < count($listBrand); $i++)
        {
            $brand = new Brand();
            $brand->setNameBrand($listBrand[$i]);
            $manager->persist($brand);
            $this->addReference('brand'.$i, $brand);
        }

        $manager->flush();
    }
}
