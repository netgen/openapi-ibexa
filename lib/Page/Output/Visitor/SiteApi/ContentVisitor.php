<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\Page\Output\Visitor\SiteApi;

use Netgen\IbexaSiteApi\API\Values\Content;
use Netgen\OpenApiIbexa\Page\Output\OutputVisitor;
use Netgen\OpenApiIbexa\Page\Output\VisitorInterface;

/**
 * @implements \Netgen\OpenApiIbexa\Page\Output\VisitorInterface<\Netgen\IbexaSiteApi\API\Values\Content>
 */
final class ContentVisitor implements VisitorInterface
{
    public function accept(object $value): bool
    {
        return $value instanceof Content;
    }

    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): iterable
    {
        return [
            'id' => $value->contentInfo->id,
            'type' => 'content',
            'remoteId' => $value->contentInfo->remoteId,
            'mainLocationId' => $value->contentInfo->mainLocationId,
            'name' => $value->contentInfo->name,
            'languageCode' => $value->contentInfo->languageCode,
            'contentType' => $value->contentInfo->contentTypeIdentifier,
            'fields' => (static function (Content $content) use ($outputVisitor) {
                foreach ($content->fields as $fieldIdentifier => $field) {
                    yield $fieldIdentifier => $outputVisitor->visit($field);
                }
            })($value),
        ];
    }
}
