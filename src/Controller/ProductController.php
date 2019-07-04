<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class ProductController extends AbstractController
{
    /**
     * Affichage des produits qu'on récupère de la BDD
     * @return Response
     */
    public function liste(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Product::class);
        $products = $repository->findBy([
            'isPublished'=>true
        ]);
        return $this->render('produit/liste.html.twig', ['products' => $products]);
    }

    /**
     * @Route("/produits/gestion/new")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request, ObjectManager $entityManager, UserInterface $user): Response
    {

        $product=new Product();
        /*$product->setName('ventilo')
            ->setDescription('msfkldlodsjumgfildjgisfdugiodfhgiorhdsgio')
            ->setImageName('ventilateur.jpg')
            ->setIsPublished(true)
            ->setPrice(12.00)
            ->setproduct($product);
        */

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $product->setPublisher($user);
            $entityManager->persist($product);
            $entityManager->flush();
            return $this->redirectToRoute('app_produit_liste');
        }
        return $this->render('produit/formulaire.html.twig', [
            'produit' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param ObjectManager $manager
     * @param Product $product
     * @Route("/produits/gestion/edit/{slug}")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function edit(Request $request, ObjectManager $manager, Product $product, UserInterface $user)
    {
        if ($product->getPublisher()!== $user && !$this->isGranted("ROLE_ADMIN")) {
            throw $this->createAccessDeniedException("Vous n'etes pas autorisé a effectuer cette action");
        }

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($product);
            $manager->flush();
            return $this->redirectToRoute('app_produit_liste');
        }
        return $this->render('produit/edit.html.twig', [
            'produit' => $product,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route(
     *     "/produit/{slug}",
     *     name= "produits",
     *     requirements={"slug"="[a-z0-9\-]+"},
     *     methods={"GET"}
     *     )
     * @param string $slug
     * @return Response
     */
    public function show(string $slug): Response
    {

        /* throw new Exception('blabla');*/
        $repository = $this->getDoctrine()->getRepository(Product::class);
        $products = $repository->findOneBy(["slug" => $slug,'isPublished'=>true]);
        if (!$products) {
            throw $this->createNotFoundException('erreur 404 sur la page, produit not found');
        }
        return $this->render('produit/show.html.twig', ["product" => $products]);
    }


    /**
     * @Route("/produits/supprimer/{slug}", name="product_delete", methods={"DELETE"})
     * @param Request $request
     * @param Product $product
     * @return Response
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_produit_liste');
    }
}
