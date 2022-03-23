<?php

namespace App\Controller;


use App\Repository\PictureRepository;
use App\Repository\TypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class pictureController extends AbstractController
{
    #[Route("/pictures", name : 'pictures', methods : ['GET'] )]
    public function pictures(PictureRepository $pictureRepository, TypeRepository $typeRepository):Response
    {
        $pictures = $pictureRepository->FindBy([], ['name'=> 'ASC']);
        $types = $typeRepository->findAll();

        return $this->render('tasks/pictures.html.twig', [
            'pictures'=>$pictures,
            'types'=>$types
        ]);
    }

    #[Route('/getPictures', name: 'getPictures', methods: ['GET'])]
    public function getPicturesByNameAndType(
        Request $request,
        PictureRepository $pictureRepository,
        TypeRepository $typeRepository
        ): Response
    {
        $name = $request->query->get('name');
        $description = $request->query->get('description');
        $type = $request->query->get('type');
        // $pictures = $pictureRepository->findBy(['name'=>$name, 'description'=>$description], ['name'=>'ASC']);
        $pictures = $pictureRepository->getPicture($name, $description, $type);
        $types = $typeRepository->findAll();

        return $this->render('tasks/pictures.html.twig', [
            'pictures'=>$pictures,
            'types'=>$types
        ]);
    }
}