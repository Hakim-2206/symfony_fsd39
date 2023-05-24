<?php

namespace App\DataFixtures;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Product;
use App\Entity\Category;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Générer les catégories + produits
        $categories = [];
        for ($i = 0; $i < 10; $i++) {
            $category = new Category();
            $category->setName($faker->word());
            $manager->persist($category);
            $categories[] = $category;
        }

        for ($i = 0; $i < 10; $i++) {
            $product = new Product();
            $product->setTitle($faker->word());
            $product->setDescription($faker->paragraph(2));
            $product->setPrice($faker->randomFloat(2, 0, 100));
            $product->setPicture($faker->imageUrl(200, 200));
            $slug = strtolower(str_replace(' ', '', $product->getTitle()));
            $product->setSlug($slug);
            $createdAt = new \DateTimeImmutable();
            $product->setCreatedAt($createdAt);

            $product->setCategory($faker->randomElement($categories));

            $manager->persist($product);
        }

        $manager->flush();
    }
}
