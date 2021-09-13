<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Memo;
use App\Entity\User;

class DemoController extends AbstractController
{
    /**
     * @Route("/demo", name="demo")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository(Memo::class);
        $memo = $repo->findAll();

        return $this->render('demo/index.html.twig', [
            'controller_name' => 'DemoController',
            'memo' => $memo
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('demo/home.html.twig');
    }

    /**
     * @Route("/demo/{id}", name="memo_show")
     */
    public function show($id)
    {
        $repo = $this->getDoctrine()->getRepository(Memo::class);
        $memo = $repo->find($id);
        return $this->render('demo/show.html.twig',['memo' => $memo]);
    }

    /**
     * @Route("/new", name="memo_cree")
     */
    public function create(Request $request, ObjectManager $manager){
        $memo = new Memo();
        $repo = $this->getDoctrine()->getRepository(User::class);

        $form = $this->createFormBuilder($memo)
                     ->add('titre', TextType::class, ['attr'=>['placeholder' => "titre du memo"]])
                     ->add('contenu', TextType::class, ['attr'=>['placeholder' => "Contenu"]])
                     ->add('auteur', TextType::class, ['attr'=>['placeholder'=>'auteur']])
                     ->getForm();

        $form->handleRequest($request);



        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($memo);
            $manager->flush();

            $this->addFlash('success', 'Memo Crée!');

            return $this->redirectToRoute('memo_show',['id'=>$memo->getId()]);
        }

        return $this->render('demo/create.html.twig', ['formMemo' => $form->createView()]);
    }
    /**
     * @Route("/demo/{id}/edit", name="memo_edit")
     */
    public function form(Memo $memo,Request $request, ObjectManager $manager){
        //$memo = new Memo();

        $form = $this->createFormBuilder($memo)
                     ->add('titre', TextType::class, ['attr'=>['placeholder' => "titre du memo"]])
                     ->add('contenu', TextType::class, ['attr'=>['placeholder' => "Contenu"]])
                     ->add('auteur', TextType::class, ['attr'=>['placeholder'=>'auteur']])
                     ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($memo);
            $manager->flush();

            $this->addFlash('success', 'Memo Crée!');

            return $this->redirectToRoute('memo_show',['id'=>$memo->getId()]);
        }

        return $this->render('demo/create.html.twig', ['formMemo' => $form->createView()]);

    }
    /**
     * @Route("/demo/{id}/delete", name="memo_delete")
     */

    public function delete($id,Request $request, ObjectManager $manager){
        //$memo = new Memo();

        $repo = $this->getDoctrine()->getRepository(Memo::class);
        $memo = $repo->find($id);


        $em=$this->getDoctrine()->getManager();

        $em->remove($memo);
        $em->flush();
        $this->addFlash('success', 'Memo Suprr!');
        return $this->redirectToRoute('demo');

    }
}
