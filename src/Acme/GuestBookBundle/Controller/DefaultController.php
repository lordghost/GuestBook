<?php

namespace Acme\GuestBookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('GuestBookBundle:Guest');

        $guest = $repo->findOneBy(array(
            'name' => 'Oleg'
        ));

        return $this->render('GuestBookBundle:Default:index.html.twig', array(
            'name' => $name,
            'guest' => $guest,
        ));
    }
}
