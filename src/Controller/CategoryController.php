<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Repository\ProgramRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CategoryController extends AbstractController
{
    #[Route('/category/', name: 'category_index')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        return $this->render('category/index.html.twig', [
            'website' => 'Wild Series',
            'categories' => $categories,
        ]);
    }

    #[Route('category/new', name: 'category_new')]
    public function new(Request $request, CategoryRepository $categoryRepository): Response
    {
        // Create a new Category Object
        $category = new Category();
        // Create the associated Form
        $form = $this->createForm(CategoryType::class, $category);
        // Get data from HTTP request
        $form->handleRequest($request);
        // Was the form submitted ?
        if ($form->isSubmitted() && $form->isValid()) {
            // Deal with the submitted data
            // For example : persiste & flush the entity
            // And redirect to a route that display the result
            $categoryRepository->save($category, true);
            return $this->redirectToRoute('category_index');
        }

        // Render the form
        return $this->renderForm('category/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/category/{categoryName}', name: 'category_show')]
    public function show(string $categoryName, CategoryRepository $categoryRepository, ProgramRepository $programRepository): Response
    {
        $category = $categoryRepository->findOneBy(['name' => $categoryName]);

        $programs = $programRepository->findBy(['category' => $category], ['id' => 'DESC'], 3);

        if (!$category) {
            throw $this->createNotFoundException(
                "Aucune catégorie trouvée !"
            );
        } else {
            return $this->render('category/show.html.twig', [
                'categories' => $category,
                'programs' => $programs,
            ]);
        }
    }
}
