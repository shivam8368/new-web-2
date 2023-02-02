<?php
namespace App\Services;

class PaginationService {

    private int $currentPage;
    private int $totalRecords;

    /** Setting default URL where to redirect (%d represent page number) */
    private string $href;

    /** How many pages will be displayed on the left and right from current page */
    private int $range = 5;

    /** Count of record on one page */
    private int $recordsPerPage;

    /**
     * Default style for display inactive page
     * 1. %s - href
     * 2. %s - page number
     * @var string
     */
    private string $defaultNumberStyle = "<a class='page-numbers' href='%s'>%s</a>";

    /**
     * Default style from display active page
     * 1. %d - page number
     * @var string
     */
    private string $defaultNumberActiveStyle = "<span class='page-numbers current'>%s</span>";


    public function setCurrentPage(int $page): void
    {
        $this->currentPage = $page;
    }

    function setTotalRecords(int $total): void
    {
        $this->totalRecords = $total;
    }

    public function setHref(string $href): void
    {
        $this->href = urldecode($href);
    }

    public function setRange(int $range): void
    {
        $this->range = $range;
    }

    public function setRecordsPerPage(int $perPage): void
    {
        $this->recordsPerPage = $perPage;
    }

    public function setNumberStyle(string $html): void
    {
        $this->defaultNumberStyle = $html;
    }

    public function setNumberStyleActive(string $html): void
    {
        $this->defaultNumberActiveStyle = $html;
    }

    public function isPagesAvailable(): bool
    {
        return $this->recordsPerPage <= $this->totalRecords;
    }

    public function draw(): string
    {
        if($this->recordsPerPage > $this->totalRecords) {
            return "";
        }

        $countOfPages = ceil($this->totalRecords / $this->recordsPerPage);

        if($this->currentPage < 1) {$this->currentPage = 1;}
        if($this->currentPage > $countOfPages) {$this->currentPage = $countOfPages;}

        $output = "";

        // Show < when user is not at page 1
        if($this->currentPage != 1) {
            $output .= sprintf($this->defaultNumberStyle, sprintf($this->href, $this->currentPage - 1), '<');
        }

        // Show three dots and page 1
        if($this->currentPage > $this->range + 1) {
            $output .= sprintf($this->defaultNumberStyle, sprintf($this->href, 1), 1);
            $output .= '...';
        }

        // Show all pages before this page by Range
        $pagesBeforeThis = 0;

        if($this->currentPage != 1) {
            for($i = $this->currentPage - $this->range; $i < $this->currentPage; $i++) {
                if($i < 1) {
                    continue;
                }

                $output .= sprintf($this->defaultNumberStyle, sprintf($this->href, $i), $i);
                $pagesBeforeThis++;
            }
        }

        // Show actual page as active
        $output .= sprintf($this->defaultNumberActiveStyle, $this->currentPage);


        // Show all pages after this page by Range
        if($this->currentPage != $countOfPages) {
            for($i = $this->currentPage + 1; $i <= $countOfPages; $i++) {
                if($this->currentPage + $this->range + ($this->range - $pagesBeforeThis) < $i) {
                    break;
                }

                $output .= sprintf($this->defaultNumberStyle, sprintf($this->href, $i), $i);
            }
        }


        // Show > when user is not at last page
        if($this->currentPage != $countOfPages) {
            $output .= sprintf($this->defaultNumberStyle, sprintf($this->href, $this->currentPage + 1), '>');
        }

        return $output;
    }
}
