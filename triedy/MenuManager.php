<?php

class MenuManager {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllItems() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM menu_items ORDER BY category, name");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Nastala chyba pri načítaní menu. Prosím, skúste to neskôr.";
            return [];
        }
    }

    public function categorizeItems($menu_items) {
        $categories = ['Predjedlá' => [], 'Hlavné jedlá' => [], 'Dezerty' => [], 'Nápoje' => []];

        foreach ($menu_items as $item) {
            $cat = $item['category'];
            if (!isset($categories[$cat])) {
                $categories[$cat] = [];
            }
            $categories[$cat][] = $item;
        }

        return $categories;
    }
}
