<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\Ibexa;

use Ibexa\Contracts\Core\Repository\URLAliasService;
use Ibexa\Contracts\Core\Repository\Values\Content\URLAlias;
use Ibexa\Contracts\Core\SiteAccess\ConfigResolverInterface;
use Ibexa\Core\MVC\Symfony\Routing\Generator\UrlAliasGenerator;
use Netgen\IbexaSiteApi\API\LoadService;
use Netgen\IbexaSiteApi\API\Values\Location;

use function sprintf;
use function trim;

final class UrlAliasLocationResolver
{
    public function __construct(
        private URLAliasService $urlAliasService,
        private UrlAliasGenerator $urlAliasGenerator,
        private LoadService $loadService,
        private ConfigResolverInterface $configResolver,
    ) {}

    public function resolveLocation(string $path): Location
    {
        $urlAlias = $this->getUrlAlias(sprintf('/%s', trim($path, '/')));

        if ($urlAlias->type === URLAlias::LOCATION) {
            return $this->loadService->loadLocation($urlAlias->destination);
        }

        return $this->resolveLocation($urlAlias->destination);
    }

    private function getUrlAlias(string $path): URLAlias
    {
        $rootLocationId = (int) $this->configResolver->getParameter('content.tree_root.location_id');
        $pathPrefix = $this->urlAliasGenerator->getPathPrefixByRootLocationId($rootLocationId);

        if ($pathPrefix === '/' || $this->urlAliasGenerator->isUriPrefixExcluded($path)) {
            return $this->urlAliasService->lookup($path);
        }

        return $this->urlAliasService->lookup($pathPrefix . $path);
    }
}
