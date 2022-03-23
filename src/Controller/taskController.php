<?php

namespace App\Controller;

use App\Entity\Tasks;
use App\Form\TaskType;
use App\Repository\TasksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class taskController extends AbstractController
{
    #[Route('/', name: 'tasks', methods: ['GET'])]
    public function tasks(TasksRepository $tasksRepository):Response
    {
        $tasks =  $tasksRepository->findBy(['accomplished' => false], ['limitdate' => 'ASC']);

        return $this->render('tasks/tasks.html.twig', [
            'tasks' => $tasks
        ]);
    }

    // Search

    #[Route('/taskByType', name: 'taskByType', methods: ['GET'])]
    public function getTaskByType(Request $request, TasksRepository $tasksRepository)
    {
        $type=$request->query->get('type');
        $tasks = $tasksRepository->FindBy(['type'=>$type], ['limitdate'=>'ASC']);
        return $this->render('tasks/tasks.html.twig', ['tasks'=>$tasks]);
    }


    #[Route('/addTask', name: 'addTask', methods: ['GET', 'POST'])]
    public function addTask(Request $request, TasksRepository $tasksRepository):Response
    {
        $task = new Tasks();
        $dateNow = new \DateTime('now');

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $file = $form->get('illustration')->getData();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('pathUploadDirectory'), $fileName);
            $task->setIllustration($fileName)
                ->setAccomplished(false)
                ->setCreationdate($dateNow);
            $tasksRepository->add($task);
            return $this->redirectToRoute('tasks');
        }
        return $this->render('tasks/addTask.html.twig', [
            'taskForm' => $form->createView()
        ]);
    }
}