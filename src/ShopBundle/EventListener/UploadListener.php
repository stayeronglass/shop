<?php
namespace ShopBundle\EventListener;

use Doctrine\Common\Persistence\ObjectManager;
use Oneup\UploaderBundle\Event\PostPersistEvent;

class UploadListener
{
    /**
     * @var ObjectManager
     */
    private $om;

    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }


    public function onUpload(PostPersistEvent $event)
    {
        $response = $event->getResponse();
        $response['success'] = true;

        return $response;
    }
}