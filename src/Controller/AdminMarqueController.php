<?php

namespace App\Controller;

use App\Entity\Marque;
use App\Form\MarqueType;
use App\Repository\MarqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



#[Route('/admin/marque')]

class AdminMarqueController extends AbstractController
{
    #[Route('/', name: 'admin_marque_index', methods: ['GET'])]
    public function index(MarqueRepository $marqueRepository): Response
    {
        return $this->render('admin_marque/index.html.twig', [
            'marques' => $marqueRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_marque_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $marque = new Marque();
        $form = $this->createForm(MarqueType::class, $marque);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($marque);
            $entityManager->flush();

            return $this->redirectToRoute('admin_marque_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_marque/new.html.twig', [
            'marque' => $marque,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_marque_show', methods: ['GET'])]
    public function show(Marque $marque): Response
    {
        return $this->render('admin_marque/show.html.twig', [
            'marque' => $marque,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_marque_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Marque $marque): Response
    {
        $form = $this->createForm(MarqueType::class, $marque);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_marque_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_marque/edit.html.twig', [
            'marque' => $marque,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_marque_delete', methods: ['POST'])]
    public function delete(Request $request, Marque $marque): Response
    {
        if ($this->isCsrfTokenValid('delete'.$marque->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($marque);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_marque_index', [], Response::HTTP_SEE_OTHER);
    }
}
