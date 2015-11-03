<?php
/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\ExtendingPlatformUIConferenceBundle\QueryType;

use eZ\Publish\API\Repository\ContentTypeService;
use eZ\Publish\API\Repository\Values\Content\LocationQuery;
use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\Core\QueryType\OptionsResolverBasedQueryType;
use eZ\Publish\Core\QueryType\QueryType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;

class ContentByType extends OptionsResolverBasedQueryType implements QueryType
{
    /**
     * @var \eZ\Publish\API\Repository\ContentTypeService
     */
    private $contentTypeService;

    public function __construct(ContentTypeService $contentTypeService)
    {
        $this->contentTypeService = $contentTypeService;
    }

    protected function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefined(['type', 'offset', 'limit']);
        $optionsResolver->setDefaults(['limit' => 10, 'offset' => 0]);
    }

    protected function doGetQuery(array $parameters)
    {
        $query = new LocationQuery();
        if (isset($parameters['type'])) {
            $type = $this->contentTypeService->loadContentTypeByIdentifier($parameters['type']);
            $query->query = new Criterion\ContentTypeIdentifier($type->identifier);
        } else {
            $query->query = new Criterion\Subtree('/1/');
        }
        $query->offset = (int)$parameters['offset'];
        $query->limit = $parameters['limit'];

        return $query;
    }

    public static function getName()
    {
        return 'ContentByType';
    }
}
