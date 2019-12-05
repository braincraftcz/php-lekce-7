<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PythagorasController extends AbstractController
{
    /**
     * @Route("/pythagoras", name="pythagoras")
     */
    public function index()
    {
        $a = 5;
        $b = 4;

        $strana = 6;
        $uhel = 60;
        $obsahTrojuhelnika = round($strana * $strana * sin(deg2rad($uhel)) / 2);

        return $this->render('pythagoras/index.html.twig', [
            'obdelnik' => [
                'a' => $a,
                'b' => $b,
                'obvod' => 2 * ($a + $b),
                'obsah' => $a * $b
            ],
            'trojuhelnik' => [
                'strana' => $strana,
                'uhel' => $uhel,
                'obvod' => 3 * $strana,
                'vyska' => $strana * sin(deg2rad($uhel)),
                'obsah' => $obsahTrojuhelnika
            ]

        ]);
    }
}
