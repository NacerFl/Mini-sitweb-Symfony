<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecuController extends AbstractController
{
    /**
     * @Route("/inscription", name="inscription_registration")
     */
    public function registration(Request $request, ObjectManager $manager,UserPasswordEncoderInterface $encoder) //encrypt le mot de passe
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $hash = $encoder->encodePassword($user, $user->getPassword());

            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('login');
            $this->addFlash('success', 'Compte crée!');


        }

        return $this->render('secu/registration.html.twig', [

            'form' => $form->createView()
        ]);

    }
    /**
     * @Route("/connexion", name="login")
     */

    public function login(Request $request){

        return $this->render('secu/login.html.twig');

    }
    /**
     * @Route("/deconnexion", name="logout")
     */
    public function logout(){}


    public function crerpar($updated_at = NULL, Request $request, ObjectManager $manager){ //incomplet
        $user= $this->container->getEmail()->getToken()->getUser();
        $updated_at = $user->getId();

    }
}
