<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Repository\CategoryRepository;
use App\Repository\EpisodeRepository;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ProgramController extends AbstractController
{
    #[Route('/program/', name: 'program_index')]
    public function index(ProgramRepository $programRepository, CategoryRepository $categoryRepository): Response
    {
        $programs = $programRepository->findAll();
        $categories = $categoryRepository->findAll();

        if (!$programs) {
            throw $this->createNotFoundException(
                'Aucune série trouvée.'
            );
        }
        return $this->render('program/index.html.twig', [
            'website' => 'Wild Series',
            'programs' => $programs,
            'categories' => $categories,
        ]);
    }


    #[Route('/program/{id}', methods: ['GET'], requirements: ['id' => '\d+'], name: 'program_show')]
    public function show(int $id, ProgramRepository $programRepository): Response
    {
        $program = $programRepository->findOneBy(['id' => $id]);
        if (!$program) {
            throw $this->createNotFoundException(
                "Aucune série trouvée !"
            );
        } else {
            return $this->render('program/show.html.twig', [
                'program' => $program,
            ]);
        }
    }

    #[Route('/program/{programId}/seasons/{seasonId}', requirements: ['programId' => '\d+', 'seasonId' => '\d+'], methods: ['GET'], name: 'program_season_show')]
    public function showSeason(int $programId, int $seasonId, ProgramRepository $programRepository, SeasonRepository $seasonRepository): Response
    {
        $program = $programRepository->findOneBy(['id' => $programId]);
        $season = $seasonRepository->findOneBy(['id' => $seasonId]);

        if (!$program || !$season) {
            throw $this->createNotFoundException(
                'Aucune série ou saison trouvée.'
            );
        }

        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
        ]);
    }

    #[Route('/program/{programId}/seasons/{seasonId}/episodes/{episodesId}', requirements: ['programId' => '\d+', 'seasonId' => '\d+', 'episodeId' => '\d+'], methods: ['GET'], name: 'program_episode_show')]
    public function showEpisode(int $programId, int $seasonId, int $episodesId, ProgramRepository $programRepository, SeasonRepository $seasonRepository, EpisodeRepository $episodeRepository)
    {
        $program = $programRepository->findOneBy(['id' => $programId]);
        $season = $seasonRepository->findOneBy(['id' => $seasonId]);
        $episodes = $episodeRepository->findOneBy(['id' => $episodesId]);

        if (!$episodes) {
            throw $this->createNotFoundException(
                "Aucun épisode trouvé !"
            );
        } else {
            return $this->render('program/episode_show.html.twig', [
                'program' => $program,
                'season' => $season,
                'episodes' => $episodes,
            ]);
        }
    }
}
