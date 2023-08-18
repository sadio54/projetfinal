<?php

namespace App\Controller;

use App\classe\Mail;
use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\ResetPasswordType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\UserPasswordEncodeInterface;

class ResetPasswordController extends AbstractController
{
    private $entityManager;


    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager ;
    }


    #[Route('/mot-de-passe-oublie', name: 'reset_password')]
    public function index(Request $request): Response
    {

        if ($this->getUser())  {
            return $this->redirectToRoute('home');
        }

        if ($request->get('email')){
            $user = $this->entityManager->getRepository(User::class)->findOneByEmail($request->get('email'));

            if ($user) {
                $reset_password = new ResetPassword();
                $reset_password->setUser($user);
                $reset_password->setToken(uniqid());
                $reset_password->setCreatedAt(new DateTime());
                $this->entityManager->persist($reset_password);
                $this->entityManager->flush();


                $url = $this->generateUrl('update_password',[
                    'token' =>  $reset_password->getToken()
                ]);

                $content = "Bonjour " . $user->getFirstName() . "<br> Vous avez demandé à réinitialiser votre mot de passe sur le site Nation Baller. <br><br>";
                $content .= "Veuillez cliquer sur le lien suivant afin de <a href='" . $url . "'>mettre à jour votre mot de passe</a>.";


                $mail = new Mail;
                $mail ->send($user->getEmail(), $user->getfirstname().' '. $user->getlastname(), 'Reinitialiser votre mot de passe sur Nation Baller', $content);

                $this->addFlash('notice', 'Vous aller bientôt recevoire un mail pour la reinitialisation de votre mot de passe. ');

            } else {

                $this->addFlash('notice', 'Cette email est inconnue.');
            }
        }

        return $this->render('reset_password/index.html.twig');
    }

    #[Route('/modifier-mot-de-passe/{token}', name: 'update_password')]
    public function update(Request $request ,$token , UserPasswordEncodeInterface $encoder): Response
    {
        $reset_password = $this->entityManager->getRepository(ResetPassword::class)->findOneByToken($token);

        if (!$reset_password) {
            return $this->redirectToRoute('reset_password');
        }


        $now = new \Datetime();
        if ($now > $reset_password->getCreatedAt()->modify('+ 3 hour ')){
            $this->addFlash('notice', 'Votre demande de mot de passe a expiré. mercide la renouveler');
            return $this->redirectToRoute('reset_password');
        } 

        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $new_pwd = $form->get('new_password')->getData();

            $password = $encoder->encoderPassword($reset_password->getUser(), $new_pwd);
            $reset_password->getUser()->setPassword($password);

            $this->entityManager->flush();

            $this->addFlash( 'notice' , 'votre mot de passe a bien été mis a jour');
            return $this->redirectToRoute('app_login');

        }
        return $this->render('reset_password/update.html.twig', [
            'form' => $form->createView() 
        ]);

        
    }
}
