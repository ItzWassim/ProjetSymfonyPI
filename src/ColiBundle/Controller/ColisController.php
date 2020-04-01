<?php

namespace ColiBundle\Controller;

use ColiBundle\Entity\Colis;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\Routing\Annotation\Route;

/**
 * Coli controller.
 *
 * @Route("colis")
 */
class ColisController extends Controller
{
    /**
     * Lists all coli entities.
     *
     * @Route("/", name="colis_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $colis = $em->getRepository('ColiBundle:Colis')->findAll();

        return $this->render('colis/colisList.html.twig', array(
            'colis' => $colis,
        ));
    }

    /**
     * Creates a new coli entity.
     *
     * @Route("/new", name="colis_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $coli = new Colis();
        $form = $this->createForm('ColiBundle\Form\ColisType', $coli);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($coli);
            $em->flush();

            return $this->redirectToRoute('colis_show', array('id' => $coli->getId()));
        }

        return $this->render('colis/new.html.twig', array(
            'coli' => $coli,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a coli entity.
     *
     * @Route("/{id}", name="colis_show")
     * @Method("GET")
     */
    public function showAction(Colis $coli)
    {
        $deleteForm = $this->createDeleteForm($coli);

        return $this->render('colis/show.html.twig', array(
            'coli' => $coli,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing coli entity.
     *
     * @Route("/{id}/edit", name="colis_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Colis $coli)
    {
        $deleteForm = $this->createDeleteForm($coli);
        $editForm = $this->createForm('ColiBundle\Form\ColisType', $coli);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('colis_edit', array('id' => $coli->getId()));
        }

        return $this->render('colis/edit.html.twig', array(
            'coli' => $coli,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a coli entity.
     *
     * @Route("/{id}", name="colis_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Colis $coli)
    {
        $form = $this->createDeleteForm($coli);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($coli);
            $em->flush();
        }

        return $this->redirectToRoute('colis_index');
    }

    /**
     * Creates a form to delete a coli entity.
     *
     * @param Colis $coli The coli entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Colis $coli)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('colis_delete', array('id' => $coli->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
