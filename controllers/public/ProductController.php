<?php

class ProductController
{
    public function __construct(private PDO $db) {}

    public function index(): void
    {
        $productModel  = new Product($this->db);
        $categoryModel = new Category($this->db);

        // Ambil filter dari query string
        $filters = [];
        $filterKeys = ['category_id', 'brand', 'color', 'frame_size', 'price_min', 'price_max', 'search'];
        foreach ($filterKeys as $key) {
            $val = trim($_GET[$key] ?? '');
            if ($val !== '') {
                $filters[$key] = $val;
            }
        }

        $products   = $productModel->all($filters);
        $categories = $categoryModel->all();

        $title = 'Katalog Produk';
        ob_start();
        require __DIR__ . '/../../views/products/catalog.php';
        $content = ob_get_clean();
        require __DIR__ . '/../../views/components/public-layout.php';
    }
}
