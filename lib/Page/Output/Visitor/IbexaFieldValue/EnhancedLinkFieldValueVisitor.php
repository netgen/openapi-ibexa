<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\Page\Output\Visitor\IbexaFieldValue;

use Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException;
use Netgen\IbexaFieldTypeEnhancedLink\FieldType\Value as EnhancedLinkValue;
use Netgen\IbexaSiteApi\API\LoadService;
use Netgen\OpenApiIbexa\Page\Output\OutputVisitor;
use Netgen\OpenApiIbexa\Page\Output\VisitorInterface;

/**
 * @implements \Netgen\OpenApiIbexa\Page\Output\VisitorInterface<\Netgen\IbexaFieldTypeEnhancedLink\FieldType\Value>
 */
final class EnhancedLinkFieldValueVisitor implements VisitorInterface
{
    public function __construct(
        private LoadService $loadService,
    ) {}

    public function accept(object $value): bool
    {
        return $value instanceof EnhancedLinkValue;
    }

    /**
     * @return iterable<string, mixed>
     */
    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): iterable
    {
        $reference = $value->isTypeInternal() ? $this->resolveInternalReference($value->reference) : $value->reference;

        return [
            'target' => $value->target,
            'label' => $value->label,
            'reference' => $reference,
            'suffix' => $value->suffix,
        ];
    }

    private function resolveInternalReference(int $reference): ?string
    {
        try {
            return $this->loadService->loadContent($reference)->mainLocation?->url->get();
        } catch (NotFoundException) {
            return null;
        }
    }
}
