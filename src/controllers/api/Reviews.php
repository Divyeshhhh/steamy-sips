<?php

declare(strict_types=1);

namespace Steamy\Controller\API;

use Opis\JsonSchema\{Errors\ErrorFormatter};
use Exception;
use PDO;
use Steamy\Core\Model;
use Steamy\Core\Utility;
use Steamy\Model\Review;

class Reviews
{
    use Model;

    public static array $routes = [
        'GET' => [
            '/reviews' => 'getAllReviews',
            '/reviews/{id}' => 'getReviewByID',
            '/reviews/stats/count-over-time' => 'getCountOverTime',
        ],
        'POST' => [
            '/reviews' => 'createReview',
        ],
        'PUT' => [
            '/reviews/{id}' => 'updateReview',
        ],
        'DELETE' => [
            '/reviews/{id}' => 'deleteReview',
        ]
    ];

    /**
     * Get the list of all reviews available.
     */
    public function getAllReviews(): void
    {
        $query = "SELECT * FROM review";

        if (!empty($_GET['order_by']) && $_GET['order_by'] === 'created_date') {
            $query .= " ORDER BY created_date DESC ";
        }

        if (!empty($_GET['limit'])) {
            $limit = filter_var($_GET['limit'], FILTER_SANITIZE_NUMBER_INT);
            $query .= " LIMIT " . $limit;
        }

        $query .= ";";

        $con = self::connect();
        $stm = $con->prepare($query);
        $success = $stm->execute();

        if (!$success) {
            http_response_code(500);
            echo json_encode(['error' => 'Database bad']);
            return;
        }

        $result = $stm->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($result);
    }

    public function getReviewByID(): void
    {
        $id = (int)Utility::splitURL()[3];

        // Retrieve all reviews from the database
        $review = Review::getByID($id);

        // Check if product exists
        if ($review === null) {
            // review not found, return 404
            http_response_code(404);
            echo json_encode(['error' => 'Review not found']);
            return;
        }

        // Return JSON response
        echo json_encode($review->toArray());
    }

    /**
     * Create a new review for a product.
     */
    public function createReview(): void
    {
        // Retrieve POST data
        $data = (object)json_decode(file_get_contents("php://input"), true);

        // Validate against JSON schema
        $result = Utility::validateAgainstSchema($data, "reviews/create.json");

        if (!($result->isValid())) {
            $errors = (new ErrorFormatter())->format($result->error());
            $response = [
                'error' => $errors
            ];
            http_response_code(400);
            echo json_encode($response);
            return;
        }

        // Create a new Review object
        $newReview = new Review(
            null, // review_id will be auto-generated
            (int)$data->product_id,
            (int)$data->client_id,
            $data->text,
            (int)$data->rating
        );

        // Save the new review to the database
        try {
            $newReview->save();
            // Review created successfully, return 201 Created
            http_response_code(201);
            echo json_encode(['message' => 'Review created successfully', 'review_id' => $newReview->getReviewID()]
            );
        } catch (Exception $e) {
            // Failed to create review, return 500 Internal Server Error
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create review']);
        }
    }

    /**
     * Update the details of a review with the specified ID.
     */
    public function updateReview(): void
    {
        $reviewId = (int)Utility::splitURL()[3];

        // Retrieve PUT request data
        $data = (object)json_decode(file_get_contents("php://input"), true);

        // Validate against JSON schema
        $result = Utility::validateAgainstSchema($data, "reviews/update.json");

        if (!($result->isValid())) {
            $errors = (new ErrorFormatter())->format($result->error());
            $response = [
                'error' => $errors
            ];
            http_response_code(400);
            echo json_encode($response);
            return;
        }

        // Retrieve existing review
        $review = Review::getByID($reviewId);

        // Check if review exists
        if ($review === null) {
            // Review not found
            http_response_code(404); // Not Found
            echo json_encode(['error' => 'Review not found']);
            return;
        }

        // Update review in the database
        $success = $review->updateReview((array)$data);

        if ($success) {
            // Review updated successfully
            http_response_code(200); // OK
            echo json_encode(['message' => 'Review updated successfully']);
        } else {
            // Failed to update review
            http_response_code(500); // Internal Server Error
            echo json_encode(['error' => 'Failed to update review']);
        }
    }

    /**
     * Delete a review with the specified ID.
     */
    public function deleteReview(): void
    {
        $reviewId = (int)Utility::splitURL()[3];

        // Retrieve the review by ID
        $review = Review::getByID($reviewId);

        // Check if review exists
        if ($review === null) {
            // Review not found, return 404
            http_response_code(404);
            echo json_encode(['error' => 'Review not found']);
            return;
        }

        // Attempt to delete the review
        if ($review->deleteReview()) {
            // Review successfully deleted
            http_response_code(204); // No Content
        } else {
            // Failed to delete the review
            http_response_code(500); // Internal Server Error
            echo json_encode(['error' => 'Failed to delete review']);
        }
    }

    /**
     * Gets the number of reviews for each month with percentage difference from last month.
     *  Example response:
     *  <pre>
     *      [
     *          {
     *              "month": "2024-04-01",
     *              "totalReviews": 1,
     *              "percentageDifference": null
     *          },
     *          {
     *              "month": "2024-05-01",
     *              "totalReviews": 9,
     *              "percentageDifference": "800.00"
     *          },
     *          {
     *              "month": "2024-06-01",
     *              "totalReviews": 1,
     *              "percentageDifference": "-88.89"
     *          },
     *          {
     *              "month": "2024-10-01",
     *              "totalReviews": 1,
     *              "percentageDifference": "0.00"
     *          }
     *      ]
     *  </pre>
     *
     * @return void
     */
    public function getCountOverTime(): void
    {
        $query = <<< EOL
        WITH monthly_reviews AS (
            SELECT
                DATE_FORMAT(created_date, '%Y-%m-01') AS month,  -- Extract the first day of the month
                COUNT(review_id) AS totalReviews                -- Total reviews per month
            FROM
                review
            GROUP BY
                DATE_FORMAT(created_date, '%Y-%m')
        ),
        monthly_diff AS (
            SELECT
                month,
                totalReviews,
                LAG(totalReviews) OVER (ORDER BY month) AS previousMonthReviews  -- Get the previous month's reviews
            FROM
                monthly_reviews
        )
        SELECT
            month,
            totalReviews,
            CASE
                WHEN previousMonthReviews IS NOT NULL AND previousMonthReviews != 0 THEN
                    ROUND(((totalReviews - previousMonthReviews) * 100.0 / previousMonthReviews), 2)
                ELSE
                    NULL  -- No previous month data for the first row
            END AS percentageDifference
        FROM
            monthly_diff
        ORDER BY
            month;
        EOL;

        $con = self::connect();
        $stm = $con->prepare($query);
        $stm->execute();

        echo json_encode($stm->fetchAll(PDO::FETCH_ASSOC));
    }
}
