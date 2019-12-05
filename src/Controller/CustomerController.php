<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
{
    /**
     * @Route("/customer", name="customer")
     */
    public function index()
    {
        return $this->render('customer/index.html.twig', [
            'controller_name' => 'CustomerController',
        ]);
    }

    /**
     * @Route("/customer/detail/{id}", name="detail")
     */
    public function detail($id)
    {
        if (!ctype_digit($id)) {
            return $this->render('customer/chyba.html.twig');
        }

        return $this->render('customer/detail.html.twig', [
            'id' => $id,
        ]);
    }
}
