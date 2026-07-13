<?php

// Controller untuk halaman katalog & detail produk yang dilihat pembeli (bukan admin).
class ProductController
{
    public function __construct(private PDO $db) {}

    // Tampilkan katalog produk + handle filter/search/sort dari query string
    public function index(): void
    {
        $productModel  = new Product($this->db);
        $categoryModel = new Category($this->db);

        // Ambil filter dari query string
        // Hanya masukin key yang beneran diisi user, biar query di Product::all() gak kena filter kosong
        $filters = [];
        $filterKeys = ['category_id', 'brand', 'color', 'frame_size', 'price_min', 'price_max', 'search', 'sort'];
        foreach ($filterKeys as $key) {
            $val = trim($_GET[$key] ?? '');
            if ($val !== '') {
                $filters[$key] = $val;
            }
        }

        $products      = $productModel->all($filters);
        $categories    = $categoryModel->all();
        $brands        = $productModel->getDistinctBrands();
        $colors        = $productModel->getDistinctColors();
        $frameSizes    = $productModel->getDistinctFrameSizes();

        $title = 'Katalog Produk';
        ob_start();
        require __DIR__ . '/../../views/products/catalog.php';
        $content = ob_get_clean();
        require __DIR__ . '/../../views/components/public-layout.php';
    }

    // Tampilkan detail 1 produk berdasarkan id di query string
    public function show(): void
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            http_response_code(404);
            $title = 'Produk Tidak Ditemukan';
            ob_start();
            echo '<div class="text-center py-20 space-y-4">'
               . '<p class="text-6xl">404</p>'
               . '<p class="text-gray-500">Produk yang kamu cari tidak ditemukan.</p>'
               . '<a href="/" class="inline-flex items-center justify-center rounded-lg bg-brand px-4 py-2 text-white font-medium hover:bg-brand-dark">Kembali ke Katalog</a>'
               . '</div>';
            $content = ob_get_clean();
            require __DIR__ . '/../../views/components/public-layout.php';
            return;
        }

        $productModel = new Product($this->db);
        $product = $productModel->find($id);

        // Produk nonaktif dianggap tidak ada buat buyer, walaupun row-nya masih ada di DB
        if (!$product || (int) $product['is_active'] === 0) {
            http_response_code(404);
            $title = 'Produk Tidak Ditemukan';
            ob_start();
            echo '<div class="text-center py-20 space-y-4">'
               . '<p class="text-6xl">404</p>'
               . '<p class="text-gray-500">Produk yang kamu cari tidak ditemukan.</p>'
               . '<a href="/" class="inline-flex items-center justify-center rounded-lg bg-brand px-4 py-2 text-white font-medium hover:bg-brand-dark">Kembali ke Katalog</a>'
               . '</div>';
            $content = ob_get_clean();
            require __DIR__ . '/../../views/components/public-layout.php';
            return;
        }

        $images = $productModel->getImages($id);

        $title = $product['title'];
        ob_start();
        require __DIR__ . '/../../views/products/detail.php';
        $content = ob_get_clean();
        require __DIR__ . '/../../views/components/public-layout.php';
    }
}
