<?php

namespace Labstag\Annotation;

use Attribute;
use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final class IgnoreSoftDelete extends Annotation
{
}
