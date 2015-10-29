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
        $query = new LocationQuery();
        $query->query = new Criterion\Subtree('/1/');
        $query->offset = (int)$offset;
        return $this->render('EzSystemsExtendingPlatformUIConferenceBundle:List:list.html.twig', [
            'results' => $this->searchService->findLocations($query),
        ]);
    }

}
