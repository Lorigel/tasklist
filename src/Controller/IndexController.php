<?php

namespace App\Controller;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class IndexController
{
    #[Route('/')]

    public function index(): Response
    {
        return new Response(
            '<html><body><p>Autitors job list backend test</p></body></html>'
        );
    }
}