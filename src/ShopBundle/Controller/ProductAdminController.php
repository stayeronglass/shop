<?php

namespace ShopBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductAdminController extends CRUDController
{

    public function step1Action(){
        $object = $this->admin->getSubject();

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $this->get('request')->get($this->admin->getIdParameter())));
        }

        $this->admin->create($object);

        $this->addFlash('sonata_flash_success', 'Saved successfully');

        return new RedirectResponse($this->admin->generateUrl('step2'));

    }

    public function step2Action($id = null)
    {
        $id = $this->get('request')->get($this->admin->getIdParameter());

        $object = $this->admin->getObject($id);

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

    }
    public function applyAction()
    {

    }
}
