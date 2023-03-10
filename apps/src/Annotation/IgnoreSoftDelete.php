<?php

namespace Labstag\Annotation;

use Attribute;

/**
 * @Annotation
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final class IgnoreSoftDelete
{
}
