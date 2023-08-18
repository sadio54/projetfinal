<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\classe\Mail;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class RegisterController extends AbstractController
{
    private $entityManager;
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    #[Route('/inscription', name: 'register')]
    public function index(Request $request, UserPasswordHasherInterface $encoder)
    {
        $notification = null;
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $search_email = $this->entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());
            if (!$search_email)
            {
                $password = $this->passwordEncoder->hashPassword($user, $user->getPassword());
                $user->setPassword($password);
    
                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $mail = new Mail();
                $content = "Bonjours ".$user->getfirstname(). "<br> Bienvenue dans Nation Baller, le Top 1 des boutiquues dedier au basketball. <br><br> Lorem ipsum dolor sit amet consectetur adipisicing elit. Doloremque harum veniam non! Nam quisquam odit, voluptatum, ducimus aliquid dolore delectus distinctio a quam non illo labore eius quia facere vero.
                Aperiam debitis aliquam dicta fugit animi, quia voluptatum eos modi illo exercitationem cumque atque vel asperiores in? Soluta recusandae, odit consequuntur, atque hic nt quia totam iste quo fugiat! Blanditiis!
                Exercitationem a dolorem corporis tenetur cum provident vitae dolores tempore. Maiores distinctio dolore perferendis cupiditate nulla magni earum nostrum eligendi quisquam? Minima, unde dicta iste quod natus qui quas maiores? ";
                $mail->send($user->getEmail(), $user->getFirstname(), 'Bienvenue dans Nation Baller, tu es chaud pour une partie de 1v1 ?', $content);


                $notification = "Votre inscription s'est correctement passée. Vous pouvez maintenant vous connecter a votre compte ";
            }else{
                $notification = "Votre email existe déjà dans notre base de donné ";
            }
          

            

            
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }
}

?>