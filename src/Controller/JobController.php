<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Books ;
use App\Entity\Image ;
use App\Form\BooksType ;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType ;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
class JobController extends AbstractController
{
    /**
     * @Route("/job", name="job")
     */
    public function index(): Response
    {
        return $this->render('job/index.html.twig', [
            'controller_name' => 'JobController',
        ]);
    }

    /**
     * @Route("/home", name="home")
     */
    public function home()
    {
        return $this->render('job/home.html.twig');
    }
  

    /**
     * @Route("/admin", name="admin")
     */
    public function admin():Response
    {
        $repo=$this->getDoctrine()->getManager()->getRepository(Books::class);
        $books= $repo->findAll();
      return $this->render('job/admin.html.twig', [
        'controller_name' => 'JobController',
         'books'=>$books
]);

    }


     /**
     * @Route("/books", name="books")
     */
    public function books()
    { 
       $repo=$this->getDoctrine()->getRepository(Books::class);
       $Books= $repo->findAll();
       return $this->render('job/books.html.twig', [
           'controller_name' => 'JobController',
           'books'=>$Books
       ]);
    
       }

    /**
     * @Route("/add", name="add")
     */
    
    public function add(Request $request)
    { 

        $book= new Books();
        $form =$this->get('form.factory')
                    ->create(BooksType::class, $book)
                    ->add('save',  SubmitType::class );

        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $em=$this->getDoctrine()->getManager() ;
                $em->persist($book);
                $em->flush();
                $request->getSession()->getFlashBag();
                return $this->redirectToRoute('admin');
                  }     
                
          }

        return $this->render('job/add.html.twig', array('form'=>$form->createView()));

    }
     /**
     * @Route("/edit/{id}", name="edit")
     */
    public function edit($id,Request $request)
    {
        $book= $this->getDoctrine()
                   ->getManager()
                   ->getRepository(Books::class)
                   ->find($id);
        $form = $this->get('form.factory')
                      ->create(BooksType::class,$book)
                      ->add('save',  SubmitType::class );

        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $em=$this->getDoctrine()->getManager() ;
                $em->persist($book);
                $em->flush();
                $request->getSession()->getFlashBag(); 
                return $this->redirectToRoute('admin',array('id'=> $book->getId())) ;
                  }     
          }

        return $this->render('job/edit.html.twig', array('form'=>$form->createView()));
    }
    /**
     * @Route("/delete/{id}", name="delete")
     */
     public function delete(books $book)
    { 
       
        $em=$this->getDoctrine()->getManager();
        $em->remove($book);
        $em->flush();

        return $this->redirectToRoute ('admin',['id'=> $book->getId()]);

    }
     
  

}