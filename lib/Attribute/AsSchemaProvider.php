<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\Attribute;

use Attribute;

/**
 * Service tag to autoconfigure schema providers.
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class AsSchemaProvider {}
