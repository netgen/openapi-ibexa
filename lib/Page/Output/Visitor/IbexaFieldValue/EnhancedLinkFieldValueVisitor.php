<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\Page\Output\Visitor\IbexaFieldValue;

use Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException;
use Netgen\IbexaFieldTypeEnhancedLink\FieldType\Type as EnhancedLinkType;
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
        $url = $value->reference;
        $path = null;

        if ($value->isTypeInternal()) {
            try {
                $location = $this->loadService->loadContent($value->reference)->mainLocation;
                $url = $location?->url->get();
                $path = $location?->path->getAbsolute();
            } catch (NotFoundException) {
                $url = null;
            }
        }

        return [
            'type' => $value->isTypeInternal()
                ? EnhancedLinkType::LINK_TYPE_INTERNAL
                : EnhancedLinkType::LINK_TYPE_EXTERNAL,
            'target' => $value->target,
            'label' => $value->label,
            'url' => $url,
            'path' => $path,
            'suffix' => $value->suffix,
        ];
    }
}
