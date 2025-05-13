<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\Page\Output\Visitor\SiteApi;

use Netgen\IbexaSiteApi\API\Values\Content;
use Netgen\OpenApiIbexa\Page\Output\OutputVisitor;
use Netgen\OpenApiIbexa\Page\Output\VisitorInterface;

use function array_map;

/**
 * @implements \Netgen\OpenApiIbexa\Page\Output\VisitorInterface<\Netgen\IbexaSiteApi\API\Values\Content>
 */
final class ContentVisitor implements VisitorInterface
{
    /**
     * @param array<string, \Netgen\OpenApiIbexa\Page\Output\Visitor\SiteApi\ContentTypePartProviderInterface[]> $contentTypePartProviders
     */
    public function __construct(
        private array $contentTypePartProviders,
    ) {}

    public function accept(object $value): bool
    {
        return $value instanceof Content;
    }

    /**
     * @return iterable<string, mixed>
     */
    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): iterable
    {
        $contentTypeParts = [];

        foreach ($this->contentTypePartProviders[$value->contentInfo->contentTypeIdentifier] ?? [] as $contentTypePartProvider) {
            $contentTypeParts = [...$contentTypeParts, ...$contentTypePartProvider->provideContentTypeParts($value)];
        }

        return [
            'id' => $value->contentInfo->id,
            'type' => 'content',
            'remoteId' => $value->contentInfo->remoteId,
            'mainLocationId' => $value->contentInfo->mainLocationId,
            'name' => $value->contentInfo->name,
            'languageCode' => $value->contentInfo->languageCode,
            'contentType' => $value->contentInfo->contentTypeIdentifier,
            'fields' => [...$this->visitFields($value, $outputVisitor)],
        ] + array_map(
            static fn (mixed $value): mixed => $outputVisitor->visit($value),
            $contentTypeParts,
        );
    }

    /**
     * @return iterable<string, mixed>
     */
    private function visitFields(Content $content, OutputVisitor $outputVisitor): iterable
    {
        foreach ($content->fields as $fieldIdentifier => $field) {
            yield $fieldIdentifier => $outputVisitor->visit($field);
        }
    }
}
