<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    public static int $actorIndex = 0;
    public const NBACTORS = 10;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        /**
         * L'objet $faker que tu récupère est l'outil qui va te permettre 
         * de te générer toutes les données que tu souhaites
         */
        for ($i = 0; $i < 10; $i++) {
            $actor = new Actor();
            $actor->setName($faker->name());
            $programNumber = [];
            for ($k = 0; $k < ProgramFixtures::NBPROGRAMS; $k++) {
                $programNumber[] = $k;
            }
            $programSelectors = array_rand($programNumber, 3);
            foreach ($programSelectors as $programSelector) {
                $actor->addProgram($this->getReference('program_' . $programSelector));
            }
            $manager->persist($actor);

            $manager->flush();
        }
    }

    public function getDependencies(): array
    {
        return [
            ProgramFixtures::class,
        ];
    }
}
