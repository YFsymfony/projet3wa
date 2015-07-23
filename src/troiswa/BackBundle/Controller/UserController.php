<?php

namespace troiswa\BackBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use troiswa\BackBundle\Entity\User;
use troiswa\BackBundle\Form\UserType;
use troiswa\BackBundle\Form\UserEditAdminType;

/**
 * User controller.
 *
 */
class UserController extends Controller
{

    /**
     * Lists all User entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('troiswaBackBundle:User')->findAll();

        return $this->render('troiswaBackBundle:User:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * formulaire de création d'un compte coté admin
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function signinAction(Request $request)
    {
        $entity = new User();
        $formSignin = $this->createCreateForm($entity);
        $formSignin->handleRequest($request);

        if ($formSignin->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            // cryptage du mot de passe
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($entity);
            $newPassword = $encoder->encodePassword($entity->getPassword(), $entity->getSalt());
            $entity->setPassword($newPassword);

            //attribution du role
            $roles = $em->getRepository('troiswaBackBundle:Role')->findOneByName('client');
            $entity->addRole($roles);

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('troiswa_back_administration'));
        }

        return $this->render("troiswaBackBundle:User:signin.html.twig",['entity' => $entity,'formSignin'=> $formSignin->createView()]);

    }


    ///////////////////////////////// EDITION ADMIN //////////////////////////////////////////
    /**
     *@Security("has_role('ROLE_ADMIN')")
     */
    public function editUserByAdminAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('troiswaBackBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('id inexistante pour cet utilisateur');
        }

        $formedituserbyadmin = $this->createEditByAdminForm( $entity);

        $formedituserbyadmin->handleRequest($request);

        if ($formedituserbyadmin->isValid())
        {
            $em->flush();
        }

        return $this->render("troiswaBackBundle:User:editUserByAdmin.html.twig",['entity' => $entity,'formedituserbyadmin'=> $formedituserbyadmin->createView()]);
    }

    private function createEditByAdminForm(User $entity)
    {
        $form = $this->createForm(new UserEditAdminType, $entity, array(
            'action' => $this->generateUrl('user_edit_by_admin', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    ///////////////////////////////// EDITION USER //////////////////////////////////////////////


    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('troiswaBackBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('troiswaBackBundle:User:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }


    private function createEditForm(User $entity)
    {
        $form = $this->createForm(new UserType(), $entity, array(
            'action' => $this->generateUrl('user_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * Creates a new User entity.
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function createAction(Request $request)
    {
        $entity = new User();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            // cryptage du mot de passe
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($entity);
            $newPassword = $encoder->encodePassword($entity->getPassword(), $entity->getSalt());
            $entity->setPassword($newPassword);

            //attribution du role
            $roles = $em->getRepository('troiswaBackBundle:Role')->findOneByName('client');
            $entity->addRole($roles);

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('user_show', array('id' => $entity->getId())));
        }

        return $this->render('troiswaBackBundle:User:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a User entity.
     *
     * @param User $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(User $entity)
    {
        $form = $this->createForm(new UserType(), $entity, array(
            'action' => $this->generateUrl('user_create'),
            'method' => 'POST',
            "attr"=>["novalidate"=>"novalidate"]
        ));

        //$form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new User entity.
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function newAction()
    {
        $entity = new User();
        $form   = $this->createCreateForm($entity);

        return $this->render('troiswaBackBundle:User:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a User entity.
     *
     */
    public function showAction($id)
    {

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('troiswaBackBundle:User')->find($id);

        // appel de la requete qui permet d'afficher tous les coupons lié à l'utilisateur
        // utiliser juste pour dump le résultat , on a utilisé twig pour affiché ces informations
        // dans la vue.
        //$testCoupon = $em->getRepository('troiswaBackBundle:UserCoupon')->findAllCouponForOneUser($id);

        if (!$entity)
        {
            throw $this->createNotFoundException('Utilisateur inexistant pour cette id');
        }

        /**
        if ($this->get('security.context')->isGranted('ROLE_ADMIN') === true)
        {
            $role = true;
        }
        else
        {
            $role = false;
        }
        */

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('troiswaBackBundle:User:show.html.twig',[
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            //'role' => $role,
        ]);
    }


    /**
     * Edits an existing User entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('troiswaBackBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('user_edit', array('id' => $id)));
        }

        return $this->render('troiswaBackBundle:User:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a User entity.
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('troiswaBackBundle:User')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('user'));
    }

    /**
     * Creates a form to delete a User entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
