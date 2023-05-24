<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Environment;

class FrontController extends AbstractController
{
    #[Route('/', name: 'app_front')]
    public function index(Request $request, ProductRepository $productRepository): Response
    {

        // $date = new \DateTime();
        // dump($date);
        // dump($request);
        // dump($request->query->get('id', null));

        $products = $productRepository->findAll();

        return $this->render('front/index.html.twig', [
            'products' => $products,
        ]);
    }


    #[Route('/product/{slug}', name: 'app_product_detail')]
    public function productDetail(Product $product): Response
    {
        if ($product == null)
            throw new NotFoundHttpException();

        return $this->render('front/product_detail.html.twig', [
            'product' => $product,
        ]);
    }


    #[Route('/category', name: 'app_product_category')]
    public function categoryProduct(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('front/product_category.html.twig', [
            'categories' => $categories,
        ]);
    }


    #[Route('/category/{name}', name: 'app_category_name')]
    public function categoryName(Category $category, ProductRepository $productRepository): Response
    {
        if ($category == null)
            throw new NotFoundHttpException();

        $products = $productRepository->findByCategory($category);

        return $this->render('front/product_category_name.html.twig', [
            'category' => $category,
            'products' => $products,
        ]);
    }


    #[Route('pages/{page}', name: 'app_static_page', requirements: ['page' => '[a-z]+'])]
    public function staticPage(string $page, Environment $twig): Response
    {
        dump($page);
        $template = 'front/' . $page . '.html.twig';
        $loader = $twig->getLoader();
        if (!$loader->exists($template))
            throw new NotFoundHttpException();

        return $this->render($template, []);
    }
}
