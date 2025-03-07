<?php
class Page {
    protected $items;
    protected $items_per_page;

    public function __construct($items_per_page) {
        $this->items = [];
        $this->items_per_page = $items_per_page;
    }

    public function addItem($item) {
        $this->items[] = $item; 
    }

    public function afficher_pagination($items, $items_per_page) {
        $num_pages = ceil(count($items) / $items_per_page);
        $current_page = 0;

        while ($current_page < $num_pages) {
            $start_index = $current_page * $items_per_page;
            $end_index = $start_index + $items_per_page;
            for ($i = $start_index; $i < $end_index && $i < count($items); $i++) {
                echo $items[$i] . "<br>";
            }

            if ($current_page < $num_pages - 1) {
                echo "Page " . ($current_page + 1) . "/" . $num_pages . ". Appuyez sur Entrée pour continuer...<br>";
                // fgets(STDIN);
            }
            $current_page++;
        }
    }
    //chercher LIMIT() sur SQL pour la gination
}