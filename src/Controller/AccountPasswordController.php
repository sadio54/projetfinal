<?php

namespace App\Controller;
use App\Entity\User;
use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AccountPasswordController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }
    

    #[Route('/compte/modifier-mon-mot-de-passe', name: 'account_password')]
    public function index(Request $request, UserPasswordHasherInterface $encoder): Response
    {
        $notification = null;
        /** @var User $user */

        $user=$this->getUser();
        $form=$this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $sold_pwd = $form->get('old_password')->getData();
            
            if($encoder->isPasswordValid($user, $sold_pwd)){
            $new_pwd = $form->get('new_password')->getData();

                $password = $encoder->hashPassword($user, $new_pwd);

                $user->setPassword($password);
                $this->entityManager->flush($user);
                $notification = "Votre mot de passe a bien été mis à jour";
            }else {
                $notification = "Votre mot de passe actuel n'est pas le bon";
            }
        }
        return $this->render('account/password.html.twig', [
            'form'=>$form->createView(),
            'notification'=>$notification
        ]);
    }
}

