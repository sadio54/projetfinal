<?php

namespace App\Controller;

use App\classe\Cart;
use App\classe\Mail;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderValidateController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/commande/merci/{StripeSessionId}', name: 'order_validate')]
    public function index(Cart $cart , $StripeSessionId): Response
    {


        $order = $this->entityManager->getRepository(Order::class)->findOneBy(['StripeSessionId' => $StripeSessionId]);


        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }
          $cart->remove();

        if (!$order->getstate() == 0) {

          
            $order->setstate(1); 
            $this->entityManager->flush();

            $mail = new Mail();
            $content = "Bonjours ".$order->getUser()->getfirstname(). "<br> Merci pour votre commande. <br><br> Lorem ipsum dolor sit amet consectetur adipisicing elit. Doloremque harum veniam non! Nam quisquam odit, voluptatum, ducimus aliquid dolore delectus distinctio a quam non illo labore eius quia facere vero.
            Aperiam debitis aliquam dicta fugit animi, quia voluptatum eos modi illo exercitationem cumque atque vel asperiores in? Soluta recusandae, odit consequuntur, atque hic nt quia totam iste quo fugiat! Blanditiis!
            Exercitationem a dolorem corporis tenetur cum provident vitae dolores tempore. Maiores distinctio dolore perferendis cupiditate nulla magni earum nostrum eligendi  quod natus qui quas maiores? ";
            $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstname(), 'Votre commande Nation Baller est bien validée', $content);

        }

        return $this->render('order_validate/index.html.twig', [
            'order' => $order
        ]);
    }
}
