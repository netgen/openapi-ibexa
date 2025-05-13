<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenApiIbexaBundle;

use Netgen\Bundle\OpenApiIbexaBundle\DependencyInjection\CompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class NetgenOpenApiIbexaBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new CompilerPass\ContentTypeSchemaProviderPass());
    }
}
