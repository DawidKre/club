<?php
/**
 * Created by PhpStorm.
 * User: dawid
 * Date: 25.05.16
 * Time: 08:56
 */

namespace Club\BlogBundle\Pagination;


class PaginatedCollection
{
    private $items;
    private $total;
    private $count;

    private $_links = [];

    /**
     * PaginatedCollection constructor.
     * @param $items
     * @param $total
     */
    public function __construct($items, $total)
    {
        $this->items = $items;
        $this->total = $total;
        $this->count = count($items);
    }

    public function addLink($rel, $url)
    {
        $this->_links[$rel] = $url;
    }

}