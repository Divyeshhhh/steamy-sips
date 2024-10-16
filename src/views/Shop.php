<?php

declare(strict_types=1);

/**
 * View for shop page
 *
 * @var Product[] $products Array of all products as fetched from database
 * @var string[] $categories Array of all product category names
 * @var string[] $selected_categories Array of selected categories
 * @var string $search_keyword keyword used to filter products
 * @var string $sort_option Sort By option selected by user
 * @var string $pagination HTML code pagination
 */

use Steamy\Model\Product;

/**
 * Outputs sanitized HTML to display a product
 * @param Product $product
 * @return void
 */
function displayProduct(Product $product): void
{
    $product_href = htmlspecialchars(
        '/shop/products/' . $product->getProductID()
    ); // link to product page
    $product_img_src = htmlspecialchars($product->getImgAbsolutePath()); // url of image
    $img_alt_text = htmlspecialchars($product->getImgAltText());
    $name = htmlspecialchars($product->getName());
    $price = filter_var($product->getPrice(), FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    echo <<<EOL
        <a data-aos="zoom-in" href="$product_href">
            <img src="$product_img_src" alt="$img_alt_text">
            <hgroup> 
                <h5>$name</h5>
                <h5>Rs $price</h5>
            </hgroup>
        </a>
    EOL;
}
?>

<form method="get" class="container">
    <label>
        <input value="<?= htmlspecialchars($search_keyword) ?>" name="keyword" type="search" placeholder="Search"/>
    </label>

    <div class="grid">
        <label>
            <select name="sort">
                <option value="" <?= empty($sort_option) ? "selected" : "" ?>>Sort By</option>
                <option value="newest" <?= $sort_option === "newest" ? "selected" : "" ?>>Newest</option>
                <option value="priceDesc" <?= $sort_option === "priceDesc" ? "selected" : "" ?>>Price: High-Low</option>
                <option value="priceAsc" <?= $sort_option === "priceAsc" ? "selected" : "" ?>>Price: Low-High</option>
                <option value="ratingDesc" <?= $sort_option === "ratingDesc" ? "selected" : "" ?>>Rating: High-Low
                </option>
                <option value="ratingAsc" <?= $sort_option === "ratingAsc" ? "selected" : "" ?>>Rating: Low-High
                </option>
            </select>
        </label>

        <details role="list">
            <summary aria-haspopup="listbox">Categories</summary>
            <ul role="listbox">
                <?php
                foreach ($categories as $category) {
                    // determine whether $category should be checked
                    $checked = in_array($category, $selected_categories) ? "checked" : "";
                    $sanitized_category = htmlspecialchars($category);

                    echo <<< EOL
                        <li>
                            <label>
                                <input $checked value="$category" name="categories[]" type="checkbox">
                                $sanitized_category
                            </label>
                        </li>
                    EOL;
                }
                ?>
            </ul>
        </details>

        <button class="secondary" type="submit">Filter</button>
    </div>

</form>

<main class="container" style="padding-top: 0">
    <div id="item-grid">
        <?php
        foreach ($products as $product) {
            displayProduct($product);
        }
        ?>
    </div>
</main>

<?= $pagination ?>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    AOS.init();
  });
</script>
