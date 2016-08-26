<?php

class PagesLinks {
    private $links = array();

    public function __construct($link, $currentPage, $totalRows, $itemsPerPage, $pagesToSide) {
        $pagesCount = ceil($totalRows / $itemsPerPage);
        if ($currentPage > $pagesCount) {
            $currentPage = $pagesCount;
        }

        if ($currentPage > 1) {
            array_push($this->links, array(
                'text' => '&lt;',
                'link' => $link . ($currentPage - 1),
                'arrow' => true
            ));
        }
        $k = $currentPage - $pagesToSide < 1 ? 1 : $currentPage - $pagesToSide;
        for ($i = $k; $i < $currentPage; $i++) {
            array_push($this->links, array(
                'text' => $i,
                'link' => $link . $i
            ));
        }
        array_push($this->links, array(
            'text' => $currentPage,
            'link' => NULL
        ));
        $k = $pagesCount > $currentPage + $pagesToSide ? $currentPage + $pagesToSide : $pagesCount;
        for ($i = $currentPage + 1; $i <= $k; $i++) {
            array_push($this->links, array(
                'text' => $i,
                'link' => $link . $i
            ));
        }
        if ($currentPage < $pagesCount) {
            array_push($this->links, array(
                'text' => '&gt;',
                'link' => $link . ($currentPage + 1),
                'arrow' => true
            ));
        }
    }

    public function getLinks() {
        return $this->links;
    }
}
