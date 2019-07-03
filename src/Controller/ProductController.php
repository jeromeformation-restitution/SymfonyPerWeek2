<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\Common\Persistence\ObjectManager;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/admin/produits/formulaire")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request, ObjectManager $entityManager): Response
    {
        $product=new Product();
        /*$product->setName('ventilo')
            ->setDescription('msfkldlodsjumgfildjgisfdugiodfhgiorhdsgio')
            ->setImageName('ventilateur.jpg')
            ->setIsPublished(true)
            ->setPrice(12.00)
            ->setCategory($category);
        */
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
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
}
