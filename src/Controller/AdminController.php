<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/back_office', name: 'back_office')]
    public function back_office()
    {
        return $this->render('admin/back_office.html.twig');
    }
}
