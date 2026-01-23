<?php

namespace App\DataFixtures;

use App\Entity\Country;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Intl\Countries;

class CountryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach (Countries::getNames('fr') as $isoCode => $name) {
            $country = new Country();
            $country->setIsoCode($isoCode);
            $country->setName($name);

            $manager->persist($country);
        }

        $manager->flush();
    }
}
