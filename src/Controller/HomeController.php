<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    /**
     * @Route("/", name="hello")
     * @return Response
     */
    public function sayHello()
    {

        $personnes = [
            "mourad",
            "clement",
            "Sara",
            "Jade",
        ];

        return $this->render(
            'hello.html.twig',
            [

                "personnes" => $personnes,
            ]
        );
    }
}