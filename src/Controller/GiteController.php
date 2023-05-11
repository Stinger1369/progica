<?php

namespace App\Controller;

use App\Entity\Gite;
use App\Entity\GiteServiceEquipement;
use App\Entity\Services;
use App\Entity\User;
use App\Form\GiteType;
use App\Repository\GiteRepository;
use App\Repository\UserRepository;
use ContainerB6WSVan\getGiteServiceEquipementRepositoryService;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use function mysql_xdevapi\getSession;


#[Route('/gite')]
class GiteController extends AbstractController
{
    #[Route('/index', name: 'app_gite_index', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function index(GiteRepository $giteRepository): Response
    {
        return $this->render('gite/index.html.twig', [
            'gites' => $giteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_gite_new', methods: ['GET', 'POST'])]
    public function new(Request $request, GiteRepository $giteRepository, UserInterface $user,SluggerInterface $slugger): Response
    {
        $gite = new Gite();
        $gite->setUser($user);
        $form = $this->createForm(GiteType::class, $gite);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $photosFile = $form->get('photos')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($photosFile) {
                $originalFilename = pathinfo($photosFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $photosFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $photosFile->move(
                        $this->getParameter('picture_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $gite->setPhotos($newFilename);
            }

            $giteRepository->save($gite, true);

                return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);



        }

        return $this->renderForm('gite/new.html.twig', [
            'gite' => $gite,
            'form' => $form,
        ]);
    }

        #[Route('/{id}', name: 'app_gite_show', methods: ['GET'])]
    public function show(Gite $gite): Response
    {
        return $this->render('gite/show.html.twig', [
            'gite' => $gite,
        ]);
    }

        #[Route('/{id}/details', name: 'app_gite_details', methods: ['GET'])]
    public function gite_details(Gite $gite): Response
    {
        return $this->render('gite/gite_details.html.twig', [
            'gite' => $gite,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_gite_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Gite $gite, GiteRepository $giteRepository): Response
    {
        $form = $this->createForm(GiteType::class, $gite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $giteRepository->save($gite, true);

            return $this->redirectToRoute('app_gite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gite/edit.html.twig', [
            'gite' => $gite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_gite_delete', methods: ['POST'])]
    public function delete(Request $request, Gite $gite, GiteRepository $giteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $gite->getId(), $request->request->get('_token'))) {
            $giteRepository->remove($gite, true);
        }

        return $this->redirectToRoute('app_gite_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/user/index', name: 'app_gite_user_index', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function indexByUserId(GiteRepository $giteRepository, User $user): Response
    {
        $user_id = $user->getId();
        return $this->render('gite/gite_index_user.html.twig', [
            'gites' => $giteRepository->findAllByUserId($user_id),
        ]);
    }
}
