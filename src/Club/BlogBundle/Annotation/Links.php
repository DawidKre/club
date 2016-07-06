<?php
/**
 * Created by PhpStorm.
 * User: dawid
 * Date: 25.05.16
 * Time: 10:55
 */

namespace Club\BlogBundle\Annotation;

use Doctrine\Common\Annotations\Annotation\Required;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 *
 * @Annotation
 * @Target("CLASS")
 */
class Links
{
    /**
     * @Required()
     */
    public $slug;

    /**
     * @Required()
     */
    public $route;

    public $params = [];
}