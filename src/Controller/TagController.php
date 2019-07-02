<?php
namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\TagRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tag")
 * Class TagController
 * @package App\Controller
 */
class TagController extends AbstractController
{

    /**
     * @Route("/liste", name="app_tag_liste")
     * @param TagRepository $repository
     * @return Response
     */
    public function liste(TagRepository $repository): Response
    {
        $tags = $repository->findAll();

        return $this->render('tag/liste.html.twig', [
            'tags' => $tags
        ]);
    }

    /**
     * @Route("/create", name="app_tag_create")
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function create(Request $request, ObjectManager $manager): Response
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($tag);
            $manager->flush();

            $this->addFlash('success', 'Vous avez bien créé votre tag');
            return $this->redirectToRoute('app_tag_liste');
        }

        return $this->render('tag/create.html.twig', [
            'TagForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/update/{id<[0-9]+>}", name="app_tag_update")
     * @param Request $request
     * @param ObjectManager $manager
     * @param Tag $tag
     * @return Response
     */
    public function update(Request $request, ObjectManager $manager, Tag $tag): Response
    {
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();

            $this->addFlash('warning', 'Vous avez bien modifié un tag');
            return $this->redirectToRoute('app_tag_liste');
        }

        return $this->render('tag/update.html.twig', [
            'TagForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="app_tag_delete")
     * @param Request $request
     * @param ObjectManager $manager
     * @param Tag $tag
     * @return Response
     */
    public function delete(Request $request, ObjectManager $manager, Tag $tag): Response
    {
        $manager->remove($tag);
        $manager->flush();
        $this->addFlash('danger', 'Vous avez bien supprimé un tag');
        return $this->redirectToRoute('app_tag_liste');
    }
}
