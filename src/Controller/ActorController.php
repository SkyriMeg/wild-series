<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Entity\Program;
use App\Repository\ActorRepository;
use App\Repository\ProgramRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ActorController extends AbstractController
{
    #[Route('actor/', name: 'actor_index')]
    public function index(ActorRepository $actorRepository, ProgramRepository $programRepository): Response
    {
        return $this->render('actor/index.html.twig', [
            'actors' => $actorRepository->findAll(),
            'programs' => $programRepository->findAll(),
        ]);
    }

    #[Route('/actor/{id}', methods: ['GET'], requirements: ['id' => '\d+'], name: 'actor_show')]
    public function show(Actor $actor): Response
    {
        if (!$actor) {
            throw $this->createNotFoundException(
                "Aucun acteur trouvé !"
            );
        } else {
            return $this->render('actor/show.html.twig', [
                'actor' => $actor,
            ]);
        }
    }
}
