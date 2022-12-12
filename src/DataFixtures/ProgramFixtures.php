<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    const NBPROGRAMS = 9;
    const PROGRAMS = [
        [
            "title" => "Mercredi",
            "synopsis" => "Mercredi Adams enquête sur une série de meurtres dans sa ville.",
            "category" => "category_Fantastique",
        ],
        [
            "title" => "Dead To Me",
            "synopsis" => "Le mari de Jen est récemment décédé dans un délit de fuite et sa veuve 
        sardonique est déterminée à résoudre le crime. Judy, d'esprit libre et optimiste, 
        a récemment subi une perte tragique. Les deux dames se rencontrent dans un groupe de soutien.",
            "category" => "category_Drame",
        ],
        [
            "title" => "Les Nouvelles Aventures de Sabrina",
            "synopsis" => "Sabrina Spellman lutte avec sa double nature à la fois sorcière et mortelle alors qu'elle s'oppose aux forces du mal qui la menacent, elle, sa famille et le monde humain.",
            "category" => "category_Fantastique",
        ],
        [
            "title" => "Dix Pour Cent",
            "synopsis" => "Chaque jour, Mathias, Gabriel, et Andréa, agents dans l'agence artistique A.S.K., jonglent d'une situation délicate à une autre en défendant leur vision du métier, où la vie privée et la vie professionnelle se mêlent souvent.",
            "category" => "category_Comédie",
        ],
        [
            "title" => "Black Mirror",
            "synopsis" => "Chaque épisode a un casting différent, un décor différent et une réalité différente, mais ils traitent tous de la façon dont nous vivons maintenant, et de la façon dont nous pourrions vivre dans dix minutes si nous sommes maladroits.",
            "category" => "category_Science-fiction",
        ],
        [
            "title" => "Le Cabinet De Curiosités",
            "synopsis" => "Une anthologie sur la thématique de l'horreur créée par Guillermo del Toro. Le réalisateur plébiscité et primé aux Oscars Guillermo del Toro présente sa sélection d'histoires sinistres, toutes plus terrifiantes les unes que les autres.",
            "category" => "category_Horreur",
        ],
        [
            "title" => "Arcane",
            "synopsis" => "Au milieu du conflit entre les villes jumelles de Piltover et Zaun, deux soeurs se battent dans les camps opposés d'une guerre entre technologies magiques et convictions incompatibles.",
            "category" => "category_Animation",
        ],
        [
            "title" => "Alias",
            "synopsis" => "Sydney Bristow travaille comme espionne pour une branche secrète de la CIA, appelée SD 6. C'est du moins ce qu'elle croit, jusqu'au jour où le chef de son unité, Arvin Sloane, fait assassiner son fiancé, après qu'elle lui a avoué sa double vie.",
            "category" => "category_Action",
        ],
        [
            "title" => "Vikings",
            "synopsis" => "Scandinavie, à la fin du 8ème siècle. Ragnar Lodbrok, un jeune guerrier viking, est avide d'aventures et de nouvelles conquêtes. Lassé des pillages sur les terres de l'Est, il se met en tête d'explorer l'Ouest par la mer.",
            "category" => "category_Aventure",
        ],
    ];

    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public static int $programIndex = 0;

    public function load(ObjectManager $manager): void
    {
        foreach (self::PROGRAMS as $programDetails) {
            $program = new Program();
            $program->setTitle($programDetails['title']);
            $program->setSynopsis($programDetails['synopsis']);
            $program->setCategory($this->getReference($programDetails['category']));
            $program->setSlug($this->slugger->slug($program->getTitle()));
            $manager->persist($program);
            $this->addReference('program_' . self::$programIndex, $program);
            self::$programIndex++;
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
            CategoryFixtures::class,
        ];
    }
}
