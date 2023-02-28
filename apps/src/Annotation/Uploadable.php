<?php

namespace Labstag\Annotation;

use Attribute;
use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class Uploadable
{
}
