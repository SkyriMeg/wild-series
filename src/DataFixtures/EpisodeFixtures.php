<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public static int $episodeIndex = 0;
    public const NBEPISODES = 10;

    public function load(ObjectManager $manager): void
    {
        //Puis ici nous demandons à la Factory de nous fournir un Faker
        $faker = Factory::create();

        /**
         * L'objet $faker que tu récupère est l'outil qui va te permettre 
         * de te générer toutes les données que tu souhaites
         */

        for ($i = 0; $i < SeasonFixtures::NBSEASONS; $i++) {

            for ($e = 0; $e < self::NBEPISODES; $e++) {
                $episode = new Episode();
                //Ce Faker va nous permettre d'alimenter l'instance de Episode que l'on souhaite ajouter en base
                $episode->setNumber($e + 1);
                $episode->setTitle($faker->words(3, true));
                $episode->setSynopsis($faker->paragraphs(3, true));

                $episode->setSeason($this->getReference('season_' . $i));

                $manager->persist($episode);
            }
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            SeasonFixtures::class,
        ];
    }
}
