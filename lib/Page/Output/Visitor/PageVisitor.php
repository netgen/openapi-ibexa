<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\Page\Output\Visitor;

use Ibexa\Contracts\Core\SiteAccess\ConfigResolverInterface;
use Netgen\OpenApiIbexa\Page\Output\OutputVisitor;
use Netgen\OpenApiIbexa\Page\Output\VisitorInterface;
use Netgen\OpenApiIbexa\Page\Page;

/**
 * @implements \Netgen\OpenApiIbexa\Page\Output\VisitorInterface<\Netgen\OpenApiIbexa\Page\Page>
 */
final class PageVisitor implements VisitorInterface
{
    public function __construct(
        private ConfigResolverInterface $configResolver,
    ) {}

    public function accept(object $value): bool
    {
        return $value instanceof Page;
    }

    /**
     * @return iterable<string, mixed>
     */
    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): iterable
    {
        /** @var array<string, array{reference_name: string, required: bool, enabled: bool}> $pageSchemaConfig */
        $pageSchemaConfig = $this->configResolver->getParameter('page_schema', 'netgen_open_api_ibexa');

        if ($pageSchemaConfig['content']['enabled'] && $value->getContent() !== null) {
            yield 'content' => $outputVisitor->visit($value->getContent());
        }

        if ($pageSchemaConfig['location']['enabled'] && $value->getLocation() !== null) {
            yield 'location' => $outputVisitor->visit($value->getLocation());
        }

        if ($pageSchemaConfig['layout']['enabled'] && $value->getLayout() !== null) {
            yield 'layout' => $outputVisitor->visit($value->getLayout());
        }

        foreach ($value->getPageParts() as $identifier => $pagePart) {
            yield $identifier => $outputVisitor->visit($pagePart);
        }
    }
}
