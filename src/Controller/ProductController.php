<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

#[Route('/product')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'app_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }
    #[Route('/front', name: 'app_product_indexFront', methods: ['GET'])]
    public function indexfront(ProductRepository $productRepository): Response
    {
        return $this->render('product/indexFront.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($product);
            $entityManager->flush();

            $email = (new Email())
                ->from('benaissarihem036@gmail.com')
                ->to('aziz.talbi@esprit.tn')
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('Nouveau Produit Ajouté')
                ->text('!!')    
                ->html('<p>Le produit ' . $product->getNom() . ' est ajouté avec succès</p>');

            $mailer->send($email);
            

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }
    #[Route('/front/{id}', name: 'app_product_showFront', methods: ['GET'])]
    public function showFront(Product $product): Response
    {
        return $this->render('product/showFront.html.twig', [
            'product' => $product,
        ]);
    }
    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/product/search', name: 'search_product')]
    public function searchProduit(Request $request, ProductRepository $productRepository): Response
    {
        $query = $request->request->get('query');
        $products = $productRepository->searchByType($query);
        return $this->render('product/search.html.twig', [
            'products' => $products
        ]);
    }

    #[Route('/product/tricroi', name: 'tricroissant', methods: ['GET','POST'])]
    public function triCroissant(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAllSortedByPriceASC();

        return $this->render('product/indexFront.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/product/tridecroi', name: 'tridecroissant', methods: ['GET','POST'])]
    public function triDecroissant(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAllSortedByPriceDESC();

        return $this->render('product/indexFront.html.twig', [
            'products' => $products,
        ]);
    }


}
