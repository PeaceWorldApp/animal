<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{

    /**
     * @Route("/home/project", name="project")
     */
    public function index(): Response
    {
        $p = new Project();
        $form = $this->createForm(ProjectType::class,$p);
        return $this->render('project/home.html.twig', [
            'form' => $form->createView(),
        ]);
        // return $this->render('project/index.html.twig', [
        //     'controller_name' => 'ProjectController',
        // ]);
    }

    /**
     * @Route("/project", name="project_index", methods={"GET"})
     */
    public function showAllAction(ProjectRepository $repo): Response
    {
        $products = $repo
            ->findAll();
 
        $data = [];
 
        foreach ($products as $product) {
           $data[] = [
               'id' => $product->getId(),
               'name' => $product->getName(),
               'description' => $product->getDescription(),
           ];
        }
 
 
        return $this->json($data);
    }
 
    /**
     * @Route("/project", name="project_new", methods={"POST"})
     */
    public function new(Request $request,ManagerRegistry $doc): Response
    {
        $entityManager = $doc->getManager();
        $request = $this->transformJsonBody($request);
        $project = new Project();
        $project->setName($request->get('name'));
        $project->setDescription($request->get('description'));
 
        $entityManager->persist($project);
        $entityManager->flush();
 
        return $this->json('Created new project successfully with id ' . $project->getId());
    }
 
    /**
     * @Route("/project/{id}", name="project_show", methods={"GET"})
     */
    public function show(int $id, ProjectRepository $repo): Response
    {
        $project = $repo
            ->find($id);
 
        if (!$project) {
 
            return $this->json('No project found for id ' . $id, 404);
        }
 
        $data =  [
            'id' => $project->getId(),
            'name' => $project->getName(),
            'description' => $project->getDescription(),
        ];
         
        return $this->json($data);
    }
    
    protected function transformJsonBody(Request $request)
    {
      $data = json_decode($request->getContent(), true);
  
      if ($data === null) {
        return $request;
      }
  
      $request->request->replace($data);
  
      return $request;
    }
    /**
     * @Route("/project/{id}", name="project_edit", methods={"PUT"})
     */
    public function edit(Request $request, int $id, ManagerRegistry $doc): Response
    {
        $entityManager = $doc->getManager();
        $project = $entityManager->getRepository(Project::class)->find($id);
 
        if (!$project) {
            return $this->json('No project found for id' . $id, 404);
        }
        $request = $this->transformJsonBody($request);
        $project->setName($request->get('name'));
        $project->setDescription($request->get('description'));
        $entityManager->flush();
 
        $data =  [
            'id' => $project->getId(),
            'name' => $project->getName(),
            'description' => $project->getDescription(),
        ];
         
        return $this->json($data);
    }
 
    /**
     * @Route("/project/{id}", name="project_delete", methods={"DELETE"})
     */
    public function delete(int $id, ManagerRegistry $doc): Response
    {
        $entityManager = $doc->getManager();
        $project = $entityManager->getRepository(Project::class)->find($id);
 
        if (!$project) {
            return $this->json('No project found for id' . $id, 404);
        }
 
        $entityManager->remove($project);
        $entityManager->flush();
 
        return $this->json('Deleted a project successfully with id ' . $id);
    }

    /**
     * @Route("/home/pro1", name="project1")
     */
    public function FunctionName(Request $request): Response
    {
        $p = new Project();
        $form = $this->createForm(ProjectType::class,$p);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // ... save the meetup, redirect etc.
        }

        return $this->renderForm('meetup/create.html.twig', [
            'form' => $form,
        ]);
    }
}
