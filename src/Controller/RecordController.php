<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Repository\ArtistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RecordController extends AbstractController
{
    /**
     * listes des artistes
     * @Route("/artist", name="artist_list")
     */
    public function index(ArtistRepository $repository)
    {
        return $this->render('record/artist_list.html.twig', [
            'artist_list' => $repository->findAll(),
        ]);
    }

    /**
     * Page d'un artiste
     * @Route("/artist/{id}", name="artist_page")
     */

     public function artistPage(Artist $artist)
     {
        return $this->render('record/artist_page.html.twig', [
            'artist' => $artist
        ]);

     }
}

