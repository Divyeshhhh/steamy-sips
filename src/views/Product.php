<?php

declare(strict_types=1);

/**
 * @var $product Product product information
 * @var $product_reviews Review[] List of product reviews to be displayed with any filters applied
 * @var $current_review_filter string
 * @var $signed_in_user bool is current user signed in?
 * @var $default_review string default review text in form
 * @var $default_rating int default rating in form
 * @var $rating_distribution string An array containing the percentages of ratings
 * @var $comment_form_info ?array Array with information to be displayed on comment form
 * @var $review_pagination string HTML code for review pagination
 */

use Steamy\Core\Utility;
use Steamy\Model\Product;
use Steamy\Model\Review;
use Steamy\Model\User;
use Carbon\Carbon;


/**
 * Returns the HTML code for a badge indicating the status of a review (verified or not)
 * @param Review $review Review
 * @return string HTML code of badge
 */
function getBadge(Review $review): string
{
    if ($review->isVerified()) {
        return <<< BADGE
        <div data-tooltip="Verified Purchase" data-placement="left" >
            <svg xmlns="http://www.w3.org/2000/svg"
            class="icon-tabler-discount-check-filled"
            width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
             stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"
             /><path d="M12.01 2.011a3.2 3.2 0 0 1 2.113 .797l.154 .145l.698 .698a1.2 1.2 0 0 0 .71 .341l.135 .008h1a3.2 3.2 0 0 1 3.195 3.018l.005 .182v1c0 .27 .092 .533 .258 .743l.09 .1l.697 .698a3.2 3.2 0 0 1 .147 4.382l-.145 .154l-.698 .698a1.2 1.2 0 0 0 -.341 .71l-.008 .135v1a3.2 3.2 0 0 1 -3.018 3.195l-.182 .005h-1a1.2 1.2 0 0 0 -.743 .258l-.1 .09l-.698 .697a3.2 3.2 0 0 1 -4.382 .147l-.154 -.145l-.698 -.698a1.2 1.2 0 0 0 -.71 -.341l-.135 -.008h-1a3.2 3.2 0 0 1 -3.195 -3.018l-.005 -.182v-1a1.2 1.2 0 0 0 -.258 -.743l-.09 -.1l-.697 -.698a3.2 3.2 0 0 1 -.147 -4.382l.145 -.154l.698 -.698a1.2 1.2 0 0 0 .341 -.71l.008 -.135v-1l.005 -.182a3.2 3.2 0 0 1 3.013 -3.013l.182 -.005h1a1.2 1.2 0 0 0 .743 -.258l.1 -.09l.698 -.697a3.2 3.2 0 0 1 2.269 -.944zm3.697 7.282a1 1 0 0 0 -1.414 0l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.32 1.497l2 2l.094 .083a1 1 0 0 0 1.32 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" stroke-width="0" fill="currentColor" />
             </svg>
        </div>
        BADGE;
    }
    return <<< UNVERIFIED_BADGE
        <div data-tooltip="This user did not buy the product" data-placement="left" >
        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"
          fill="none"  stroke="red"  stroke-width="2"  stroke-linecap="round"
            stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline
             icon-tabler-alert-octagon"><path stroke="none" d="M0 0h24v24H0z"
              fill="none"/><path d="M12.802 2.165l5.575 2.389c.48 .206 .863 .589 1.07 1.07l2.388
               5.574c.22 .512 .22 1.092 0 1.604l-2.389 5.575c-.206 .48 -.589 .863 -1.07 1.07l-5.574
                2.388c-.512 .22 -1.092 .22 -1.604 0l-5.575 -2.389a2.036 2.036 0 0 1 -1.07 -1.07l-2.388
                 -5.574a2.036 2.036 0 0 1 0 -1.604l2.389 -5.575c.206 -.48 .589 -.863 1.07 -1.07l5.574
                  -2.388a2.036 2.036 0 0 1 1.604 0z" /><path d="M12 8v4" /><path d="M12 16h.01" /></svg>
        </div>
        UNVERIFIED_BADGE;
}

/**
 * Returns the HTML code for stars showing the rating of a review
 * @param Review $review
 * @return string HTML for stars
 */
function getStars(Review $review): string
{
    $checked_stars = filter_var($review->getRating(), FILTER_VALIDATE_INT); // number of shaded stars
    $unchecked_stars = Review::MAX_RATING - $checked_stars; // number of empty stars
    $html = "";

    while ($checked_stars > 0) {
        $html .= <<< EOL
        <svg xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"
          fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"
            stroke-linejoin="round"  class="fill-star icon icon-tabler icons-tabler
            -outline icon-tabler-star">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 17.75l-6.172 3.245l1.179
             -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" /></svg>
        EOL;
        $checked_stars--;
    }


    while ($unchecked_stars > 0) {
        $html .= <<< EOL
        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"
          fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"
            stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-star">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 17.75l-6.172 3.245l1.179
             -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" /></svg>
        EOL;
        $unchecked_stars--;
    }
    return $html;
}

/**
 * Prints sanitized HTML code to display a review and its comment section.
 * @param Review $review
 * @return void
 */
function printReview(Review $review): void
{
    $review_id = $review->getReviewID();
    $reply_link = "?reply_to_review=" . $review->getReviewID();
    $date = htmlspecialchars(Carbon::instance($review->getCreatedDate())->diffForHumans());
    $text = htmlspecialchars($review->getText());
    $author = htmlspecialchars(User::getFullName($review->getClientID()));
    $verified_badge = getBadge($review);
    $rating_stars = getStars($review);

    echo <<< EOL
        <article id="review-$review_id">
            $verified_badge
            $rating_stars
           <hgroup> 
                <h5>$author</h5>
                <h6 class="review-date">$date</h6>
           </hgroup>
           
            <p>$text</p>
            <a data-tooltip="Reply" data-placement="right" href= "$reply_link">
                 <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-message-reply"
                 width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                  stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"
                   fill="none"/><path d="M18 4a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-5l-5 3v-3h-2a3 3 0 0 1 -3
                    -3v-8a3 3 0 0 1 3 -3h12z" />
                   <path d="M11 8l-3 3l3 3" /><path d="M16 11h-8" />
                 </svg>
            </a>

        </article>
    EOL;

    // print comment section
    printCommentSection($review->getNestedComments());
}

/**
 * Prints sanitized HTML code for a comment section given the return value of review->getNestedComments().
 * @param array $top_comments Array of top-level comments where each comment has a children attribute.
 * @return void
 */
function printCommentSection(array $top_comments): void
{
    if (empty($top_comments)) {
        return;
    }

    echo "<ul>";
    foreach ($top_comments as $top_level_comment) {
        echo "<li>";
        printComment($top_level_comment);
        printCommentSection($top_level_comment->children);
        echo "</li>";
    }
    echo "</ul>";
}

/**
 * Prints sanitized HTML code for a comment.
 * @param StdClass $comment
 * @return void
 */
function printComment(StdClass $comment): void
{
    $reply_link = "?reply_to_comment=" . $comment->comment_id;
    $date = htmlspecialchars(Carbon::instance(Utility::stringToDate($comment->created_date))->diffForHumans());;
    $text = htmlspecialchars($comment->text);
    $author = htmlspecialchars(User::getFullName($comment->user_id));
    $comment_id = 'comment-' . $comment->comment_id;

    echo <<<EOL
        <article id = "$comment_id">
           <hgroup> 
                <h5>$author</h5>
                <h6 class="review-date">$date</h6>
           </hgroup>
           
            <p>$text</p>
            <a data-tooltip="Reply" data-placement="right" href= "$reply_link">
                 <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-message-reply"
                 width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                  stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"
                   fill="none"/><path d="M18 4a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-5l-5 3v-3h-2a3 3 0 0 1 -3
                    -3v-8a3 3 0 0 1 3 -3h12z" />
                   <path d="M11 8l-3 3l3 3" /><path d="M16 11h-8" />
                 </svg>
            </a>
    
        </article>
    EOL;
}

?>

<dialog id="my-modal">
    <article>
        <a href="#"
           aria-label="Close"
           class="close"
           data-target="my-modal"
        >
        </a>
        <h3>Item successfully added!</h3>
        <footer>
            <a href="#"
               role="button"
               class="secondary"
               data-target="my-modal"
            >
                Ok
            </a>
            <a href="/cart"
               role="button"
               data-target="my-modal"
            >
                View cart
            </a>
        </footer>
    </article>
</dialog>

<?php
// display comment form if user previously clicked on reply to button
if (!empty($comment_form_info)): ?>
    <dialog open id="comment-box">
        <article style="width: 45%;">
            <a href="#"
               aria-label="Close"
               class="close"
               data-target="comment-box"
            >
            </a>
            <h3>Reply to:</h3>

            <blockquote>
                <p><?= htmlspecialchars($comment_form_info['quote_text']) ?></p>
                <footer>
                    - <?= htmlspecialchars($comment_form_info['quote_author']) ?>,
                    <?= htmlspecialchars($comment_form_info['quote_date']) ?>
                </footer>
            </blockquote>

            <form action="" method="post">
                <?php
                if (!empty($comment_form_info['review_id'])): ?>
                    <input type="hidden" name="review_id"
                           value="<?= filter_var($comment_form_info['review_id'], FILTER_SANITIZE_NUMBER_INT) ?>">
                <?php
                endif ?>

                <?php
                if (!empty($comment_form_info['parent_comment_id'])): ?>
                    <input type="hidden" name="parent_comment_id"
                           value="<?= filter_var(
                               $comment_form_info['parent_comment_id'],
                               FILTER_SANITIZE_NUMBER_INT
                           ) ?>">
                <?php
                endif ?>

                <textarea name="comment" placeholder="Your comment" cols="20" rows="5"></textarea>
                <small style="color:red"><?= $comment_form_info['error'] ?? "" ?></small>
                <button class="secondary" type="submit" <?= $signed_in_user ? "" : "disabled" ?>>Submit</button>
            </form>
        </article>
    </dialog>
<?php
endif ?>

<main class="container">
    <div id="product-info" class="grid">
        <img src="<?= htmlspecialchars($product->getImgAbsolutePath()) ?>"
             alt="<?= htmlspecialchars($product->getImgAltText()) ?>">
        <div>
            <hgroup>
                <h1><?= htmlspecialchars($product->getName()) ?></h1>
                <h4>Rs <?= filter_var(
                        $product->getPrice(),
                        FILTER_SANITIZE_NUMBER_FLOAT,
                        FILTER_FLAG_ALLOW_FRACTION
                    ) ?></h4>
                <p><?= $product->getCalories() ?> calories</p>
            </hgroup>
            <p>
                <?= htmlspecialchars($product->getDescription()) ?>
            </p>
            <form id="product-customization-form" method="post">
                <input type="hidden" value="1" name="quantity">
                <input type="hidden" value="<?= filter_var(
                    $product->getProductID(),
                    FILTER_SANITIZE_NUMBER_INT
                ) ?>"
                       name="product_id">
                <h4>Size options</h4>
                <fieldset>
                    <label for="small">
                        <input type="radio" id="small" name="cupSize" value="small" checked>
                        Small
                    </label>
                    <label for="medium">
                        <input type="radio" id="medium" name="cupSize" value="medium">
                        Medium
                    </label>
                    <label for="large">
                        <input type="radio" id="large" name="cupSize" value="large">
                        Large
                    </label>
                </fieldset>
                <h4>Customizations</h4>
                <label for="milk">
                    Milk
                </label>
                <select id="milk" name="milkType" required>
                    <option value="almond" selected>Almond</option>
                    <option value="coconut">Coconut</option>
                    <option value="oat">Oat</option>
                    <option value="soy">Soy</option>
                </select>
                <button type="submit">Add to cart</button>
            </form>

        </div>
    </div>

    <hgroup>
        <h3>Rate this product</h3>
        <p>Tell others what you think</p>
    </hgroup>
    <form method="post">
        <div class="review__rating">
            <!-- Reference: Alice Campkin: https://codepen.io/alcampk/pen/rNezxXE-->
            <input type=radio value='0' id='star-0' name='review_rating'/>

            <label for='star-1'>★</label>
            <input type=radio checked value='1' id='star-1' name='review_rating'/>

            <label for='star-2'>★</label>
            <input type=radio value='2' id='star-2' name='review_rating'/>

            <label for='star-3'>★</label>
            <input type=radio value='3' id='star-3' name='review_rating'/>

            <label for='star-4'>★</label>
            <input type=radio value='4' id='star-4' name='review_rating'/>

            <label for='star-5'>★</label>
            <input type=radio value='5' id='star-5' name='review_rating'/>
        </div>
        <label>
            <textarea id="review-input" name="review_text" id="" cols="30" rows="3"
                      required
                      placeholder="Describe your experience"
                <?php
                if (!empty($default_review)) {
                    echo empty($errors['text']) ? 'aria-invalid=false' : 'aria-invalid=true';
                } ?>><?= htmlspecialchars(trim($default_review)) ?></textarea>


            <small><?= $errors['text'] ?? "" ?></small>
        </label>
        <button style="width: 20%" class="secondary" type="submit" <?= $signed_in_user ? "" : "disabled" ?>>Post
        </button>
    </form>

    <h2>Customer Reviews (<?= count($product->getReviews()) ?>)</h2>

    <div style="display: flex; align-items: center; gap:3em;">
        <hgroup>
            <h6>Average Rating</h6>
            <p style="font-size: 50px;">
                <?= filter_var(
                    $product->getAverageRating(),
                    FILTER_SANITIZE_NUMBER_FLOAT,
                    FILTER_FLAG_ALLOW_FRACTION
                ) ?>

                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24"
                     fill="none" stroke="black" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="fill-star icon icon-tabler icons-tabler
                            -outline icon-tabler-star">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M12 17.75l-6.172 3.245l1.179
                             -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"/>
                </svg>
            </p>
        </hgroup>

        <div style="width: 500px;">
            <canvas id="customer_rating_chart" data-chart-data="<?= $rating_distribution ?>"></canvas>
        </div>
    </div>

    <form id="review-form" action="" method="get">
        <label for="filter-by">Filter by</label>
        <select id="filter-by" required>
            <option value="all-reviews"
                <?= $current_review_filter === 'all-reviews' ? 'selected' : '' ?>>
                All reviews
            </option>
            <option value="verified-reviews"
                <?= $current_review_filter === 'verified-reviews' ? 'selected' : '' ?>
            >Verified reviews only
            </option>
        </select>
    </form>

    <div id="reviews">
        <ul>
            <?php
            // print reviews with respective comments
            foreach ($product_reviews as $review) {
                echo "<li>";
                printReview($review);
                echo "</li>";
            }
            ?>
        </ul>
    </div>
</main>

<?= $review_pagination ?>

<script src="/js/product_view.bundle.js"></script>