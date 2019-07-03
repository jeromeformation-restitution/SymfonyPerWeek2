<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route ("/admin")
 * Class AdminController
 * @package App\Controller
 */
class AdminController extends AbstractController
{

    /**
     * @Route("/listuser", name="admin_listUser")
     */
    public function listUser(UserRepository $userRepository):Response
    {
        return $this->render('admin/index.html.twig', [
            'users' => $userRepository->findAll()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="admin_changeRole")
     * @param ObjectManager $manager
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function changeRole(ObjectManager $manager, Request $request, User $user): Response
    {
        $form = $this->createForm('App\Form\ChangeRoleType', $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', 'Le role de l\'utilisateur a bien été modifié');
            return $this->redirectToRoute('admin_listUser');
        }
        return $this->render('admin/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
