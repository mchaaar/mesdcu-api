<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProductService
{
    private EntityManagerInterface $entityManager;
    private ProductRepository $productRepository;

    public function __construct(EntityManagerInterface $entityManager, ProductRepository $productRepository)
    {
        $this->entityManager = $entityManager;
        $this->productRepository = $productRepository;
    }

    public function createProduct(string $name, string $description, float $price, int $stock, string $imageUrl, bool $isActive): Product
    {
        $product = new Product();
        $product->setName($name);
        $product->setDescription($description);
        $product->setPrice($price);
        $product->setStock($stock);
        $product->setImageUrl($imageUrl);
        $product->setIsActive($isActive);

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $product;
    }

    public function deleteProduct(int $id): bool
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            return false;
        }

        $this->entityManager->remove($product);
        $this->entityManager->flush();

        return true;
    }

    public function getProductById(int $id): ?Product
    {
        return $this->productRepository->find($id);
    }
}
