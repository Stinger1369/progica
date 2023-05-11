<?php

namespace App\Controller;

use App\Entity\Gite;
use App\Entity\User;
use App\Repository\GiteRepository;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(GiteRepository $giteRepository): Response
    {

        $gite = $giteRepository->findAll();
        $gitesBy = $giteRepository->findBy(array('region' => 'Haute-Garonne'));

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'gites' => $gite,
            'gitesBy' => $gitesBy,
        ]);
    }
}
