<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\ProductType;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Types\DateImmutableType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminProductController extends AbstractController
{
    #[Route('/admin/product', name: 'app_admin_product')]
    public function index(ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        $products = $productRepository->findAll();
        $categories = $categoryRepository->findAll();

        return $this->render('admin_product/index.html.twig', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    #[Route('/admin/product/add', name: 'app_admin_product_add')]
    public function add(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // ENREGISTREMENT EN BDD

            $title = $product->getTitle();
            $slug = strtolower(str_replace(' ', '-', $title));
            $product->setSlug($slug);
            $createdAt = new \DateTimeImmutable();
            $product->setCreatedAt($createdAt);

            $entityManagerInterface->persist($product);
            $entityManagerInterface->flush();


            return $this->redirectToRoute('app_admin_product');
        }

        return $this->render('admin_product/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/category/add', name: 'app_admin_category_add')]
    public function addCategory(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManagerInterface->persist($category);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('app_admin_product');
        }

        return $this->render('admin_product/add-category.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/product/{id}/edit', name: 'app_admin_product_edit')]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManagerInterface): Response
    {
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManagerInterface->flush();

            return $this->redirectToRoute('app_admin_product');
        }

        return $this->render('admin_product/edit.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
        ]);
    }

    #[Route('/admin/category/{id}/edit', name: 'app_admin_category_edit')]
    public function editCat(Request $request, Category $category, EntityManagerInterface $entityManagerInterface): Response
    {
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManagerInterface->flush();

            return $this->redirectToRoute('app_admin_product');
        }

        return $this->render('admin_product/edit-category.html.twig', [
            'form' => $form->createView(),
            'category' => $category,
        ]);
    }
}
