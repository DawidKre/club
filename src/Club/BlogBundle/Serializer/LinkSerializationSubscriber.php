<?php
/**
 * Created by PhpStorm.
 * User: dawid
 * Date: 25.05.16
 * Time: 10:23
 */

namespace Club\BlogBundle\Serializer;


use Club\BlogBundle\Annotation\Links;
use Club\BlogBundle\Entity\Category;
use Doctrine\Common\Annotations\Reader;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\JsonSerializationVisitor;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\Routing\RouterInterface;


class LinkSerializationSubscriber implements EventSubscriberInterface
{
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var Reader
     */
    private $annotationReader;

    private $expressionLanguage;


    /**
     * LinkSerializationSubscriber constructor.
     * @param RouterInterface $router
     * @param Reader $annotationReader
     */
    public function __construct(RouterInterface $router, Reader $annotationReader)
    {

        $this->router = $router;
        $this->annotationReader = $annotationReader;
        $this->expressionLanguage = new ExpressionLanguage();
    }

    public function onPostSerialize(ObjectEvent $event)
    {
        /** @var JsonSerializationVisitor $visitor */
        $visitor = $event->getVisitor();

        $object = $event->getObject();
        $annotations = $this->annotationReader
            ->getClassAnnotations(new \ReflectionObject($object));

        $links = [];
        foreach ($annotations as $annotation) {
            if ($annotation instanceof Links) {
                $uri = $this->router->generate(
                    $annotation->route,
                    $this->resolveParams($annotation->params, $object)
                );
                $links[$annotation->slug] = $uri;
            }
        }
        if ($links) {
            $visitor->addData('_link', $links);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            [
                'event' => 'serializer.post_serialize',
                'method' => 'onPostSerialize',
                'format' => 'json',
            ]
        ];
    }

    private function resolveParams(array $params, $object)
    {
        foreach ($params as $key => $param) {
            $params[$key] = $this->expressionLanguage
                ->evaluate($param, array('object' => $object));
        }

        return $params;
    }
}