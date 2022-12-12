<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public static int $episodeIndex = 0;
    public const NBEPISODES = 10;

    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        //Puis ici nous demandons à la Factory de nous fournir un Faker
        $faker = Factory::create();

        /**
         * L'objet $faker que tu récupère est l'outil qui va te permettre 
         * de te générer toutes les données que tu souhaites
         */

        for ($i = 0; $i < SeasonFixtures::$seasonIndex; $i++) {

            for ($e = 0; $e < self::NBEPISODES; $e++) {
                $episode = new Episode();
                //Ce Faker va nous permettre d'alimenter l'instance de Episode que l'on souhaite ajouter en base
                $episode->setNumber($e + 1);
                $episode->setTitle($faker->words(3, true));
                $episode->setSynopsis($faker->paragraphs(3, true));
                $episode->setDuration($faker->numberBetween(25, 60));

                $episode->setSeason($this->getReference('season_' . $i));
                $episode->setSlug($this->slugger->slug($episode->getTitle()));
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
