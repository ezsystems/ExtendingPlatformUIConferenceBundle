<?php

namespace EzSystems\ExtendingPlatformUIConferenceBundle\Controller;

use eZ\Publish\Core\QueryType\ArrayQueryTypeRegistry;
use eZ\Publish\Core\QueryType\QueryTypeRegistry;
use EzSystems\PlatformUIBundle\Controller\Controller as BaseController;
use eZ\Publish\API\Repository\SearchService;
use eZ\Publish\API\Repository\ContentTypeService;
use eZ\Publish\API\Repository\Values\Content\LocationQuery;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;

class ListController extends BaseController
{
    private $searchService;

    private $contentTypeService;

    public function __construct(SearchService $searchService, ContentTypeService $contentTypeService, ArrayQueryTypeRegistry $queryTypeRegistry)
    {
        $this->searchService = $searchService;
        $this->contentTypeService = $contentTypeService;
        $this->queryTypeRegistry = $queryTypeRegistry;
    }

    public function listAction($offset, $typeIdentifier)
    {
        $typesByGroup = [];
        $typesById = [];
        $groups = $this->contentTypeService->loadContentTypeGroups();
        foreach($groups as $group) {
            $typesByGroup[$group->identifier] = $this->contentTypeService->loadContentTypes($group);
            foreach($typesByGroup[$group->identifier] as $type) {
                $typesById[$type->id] = $type;
            }
        }

        $queryParameters = ['offset' => (int)$offset, 'limit' => 10];
        if ($typeIdentifier) {
            $queryParameters['type'] = $this->contentTypeService->loadContentTypeByIdentifier($typeIdentifier)->identifier;
        }
        $results = $this->searchService->findLocations(
            $this->queryTypeRegistry->getQueryType('ContentByType')->getQuery($queryParameters)
        );

        $previous = false;
        $next = false;
        if ( $offset > 0 ) {
            $previous = max(0, $offset - $queryParameters['limit']);
        }
        if ( ($offset + $queryParameters['limit']) < $results->totalCount ) {
            $next = $offset + $queryParameters['limit'];
        }
        return $this->render('EzSystemsExtendingPlatformUIConferenceBundle:List:list.html.twig', [
            'groups' => $groups,
            'typesByGroup' => $typesByGroup,
            'typesById' => $typesById,
            'typeIdentifier' => $typeIdentifier,
            'contentType' => $type,
            'results' => $results,
            'previous' => $previous,
            'next' => $next,
        ]);
    }

}
