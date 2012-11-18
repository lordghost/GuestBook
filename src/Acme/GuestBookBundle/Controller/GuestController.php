<?php

namespace Acme\GuestBookBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Acme\GuestBookBundle\Entity\Guest;
use Acme\GuestBookBundle\Form\GuestType;

/**
 * Guest controller.
 *
 */
class GuestController extends Controller
{
    /**
     * Lists all Guest entities.
     *
     */
    public function indexAction($page=1)
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('GuestBookBundle:Guest')->findAll();
        $adapter = new ArrayAdapter(array_reverse($entities));

        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(10);
        $pagerfanta->setCurrentPage($page);
        $entity = new Guest();
        $form = $this->createForm(new GuestType(), $entity);
        return $this->render('GuestBookBundle:Guest:index.html.twig', array(
            'entities' => $pagerfanta->getCurrentPageResults(),
            'form' => $form->createView(),
            'my_pager' => $pagerfanta,
         ));
    }

    /**
     * Finds and displays a Guest entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GuestBookBundle:Guest')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Guest entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('GuestBookBundle:Guest:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new Guest entity.
     *
     */
    public function newAction()
    {
        $entity = new Guest();
        $form   = $this->createForm(new GuestType(), $entity);

        return $this->render('GuestBookBundle:Guest:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new Guest entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new Guest();
        $form = $this->createForm(new GuestType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('guest_show', array('id' => $entity->getId())));
        }

        return $this->render('GuestBookBundle:Guest:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Guest entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GuestBookBundle:Guest')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Guest entity.');
        }

        $editForm = $this->createForm(new GuestType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('GuestBookBundle:Guest:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Guest entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GuestBookBundle:Guest')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Guest entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new GuestType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('guest_edit', array('id' => $id)));
        }

        return $this->render('GuestBookBundle:Guest:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Guest entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('GuestBookBundle:Guest')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Guest entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('guest'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
