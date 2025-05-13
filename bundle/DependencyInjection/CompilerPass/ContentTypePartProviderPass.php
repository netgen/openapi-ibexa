<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenApiIbexaBundle\DependencyInjection\CompilerPass;

use RuntimeException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

use function sprintf;

final class ContentTypePartProviderPass implements CompilerPassInterface
{
    private const SERVICE_NAME = 'netgen.openapi_ibexa.page.output.visitor.site_api.content';
    private const TAG_NAME = 'netgen.openapi_ibexa.ibexa.content_type_part_provider';

    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(self::SERVICE_NAME)) {
            return;
        }

        $services = [];

        foreach ($container->findTaggedServiceIds(self::TAG_NAME) as $id => $tags) {
            foreach ($tags as $tag) {
                $contentType = $tag['content_type'] ??
                    throw new RuntimeException(
                        sprintf(
                            'Service "%s" must define a "content_type" attribute for "%s" tag.',
                            $id,
                            self::TAG_NAME,
                        ),
                    );

                $services[$contentType][] = new Reference($id);
            }
        }

        $container
            ->findDefinition(self::SERVICE_NAME)
            ->setArgument(0, $services);
    }
}
