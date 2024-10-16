# API Documentation

- [API Documentation](#api-documentation)
    - [Endpoints](#endpoints)
        - [Authentication](#authentication)
        - [User](#user)
        - [Product](#product)
        - [Order](#order)
        - [Review](#review)
        - [Comment](#comment)
        - [District](#district)
    - [Query string parameters](#query-string-parameters)
- [References](#references)

The Steamy Sips API is a REST API.

Add `X-TEST-ENV` to the header of your request if you want to use the testing database. This is required when running
tests for API.

## Endpoints

There are two types of endpoints:

1. **Public endpoints** : They return a public resource that can be accessed by anyone.
2. **Protected endpoints** : They return a protected resource that can only be accessed by administrators.

### Session

PHP sessions are used for authentication so all session information are stored on server.

| Endpoint                | Description                                                                                              | Protected |
|-------------------------|----------------------------------------------------------------------------------------------------------|-----------|
| `POST /api/v1/sessions` | Authenticates admin and creates a session token. The request body should contain `email` and `password`. | No        |

### User

A user can be a client or an administrator.

| Endpoint                          | Description                                         | Protected | Query string parameters |
|-----------------------------------|-----------------------------------------------------|-----------|-------------------------|
| `GET /api/v1/users`               | Get the list of all users.                          | Yes       | `user-type`             |         
| `GET /api/v1/users/[id]`          | Get the details of a specific user by their ID.     | Yes       |
| `POST /api/v1/users`              | Create a new user entry in the database.            | Yes       |
| `DELETE /api/v1/users/[id]`       | Delete a user with the specified ID.                | Yes       |
| `PUT /api/v1/users/[id]`          | Update the details of a user with the specified ID. | Yes       |
| `GET /api/v1/users/[id]/orders/`  | Get the orders made by a specific user.             | Yes       |
| `GET /api/v1/users/[id]/reviews/` | Get the reviews made by a specific user.            | Yes       |

### Product

| Endpoint                                        | Description                                            | Protected | Query string parameters |
|-------------------------------------------------|--------------------------------------------------------|-----------|-------------------------|
| `GET /api/v1/products`                          | Get the list of all products available in the store.   | No        | `sort`, `group-by`      |
| `GET /api/v1/products/[id]`                     | Get the details of a specific product by its ID.       | No        |
| `GET /api/v1/products/[id]/stores`              | Get the details of stores where a product is sold.     | No        |
| `GET /api/v1/products/categories`               | Get the list of product categories.                    | No        |
| `POST /api/v1/products`                         | Create a new product entry in the database.            | No        |
| `DELETE /api/v1/products/[id]`                  | Delete a product with the specified ID.                | No        |
| `PUT /api/v1/products/[id]`                     | Update the details of a product with the specified ID. | No        |
| `GET /api/v1/products/sales/sales-per-category` | Get the number of units sold per category.             | No        |                         |

### Order

| Endpoint                                   | Description                                           | Protected | Query string parameters      |
|--------------------------------------------|-------------------------------------------------------|-----------|------------------------------|
| `GET /api/v1/orders/`                      | Get the list of all orders.                           | Yes       | `sort`, `group-by`, `status` |
| `GET /api/v1/orders/[id]`                  | Get the details of a specific order by its ID.        | Yes       |
| `POST /api/v1/orders`                      | Create a new order for products.                      | Yes       |
| `PUT /api/v1/orders/[id]`                  | Update the details of an order with the specified ID. | Yes       |
| `DELETE /api/v1/orders/[id]`               | Delete an order with the specified ID.                | Yes       |
| `GET /api/v1/orders/stats/sales-over-time` | Get total revenue for each month.                     | Yes       |                              |

### Review

| Endpoint                                    | Description                                           | Protected | Query string parameters |
|---------------------------------------------|-------------------------------------------------------|-----------|-------------------------|
| `GET /api/v1/reviews`                       | Get all reviews for a particular product by its ID.   | No        | `limit`, `order_by`     |
| `GET /api/v1/products/[id]/reviews`         | Get all reviews for a particular product by its ID.   | No        |
| `POST /api/v1/reviews`                      | Create a new review for a product.                    | Yes       |
| `PUT /api/v1/reviews/[id]`                  | Update the details of a review with the specified ID. | Yes       |
| `DELETE /api/v1/reviews/[id]`               | Delete a review with the specified ID.                | Yes       |
| `GET /api/v1/reviews/stats/count-over-time` | Get the count of reviews for each month.              | No        |                         |


### Comment

| Endpoint                             | Description                                            | Protected |
|--------------------------------------|--------------------------------------------------------|-----------|
| `GET /api/v1/comments`               | Get the list of all comments.                          | No        |
| `GET /api/v1/comments/[id]`          | Get the details of a specific comments by its ID.      | No        |
| `POST /api/v1/comments`              | Create a new comment  for a product.                   | Yes       |
| `PUT /api/v1/comments/[id]`          | Update the details of a comment with the specified ID. | Yes       |
| `DELETE /api/v1/comments/[id]`       | Delete a comment with the specified ID.                | Yes       |

### District

| Endpoint                     | Description                                       | Protected |
|------------------------------|---------------------------------------------------|-----------|
| `GET /api/v1/districts`      | Get the list of all districts.                    | No        |
| `GET /api/v1/districts/[id]` | Get the details of a specific district by its ID. | No        |

## Query string parameters

| Parameter   | Possible values   | Description                                       |
|-------------|-------------------|---------------------------------------------------|
| `sort`      | `asc`, `desc`     | Sort in ascending or descending order             |
| `user-type` | `client`, `admin` | For user endpoints, return only clients or admins | 

# References

1. https://apiguide.readthedocs.io/en/latest/build_and_publish/documentation.html
2. https://stackoverflow.com/questions/1619152/how-to-create-rest-urls-without-verbs
