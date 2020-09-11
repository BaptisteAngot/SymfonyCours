<?php

namespace App\DataFixtures;

use App\Entity\Departements;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $normandie =  new Departements();
        $imgNormandie = new Image();

        $normandie->setName("Normandie")
            ->setSuperficie(45645)
            ->setPopulation(45645)
            ->setDensite(55)
            ->setNumero(76)
            ->setDescriptions('Jolie description');
        $manager->persist($normandie);
        $imgNormandie->setUrl('https://www.photomaville.com/wp-content/uploads/img-tourisme-et-bien-etre-en-normandie-pour-les-velocyclistes.jpg')
            ->setCaption('Image tourisme velocycliste')
            ->setDepartement($normandie);
        $manager->persist($imgNormandie);
        // create 1 departements !
        for ($i = 0; $i < 20; $i++) {
            $faker = Faker\Factory::create('FR-fr');
            $departement = new Departements();
            $departement->setName($faker->state)
            ->setNumero($faker->postcode)
            ->setPopulation($faker->numberBetween(10000,20000))
            ->setDensite($faker->numberBetween(10,500))
            ->setSuperficie($faker->numberBetween(25000,50000))
            ->setDescriptions($faker->realText(200));
            $manager->persist($departement);

            $image = new Image();
            $image->setUrl($faker->imageUrl(640,480,'city'))
                ->setCaption($faker->realText(50))
                ->setDepartement($departement);
            $manager->persist($image);
        }

        $manager->flush();
    }
}
