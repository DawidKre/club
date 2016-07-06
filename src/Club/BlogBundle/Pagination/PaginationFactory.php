<?php
/**
 * Created by PhpStorm.
 * User: dawid
 * Date: 25.05.16
 * Time: 09:27
 */

namespace Club\BlogBundle\Pagination;


use Knp\Component\Pager\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class PaginationFactory
{
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(RouterInterface $router)
    {

        $this->router = $router;
    }

    public function createCollection($qb, Request $request, $route, array $routeParams = [])
    {
        $page = $request->query->get('page', 1);

        $pageRange = 5;
        $paginator = new Paginator();
        $pagination = $paginator->paginate($qb, $page, $pageRange);

        $data = [];
        foreach ($pagination as $item) {
            $data[] = $item;
        }
        $total = count($qb);
        $lastPage = ceil($total / $pageRange);

        $paginatedCollection = new PaginatedCollection(
            $data,
            $total
        );

        $routeParams = array_merge($routeParams, $request->query->all());
        $createLinkUrl = function ($targetPage) use ($route, $routeParams) {
            return $this->router->generate($route, array_merge(
                $routeParams,
                ['page' => $targetPage]
            ));
        };

        $paginatedCollection->addLink('self', $createLinkUrl($page));
        $paginatedCollection->addLink('first', $createLinkUrl(1));
        $paginatedCollection->addLink('last', $createLinkUrl($lastPage));

        if ($page != $lastPage) {
            $paginatedCollection->addLink('next', $createLinkUrl($page + 1));
        }
        if ($page > 1) {
            $paginatedCollection->addLink('prev', $createLinkUrl($page - 1));
        }

        return $paginatedCollection;
    }

}