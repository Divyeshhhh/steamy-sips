<?php

declare(strict_types=1);

namespace Steamy\Controller\API;

use Steamy\Core\Utility;
use Steamy\Model\Product;
use Steamy\Core\Model;

class Products
{
    use Model;

    public static array $routes = [
        'GET' => [
            '/api/v1/products' => 'getAllProducts',
            '/api/v1/products/categories' => 'getProductCategories',
            '/api/v1/products/{id}' => 'getProductById',
        ],
        'POST' => [
            '/api/v1/products' => 'createProduct',
        ],
        'PUT' => [
            '/api/v1/products/{id}' => 'updateProduct',
        ],
        'DELETE' => [
            '/api/v1/products/{id}' => 'deleteProduct',
        ]
    ];

    /**
     * Get the list of all products available in the store.
     */
    public function getAllProducts(): void
    {
        // Retrieve all products from the database
        $allProducts = Product::getAll();

        // Convert products to array format
        $result = [];
        foreach ($allProducts as $product) {
            $result[] = $product->toArray();
        }

        // Return JSON response
        echo json_encode($result);
    }

    /**
     * Get the details of a specific product by its ID.
     */
    public function getProductById(): void
    {
        $productId = (int)Utility::splitURL()[3];

        // Retrieve product details from the database
        $product = Product::getByID($productId);

        // Check if product exists
        if ($product === null) {
            // Product not found, return 404
            http_response_code(404);
            echo json_encode(['error' => 'Product not found']);
            return;
        }

        // Return JSON response
        echo json_encode($product->toArray());
    }

    /**
     * Get the list of product categories.
     */
    public function getProductCategories(): void
    {
        // Retrieve all product categories from the database
        $categories = Product::getCategories();

        // Return JSON response
        echo json_encode($categories);
    }

    /**
     * Create a new product entry in the database.
     */
    public function createProduct(): void
    {
        // Retrieve POST data
        $postData = $_POST;

        // TODO : Use json schema validation here
        // Check if required fields are present
        $requiredFields = [
            'name',
            'calories',
            'img_url',
            'img_alt_text',
            'category',
            'price',
            'description'
        ];

        if (empty($postData)) {
            http_response_code(400);
            echo json_encode(['error' => "Missing fields: " . implode(', ', $requiredFields)]);
            return;
        }

        foreach ($requiredFields as $field) {
            if (empty($postData[$field])) {
                // Required field is missing, return 400 Bad Request
                http_response_code(400);
                echo json_encode(['error' => "Missing required field: $field"]);
                return;
            }
        }

        // Create a new Product object
        $newProduct = new Product(
            $postData['name'],
            (int)$postData['calories'],
            $postData['img_url'],
            $postData['img_alt_text'],
            $postData['category'],
            (float)$postData['price'],
            $postData['description']
        );

        // Save the new product to the database
        if ($newProduct->save()) {
            // Product created successfully, return 201 Created
            http_response_code(201);
            echo json_encode(['message' => 'Product created successfully', 'product_id' => $newProduct->getProductID()]
            );
        } else {
            // Failed to create product, return 500 Internal Server Error
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create product']);
        }
    }

    /**
     * Delete a product with the specified ID.
     */
    public function deleteProduct(): void
    {
        $productId = (int)Utility::splitURL()[3];

        // Retrieve the product by ID
        $product = Product::getByID($productId);

        // Check if product exists
        if ($product === null) {
            // Product not found, return 404
            http_response_code(404);
            echo json_encode(['error' => 'Product not found']);
            return;
        }

        // Attempt to delete the product
        if ($product->deleteProduct()) {
            // Product successfully deleted
            http_response_code(204); // No Content
        } else {
            // Failed to delete the product
            http_response_code(500); // Internal Server Error
            echo json_encode(['error' => 'Failed to delete product']);
        }
    }

    /**
     * Update the details of a product with the specified ID.
     */
    public function updateProduct(): void
    {
        $productId = (int)Utility::splitURL()[3];

        // Retrieve PUT request data
        $putData = json_decode(file_get_contents("php://input"), true);

        // Check if PUT data is valid
        if (empty($putData)) {
            // Invalid JSON data
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Invalid JSON data']);
            return;
        }

        // Retrieve existing product
        $product = Product::getByID($productId);

        // Check if product exists
        if ($product === null) {
            // Product not found
            http_response_code(404); // Not Found
            echo json_encode(['error' => 'Product not found']);
            return;
        }

        // Update product in the database
        $success = $product->updateProduct($putData);

        if ($success) {
            // Product updated successfully
            http_response_code(200); // OK
            echo json_encode(['message' => 'Product updated successfully']);
        } else {
            // Failed to update product
            http_response_code(500); // Internal Server Error
            echo json_encode(['error' => 'Failed to update product']);
        }
    }
}
