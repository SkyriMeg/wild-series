<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\ProgramType;
use App\Repository\CategoryRepository;
use App\Repository\EpisodeRepository;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('program/new', name: 'program_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProgramRepository $programRepository): Response
    {
        // Create a new program Object
        $program = new Program();
        // Create the associated Form
        $form = $this->createForm(ProgramType::class, $program);
        // Get data from HTTP request
        $form->handleRequest($request);
        // Was the form submitted ?
        if ($form->isSubmitted() && $form->isValid()) {
            // Deal with the submitted data
            // For example : persiste & flush the entity
            // And redirect to a route that display the result
            $programRepository->save($program, true);
            // Once the form is submitted, valid and the data inserted in database, you can define the success flash message
            $this->addFlash('success', 'La série a bien été ajoutée !');
            return $this->redirectToRoute('program_index');
        }
        // Render the form
        return $this->renderForm('program/new.html.twig', [
            'form' => $form,
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

    #[Route('/{id}/edit', name: 'program_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Program $program, ProgramRepository $programRepository): Response
    {
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $programRepository->save($program, true);
            $this->addFlash('success', 'La série a bien été modifiée !');
            return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('program/edit.html.twig', [
            'program' => $program,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'program_delete', methods: ['POST'])]
    public function delete(Request $request, Program $program, ProgramRepository $programRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $program->getId(), $request->request->get('_token'))) {
            $this->addFlash('danger', "La série a bien été supprimée !");
            $programRepository->remove($program, true);
        }

        return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
    }
}
