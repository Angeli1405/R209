<?php

namespace App\Controller;

use App\Entity\Catergory;
use App\Form\CatergoryType;
use App\Repository\CatergoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/catergory')]
class CatergoryController extends AbstractController
{
    #[Route('/', name: 'app_catergory_index', methods: ['GET'])]
    public function index(CatergoryRepository $catergoryRepository): Response
    {
        return $this->render('catergory/index.html.twig', [
            'catergories' => $catergoryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_catergory_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $catergory = new Catergory();
        $form = $this->createForm(CatergoryType::class, $catergory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($catergory);
            $entityManager->flush();

            return $this->redirectToRoute('app_catergory_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('catergory/new.html.twig', [
            'catergory' => $catergory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_catergory_show', methods: ['GET'])]
    public function show(Catergory $catergory): Response
    {
        return $this->render('catergory/show.html.twig', [
            'catergory' => $catergory,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_catergory_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Catergory $catergory, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CatergoryType::class, $catergory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_catergory_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('catergory/edit.html.twig', [
            'catergory' => $catergory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_catergory_delete', methods: ['POST'])]
    public function delete(Request $request, Catergory $catergory, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$catergory->getId(), $request->request->get('_token'))) {
            $entityManager->remove($catergory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_catergory_index', [], Response::HTTP_SEE_OTHER);
    }
}
