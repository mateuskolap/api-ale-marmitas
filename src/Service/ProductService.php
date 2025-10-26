<?php

namespace App\Service;

use App\DTO\Input\Product\ProductCreateInput;
use App\DTO\Input\Product\ProductUpdateInput;
use App\DTO\Output\PaginatedList;
use App\DTO\Output\Product\ProductOutput;
use App\Entity\Product;
use App\Enum\ProductCategory;
use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;

readonly class ProductService
{
    public function __construct(
        private ProductRepository  $productRepository,
        private PaginatorInterface $paginator,
    )
    {
    }

    /**
     * Find all products with pagination
     *
     * @param int $page
     * @param int $size
     * @return PaginatedList
     */
    public function findAllPaginated(int $page, int $size): PaginatedList
    {
        $pagination = $this->paginator->paginate(
            $this->productRepository->findAllQuery(),
            $page,
            $size
        );

        $pagination->setItems(array_map(
            fn(Product $product) => new ProductOutput($product),
            $pagination->getItems()
        ));

        return new PaginatedList($pagination);
    }

    /**
     * Create a new product
     *
     * @param ProductCreateInput $input
     * @return ProductOutput
     */
    public function create(ProductCreateInput $input): ProductOutput
    {
        $product = (new Product())
            ->setName($input->name)
            ->setPrice($input->price)
            ->setCategory(ProductCategory::from($input->category));

        $this->productRepository->save($product, true);

        return new ProductOutput($product);
    }

    public function update(ProductUpdateInput $input, Product $product): ProductOutput
    {
        if ($input->name) {
            $product->setName($input->name);
        }

        if ($input->price) {
            $product->setPrice($input->price);
        }

        if ($input->category) {
            $product->setCategory(ProductCategory::from($input->category));
        }

        $this->productRepository->save($product, true);

        return new ProductOutput($product);
    }

    public function delete(Product $product): void
    {
        $this->productRepository->delete($product, true);
    }
}
