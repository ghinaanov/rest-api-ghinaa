<?php

namespace App\Http\Controllers;

use PDO;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $pdo;
    public function __construct()
    {
        $host = env('DB_HOST');
        $db = env('DB_DATABASE');
        $user = env('DB_USERNAME');
        $pass = env('DB_PASSWORD');
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function index() {
        try {
            $stmt = $this->pdo->query('SELECT * FROM products');
            $products = $stmt->fetchAll();
            return response()->json($products);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM products WHERE id = ?');
            $stmt->execute([$id]);
            $product = $stmt->fetch();
            
            if (!$product) {
                return response()->json(['message' => 'Product not found'], 404);
            }

            return response()->json($product);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only(['name', 'description', 'price']);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads', 'public');
            $data['image'] = $imagePath;
        } else {
            $data['image'] = null;
        }

        try {
            $stmt = $this->pdo->prepare('INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)');
            $stmt->execute([$data['name'], $data['description'], $data['price'], $data['image']]);

            return response()->json(['message' => 'Product created successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create product: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Ambil data produk berdasarkan ID
        $product = $this->pdo->query('SELECT * FROM products WHERE id = ' . intval($id))->fetch();

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        try {
            $data = [
                $request->name,
                $request->description,
                $request->price,
                $product['image'], // Default gunakan gambar lama
                $id,
            ];

            // Jika ada gambar baru, simpan dan perbarui path
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('uploads', 'public');
                $data[3] = $imagePath;
            }

            // Query PDO untuk update
            $stmt = $this->pdo->prepare('UPDATE products SET name = ?, description = ?, price = ?, image = ? WHERE id = ?');
            $stmt->execute($data);

            return response()->json(['message' => 'Product updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update product: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM products WHERE id = ?');
            $stmt->execute([$id]);
            return response()->json(['message' => 'Product deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}