<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\Page\Output\Visitor\Layouts;

use Netgen\Layouts\API\Values\Block\Block;

abstract class BlockVisitor
{
    /**
     * @return array<string, mixed>
     */
    protected function visitBasicProperties(Block $block): array
    {
        $basicProperties = [
            'id' => $block->getId()->toString(),
            'type' => $block->getDefinition()->getIdentifier(),
        ];

        $basicProperties['cssClass'] = $block->hasParameter('css_class') ?
            $block->getParameter('css_class')->getValue() ?? '' :
            '';

        $basicProperties['cssId'] = $block->hasParameter('css_id') ?
            $block->getParameter('css_id')->getValue() ?? '' :
            '';

        $basicProperties['setContainer'] = $block->hasParameter('set_container') ?
            $block->getParameter('set_container')->getValue() ?? false :
            false;

        $basicProperties['setContainerSize'] = $block->hasParameter('set_container_size') ?
            $block->getParameter('set_container_size')->getValue() ?? '' :
            '';

        $basicProperties['verticalWhitespaceEnabled'] = $block->hasParameter('vertical_whitespace:enabled') ?
            $block->getParameter('vertical_whitespace:enabled')->getValue() ?? false :
            false;

        $basicProperties['verticalWhitespaceTop'] = $block->hasParameter('vertical_whitespace:top') ?
            $block->getParameter('vertical_whitespace:top')->getValue() ?? '' :
            '';

        $basicProperties['verticalWhitespaceBottom'] = $block->hasParameter('vertical_whitespace:bottom') ?
            $block->getParameter('vertical_whitespace:bottom')->getValue() ?? '' :
            '';

        return $basicProperties;
    }
}
