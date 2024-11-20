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

        $basicProperties['css_class'] = $block->hasParameter('css_class') ?
            $block->getParameter('css_class')->getValue() :
            '';

        $basicProperties['css_id'] = $block->hasParameter('css_id') ?
            $block->getParameter('css_id')->getValue() :
            '';

        $basicProperties['set_container'] = $block->hasParameter('set_container') ?
            $block->getParameter('set_container')->getValue() :
            false;

        $basicProperties['set_container_size'] = $block->hasParameter('set_container_size') ?
            $block->getParameter('set_container_size')->getValue() :
            '';

        $basicProperties['vertical_whitespace_enabled'] = $block->hasParameter('vertical_whitespace:enabled') ?
            $block->getParameter('vertical_whitespace:enabled')->getValue() :
            false;

        $basicProperties['vertical_whitespace_top'] = $block->hasParameter('vertical_whitespace:top') ?
            $block->getParameter('vertical_whitespace:top')->getValue() :
            '';

        $basicProperties['vertical_whitespace_bottom'] = $block->hasParameter('vertical_whitespace:bottom') ?
            $block->getParameter('vertical_whitespace:bottom')->getValue() :
            '';

        return $basicProperties;
    }
}
