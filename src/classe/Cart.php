<?php

// src/Classe/Cart.php
namespace App\classe;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart
{
    private $session;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        $request = $requestStack->getCurrentRequest();

        $this->session = $request->getSession();
        $this->entityManager = $entityManager;
    }

    public function add($id)
    {
        $cart = $this->session->get('cart', []); // Récupérer le panier existant ou un tableau vide

        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $this->session->set('cart', $cart); // Sauvegarder le panier mis à jour
    }

    public function get()
    {
        return $this->session->get('cart', []);
    }

    public function remove()
    {
        return $this->session->remove('cart');
    }

    public function delete($id)
    {
        $cart = $this->session->get('cart', []);

        unset($cart[$id]);

        return $this->session->set('cart', $cart);
    }

    public function decrease($id)
    {
        $cart = $this->session->get('cart', []);

        if ($cart[$id] > 1) {
            // Retirer une quantité (-1)
            $cart[$id]--;
        } else {
            unset($cart[$id]);
        }

        return $this->session->set('cart', $cart);
    }

    public function getFull()
    {
        $cartComplete = [];

        foreach ($this->get() as $id => $quantity) {
            $product = $this->entityManager->getRepository(Product::class)->findOneById($id);


            if (!$product){
                $this->delete($id);
                continue;
            }
            // Vérifier si le produit existe avant d'ajouter à $cartComplete
            if ($product) {
                $cartComplete[] = [
                    'product' => $product,
                    'quantity' => $quantity
                ];
            }
        }

        return $cartComplete;
    }
}
