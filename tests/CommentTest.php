<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Steamy\Model\Comment;
use Steamy\Model\Review;
use Steamy\Model\Location;
use Steamy\Model\Client;
use Steamy\Core\Database;

Class CommentTest extends TestCase
{
    use Database;
    private ?Comment $dummy_comment;
    private ?Review $dummy_review;
    private ?Client $reviewer;

    public function setUp(): void
    {

                // create a client object and save to database
        $this->reviewer = new Client(
            "divyesh@gmail.com", "divyesh", "jokokho", "Us4sdcsdjkcksdsdv",
            "748737292", new Location("Royal", "Curepipe", 1)
        );

        $success = $this->reviewer->save();
        if (!$success) {
            throw new Exception('Unable to save client');
        }

        // create a review object and save to database
        $this->dummy_review = new Review(
            3,
            3,
            $this->reviewer->getUserID(),
            "This is a test test review.",
            5
        );
        $success = $this->dummy_review->save();

        if (!$success) {
            throw new Exception('Unable to save client');
        }

        $this->dummy_comment = new Comment(
            user_id: $this->reviewer->getUserID(),
            review_id: $this->dummy_review->getReviewID(),
            text: 'This is a test comment.',
            created_date: new DateTime()
        );

        $success = $this->dummy_comment->save();

        if (!$success) {
            throw new Exception('Unable to save client');
        }
    }

    public function tearDown(): void
    {
        $this->dummy_review = null;
        $this->reviewer = null;
        $this->dummy_comment = null;

        // clear all data from review and client tables
        self::query('DELETE FROM  comment; DELETE FROM review; DELETE FROM client; DELETE FROM user;');
    }

    public function testConstructor(): void
    {
        self::assertEquals('This is a test comment.', $this->dummy_comment->getText());
    }

    public function testValidate(): void
    {
        // Test for a valid comment
        $errors = $this->dummy_comment->validate();
        self::assertEmpty($errors);

        // Test for an empty comment text
        $this->dummy_comment->setText('');
        $errors = $this->dummy_comment->validate();
        self::assertArrayHasKey('text', $errors);

        // Test for a short comment text
        $this->dummy_comment->setText('A');
        $errors = $this->dummy_comment->validate();
        self::assertArrayHasKey('text', $errors);

        // Test for a future created date
        $futureDate = new DateTime('+1 day');
        $this->dummy_comment->setCreatedDate($futureDate);
        $errors = $this->dummy_comment->validate();
        self::assertArrayHasKey('date', $errors);

        // Test for a non-existing review ID
        $this->dummy_comment->setReviewID(-1);
        $errors = $this->dummy_comment->validate();
        self::assertArrayHasKey('review_id', $errors);

        // Test for a non-existing user ID
        $this->dummy_comment->setUserID(-1);
        $errors = $this->dummy_comment->validate();
        self::assertArrayHasKey('user_id', $errors);

        // Test for a non-existing parent comment ID
        $this->dummy_comment->setParentCommentID(999); // Assuming 999 doesn't exist
        $errors = $this->dummy_comment->validate();
        self::assertArrayHasKey('parent_comment_id', $errors);
    }

    public function testSave(): void
    {
        // Test saving a valid comment
        $saved = $this->dummy_comment->save();
        self::assertTrue($saved);

        // Test saving an invalid comment
        $this->dummy_comment->setText('');
        $saved = $this->dummy_comment->save();
        self::assertFalse($saved);
    }

    public function testGetByID(): void
    {
        // Test fetching an existing comment
        $comment_id = $this->dummy_comment->getCommentID();
        $fetched_comment = Comment::getByID($comment_id);
        self::assertNotNull($fetched_comment);
        self::assertEquals($this->dummy_comment->getUserID(), $fetched_comment->getUserID());
        self::assertEquals($this->dummy_comment->getReviewID(), $fetched_comment->getReviewID());
        self::assertEquals($this->dummy_comment->getText(), $fetched_comment->getText());

        // Test fetching a non-existing comment
        $non_existing_comment = Comment::getByID(-1);
        self::assertNull($non_existing_comment);
    }
}