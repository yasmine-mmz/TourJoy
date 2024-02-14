<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CategoriesType;
use App\Form\CategoriesAddType;
use App\Form\CategoriesUpdateType;
use App\Entity\Categories;
use App\Repository\CategoriesRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;
class CategoriesController extends AbstractController
{
    #[Route('/categories', name: 'app_categories')]
    public function index(): Response
    {
        return $this->render('categories/index.html.twig', [
            'controller_name' => 'CategoriesController',
        ]);
    }
    #[Route('/showcategories', name: 'Categories_show')]
    public function show(CategoriesRepository $rep): Response
    {
        $Categoriess = $rep->findAll();
        return $this->render('Categories/index.html.twig', ['Categoriess'=>$Categoriess]);
    }
    #[Route('/Categoriesadd', name: 'Categories_add')]
    public function AddCategories(ManagerRegistry $doctrine, Request $request,ValidatorInterface $validator): Response
    {
        $Categories =new Categories();
        $form=$this->createForm(CategoriesAddType::class,$Categories);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $errors = $validator->validate($Categories); 
            if (count($errors) > 0) {
                $errorsString = (string) $errors;
        
                return new Response($errorsString);
            }
            $em= $doctrine->getManager();
            $em->persist($Categories);
            $em->flush();
            return $this-> redirectToRoute('Categories_show');
        }
        return $this->render('Categories/Add.html.twig',[
            'Categories'=>$form->createView(),
        ]);
    }
    #[Route('/Categoriesupdate{id}', name: 'Categories_update')]
    public function UpdateCategories(ManagerRegistry $doctrine, Request $request, CategoriesRepository $rep, $id): Response
    {
       $Categories = $rep->find($id);
       $form=$this->createForm(CategoriesUpdateType::class,$Categories);
       $form->handleRequest($request);
       if($form->isSubmitted()){
           $em= $doctrine->getManager();
           $em->persist($Categories);
           $em->flush();
           return $this-> redirectToRoute('Categories_show');
       }
       return $this->render('Categories/Update.html.twig',[
           'Categories'=>$form->createView(),
       ]);
    }
    #[Route('/Categoriesdelete{id}', name: 'Categories_delete')]
    public function deleteCategories($id, CategoriesRepository $rep, ManagerRegistry $doctrine): Response
    {
        $em= $doctrine->getManager();
        $Categories= $rep->find($id);
        $em->remove($Categories);
        $em->flush();
        return $this-> redirectToRoute('Categories_show');
    }
}
