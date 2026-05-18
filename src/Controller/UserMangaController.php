<?php

namespace App\Controller;

use App\Entity\UserManga;
use App\Repository\UserMangaRepository;
use App\Repository\MangaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/library')]
class UserMangaController extends AbstractController
{
    #[Route('/', name: 'app_library_index')]
    public function index(UserMangaRepository $userMangaRepository): Response
    {
        $userMangas = $userMangaRepository->findBy(['user' => $this->getUser()]);

        return $this->render('library/index.html.twig', [
            'userMangas' => $userMangas,
        ]);
    }

    #[Route('/add/{id}', name: 'app_library_add')]
    public function add(int $id, MangaRepository $mangaRepository, UserMangaRepository $userMangaRepository, EntityManagerInterface $em): Response
    {
        $manga = $mangaRepository->find($id);

        if (!$manga) {
            throw $this->createNotFoundException('Manga not found');
        }

        // Verificar que no lo tenga ya en su biblioteca
        $existing = $userMangaRepository->findOneBy([
            'user' => $this->getUser(),
            'manga' => $manga,
        ]);

        if (!$existing) {
            $userManga = new UserManga();
            $userManga->setUser($this->getUser());
            $userManga->setManga($manga);
            $userManga->setReadingStatus('to_read');
            $userManga->setAddedAt(new \DateTimeImmutable());

            $em->persist($userManga);
            $em->flush();

            $this->addFlash('success', 'Manga ajouté à votre bibliothèque !');
        } else {
            $this->addFlash('warning', 'Ce manga est déjà dans votre bibliothèque.');
        }

        return $this->redirectToRoute('app_manga_show', ['id' => $id]);
    }

    #[Route('/status/{id}', name: 'app_library_status', methods: ['POST'])]
    public function updateStatus(UserManga $userManga, Request $request, EntityManagerInterface $em): Response
    {
        if ($userManga->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $status = $request->request->get('status');
        $allowed = ['to_read', 'reading', 'completed'];

        if (in_array($status, $allowed)) {
            $userManga->setReadingStatus($status);
            $em->flush();
            $this->addFlash('success', 'Statut mis à jour !');
        }

        return $this->redirectToRoute('app_library_index');
    }

    #[Route('/remove/{id}', name: 'app_library_remove', methods: ['POST'])]
    public function remove(UserManga $userManga, Request $request, EntityManagerInterface $em): Response
    {
        if ($userManga->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $em->remove($userManga);
        $em->flush();
        $this->addFlash('success', 'Manga retiré de votre bibliothèque.');

        return $this->redirectToRoute('app_library_index');
    }
}