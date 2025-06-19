<?php
class AddReview {
    private $pdo;
    private $user_id;
    private $rating;
    private $review_text;
    private $errors = [];

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function setUserId($user_id) {
        $this->user_id = $user_id;
    }

    public function setReviewData($rating, $review_text) {
        $this->rating = $rating;
        $this->review_text = trim($review_text);
    }

    public function validate() {
        if (!filter_var($this->rating, FILTER_VALIDATE_INT) || $this->rating < 1 || $this->rating > 5) {
            $this->errors[] = "Hodnotenie musí byť číslo od 1 do 5.";
        }

        if (empty($this->review_text)) {
            $this->errors[] = "Text recenzie nemôže byť prázdny.";
        } elseif (strlen($this->review_text) > 1000) {
            $this->errors[] = "Recenzia je príliš dlhá (max. 1000 znakov).";
        }

        return empty($this->errors);
    }

    public function save() {
        if (!$this->validate()) {
            return false;
        }
        try {
            $stmt = $this->pdo->prepare("INSERT INTO reviews (user_id, rating, review_text) VALUES (:user_id, :rating, :review_text)");
            return $stmt->execute([
                'user_id' => $this->user_id,
                'rating' => $this->rating,
                'review_text' => $this->review_text
            ]);
        } catch (PDOException $e) {
            $this->errors[] = "Chyba databázy: " . $e->getMessage();
            return false;
        }
    }

    public function getErrors() {
        return $this->errors;
    }
}
