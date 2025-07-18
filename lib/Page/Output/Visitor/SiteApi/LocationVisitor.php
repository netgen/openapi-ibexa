<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\Page\Output\Visitor\SiteApi;

use Ibexa\HttpCache\Handler\TagHandler;
use Netgen\IbexaSiteApi\API\Values\Location;
use Netgen\OpenApiIbexa\Page\Output\OutputVisitor;
use Netgen\OpenApiIbexa\Page\Output\VisitorInterface;

use function array_map;

/**
 * @implements \Netgen\OpenApiIbexa\Page\Output\VisitorInterface<\Netgen\IbexaSiteApi\API\Values\Location>
 */
final class LocationVisitor implements VisitorInterface
{
    public function __construct(
        private TagHandler $tagHandler,
    ) {}

    public function accept(object $value): bool
    {
        return $value instanceof Location;
    }

    /**
     * @return iterable<string, mixed>
     */
    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): iterable
    {
        $this->tagHandler->addLocationTags([$value->id]);
        $this->tagHandler->addContentTags([$value->contentId]);

        return [
            'id' => $value->id,
            'type' => 'location',
            'remoteId' => $value->remoteId,
            'contentId' => $value->contentId,
            'parentLocationId' => $value->parentLocationId,
            'pathString' => $value->pathString,
            'pathArray' => array_map('intval', $value->pathArray),
            'depth' => $value->depth,
            'path' => $value->path->getAbsolute(),
            'url' => $value->url->get(),
        ];
    }
}
