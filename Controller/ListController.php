<?php

namespace EzSystems\ExtendingPlatformUIConferenceBundle\Controller;

use EzSystems\PlatformUIBundle\Controller\Controller as BaseController;
use eZ\Publish\API\Repository\SearchService;
use eZ\Publish\API\Repository\Values\Content\LocationQuery;
use  eZ\Publish\API\Repository\Values\Content\Query\Criterion;

class ListController extends BaseController
{
    private $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function listAction($offset)
    {
        $limit = 10;
        $query = new LocationQuery();
        $query->query = new Criterion\Subtree('/1/');
        $query->offset = (int)$offset;
        $query->limit = $limit;
        $results = $this->searchService->findLocations($query);

        $previous = false;
        $next = false;
        if ( $offset > 0 ) {
            $previous = max(0, $offset - $limit);
        }
        if ( ($offset + $limit) < $results->totalCount ) {
            $next = $offset + $limit;
        }
        return $this->render('EzSystemsExtendingPlatformUIConferenceBundle:List:list.html.twig', [
            'results' => $results,
            'previous' => $previous,
            'next' => $next,
        ]);
    }

}
