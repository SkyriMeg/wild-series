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
    public function show(Program $program): Response
    {
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
    public function showSeason(Program $programId, Season $seasonId): Response
    {
        if (!$programId || !$seasonId) {
            throw $this->createNotFoundException(
                'Aucune série ou saison trouvée.'
            );
        }

        return $this->render('program/season_show.html.twig', [
            'program' => $programId,
            'season' => $seasonId,
        ]);
    }

    #[Route('/program/{programId}/seasons/{seasonId}/episodes/{episodesId}', requirements: ['programId' => '\d+', 'seasonId' => '\d+', 'episodeId' => '\d+'], methods: ['GET'], name: 'program_episode_show')]
    public function showEpisode(Program $programId, Season $seasonId, Episode $episodesId)
    {
        if (!$episodesId) {
            throw $this->createNotFoundException(
                "Aucun épisode trouvé !"
            );
        } else {
            return $this->render('program/episode_show.html.twig', [
                'program' => $programId,
                'season' => $seasonId,
                'episodes' => $episodesId,
            ]);
        }
    }
}
