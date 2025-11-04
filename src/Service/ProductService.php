<?php

namespace App\Service;

use App\DTO\Input\PaginationOptions;
use App\DTO\Input\Product\ProductCreateInput;
use App\DTO\Input\Product\ProductFilterInput;
use App\DTO\Input\Product\ProductUpdateInput;
use App\DTO\Output\Pagination\PaginatedList;
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
     * @param PaginationOptions $pagination
     * @param ProductFilterInput|null $filters
     * @return PaginatedList
     */
    public function findAllPaginated(PaginationOptions $pagination, ?ProductFilterInput $filters = null): PaginatedList
    {
        $pagination = $this->paginator->paginate(
            $this->productRepository->findFilteredQuery($filters),
            $pagination->page,
            $pagination->size
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

    /**
     * Update an existing product
     *
     * @param ProductUpdateInput $input
     * @param Product $product
     * @return ProductOutput
     */
    public function update(ProductUpdateInput $input, Product $product): ProductOutput
    {
        $input->name && $product->setName($input->name);
        $input->price && $product->setPrice($input->price);
        $input->category && $product->setCategory(ProductCategory::from($input->category));

        $this->productRepository->save($product, true);

        return new ProductOutput($product);
    }

    /**
     * Delete a product
     *
     * @param Product $product
     * @return void
     */
    public function delete(Product $product): void
    {
        $this->productRepository->delete($product, true);
    }
}
