<?php

namespace App\Controller;

use App\Entity\CatNote;
use App\Entity\Note;
use App\Form\CatNoteType;
use App\Form\NoteType;
use App\Repository\CatAniRepository;
use App\Repository\CatNoteRepository;
use App\Repository\NoteRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

date_default_timezone_set('Asia/Ho_Chi_Minh');
class NoteController extends AbstractController
{
    // // Config serializer
    // private $serializer;

    // public function __construct(SerializerInterface $serializer)
    // {
    //     $this->serializer = $serializer;
    // }

    /**
     * @Route("/index/note", name="notePage")
     */
    public function indexNoteAction(): Response
    {
        $catNote = new CatNote();
        $noteForm = $this->createForm(CatNoteType::class,$catNote);
        return $this->render('note/home.html.twig', [
            'note_form' => $noteForm->createView()
        ]);
    }
    /**
     * @Route("/note", name="app_note")
     */
    public function index(ManagerRegistry $doctrine,Request $req): Response
    {
        $note = new Note();
        $noteForm = $this->createForm(NoteType::class,$note);

        $noteForm->handleRequest($req);
        $entityManager = $doctrine->getManager();

        if($noteForm->isSubmitted() && $noteForm->isValid()){
             $data = $noteForm->getData();
             $note->setContent($data->getContent());
             $note->setCreated($data->getCreated());
             $note->setAuthor($data->getAuthor());
             $note->setRepeatTime($data->getRepeatTime());
             $note->setCat($data->getCat());

             $entityManager->persist($note);

             // actually executes the queries (i.e. the INSERT query)
             $entityManager->flush();

            //  $createdDate = $data->getCreated()->format('Y-m-d');
             
             return $this->json([
                 'id'=> $note->getId()
             ]);
        }

        return $this->render('note/index.html.twig', [
            'note_form' => $noteForm->createView()
        ]);
        // return $this->redirectToRoute("addNote");
    }


    /**
     * @Route("/addNote", name="addNote")
     */
    public function addAction(ManagerRegistry $doctrine,ValidatorInterface $validator): Response
    {
        $entityManager = $doctrine->getManager();

        $note = new Note();
        // $note->setContent("Go to bed");
        // $note->setCreated(new \DateTime());
        // $note->setAuthor("Khanh");

        $note->setContent("h");
        $note->setCreated(new \DateTime());
        $note->setAuthor("Khanh@gmail.com");
        $note->setRepeatTime(3);
        $errors = $validator->validate($note);

        if(count($errors)>0){
             /*
         * Uses a __toString method on the $errors variable which is a
         * ConstraintViolationList object. This gives us a nice string
         * for debugging.
         */
                $errorsString = (string) $errors;

                return new Response((string) $errorsString, 400);
        }

            // tell Doctrine you want to (eventually) save the Note (no queries yet)
            $entityManager->persist($note);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            return new Response('Saved new note with id '.$note->getId());
    }

    /**
     * @Route("/show_note", name="note_show")
     */
    public function showAction(NoteRepository $productRepositor, Request $req): Response
    {
        $id = $req->query->get('id');
        //1
        $product = $productRepositor
        ->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No note found for id '.$id
            );
        }

        return new Response('Check out this great note: '.$product->getContent());

        // or render a template
        // in the template, print things with {{ product.name }}
        // return $this->render('note/index.html.twig', ['post' => $product]);
        // return $this->json([
        //     'product'=>$product
        // ]);
    }

    //  /**
    //  * @Route("/note/{id}", name="note_show")
    //  */
    // public function showSlug(Note $product): Response
    // {
    //     // use the Product!
    //     // ...
    //     return $this->json([
    //                 'product'=>$product
    //     ]);
    // }

    /**
     * @Route("/note/find", name="note_showAll")
     */
    public function find(NoteRepository $repository): Response
    {
    //     //1
    //     // $product = $repository->findOneBy(['author' => 'Khanh']);

    //     //2
    //     // $product = $repository->findOneBy([
    //     //     'author' => 'Khanh',
    //     //     'content => 'dd',
    //     // ]);

    //     //3
    //     // $product = $repository->findBy(
    //     //     ['author' => 'Khanh']
    //     // );
    //     // if (!$product) {
    //     //     throw $this->createNotFoundException(
    //     //         'No note found'
    //     //     );
    //     // }

    //     //4
    //     // $product = $repository->findAll();

    //     //5
    $product = $repository->findAllGreaterThan(2);

        // return new JsonResponse($product);
    return $this->json($product);

    //     // return new Response('Check out this great note: '.$product->getId());

    //     // or render a template
    //     // in the template, print things with {{ product.name }}
    //     // return $this->render('product/show.html.twig', ['product' => $product]);
    }
    
    /**
     * @Route("/update/{id}", name="update", requirements={"id":"\d+"})
     */
    public function updateAction(NoteRepository $productRepositor, int $id, 
    ManagerRegistry $doctrine, SerializerInterface $serializer): Response
    {
        $entityManager = $doctrine->getManager();
         //1
         $product = $productRepositor
         ->find($id);

        //  $cat = $product->getCat();
 
         if (!$product) {
             throw $this->createNotFoundException(
                 'No note found for id '.$id
             );
         }
        // //  $repository = $doctrine->getRepository(Product::class);
         $product->setContent('New product name!');
         $entityManager->persist($product);
         $entityManager->flush();

        //  $jsonContent = $serializer->serialize($product, 'json');


         return $this->json($product,Response::HTTP_OK,[],[
             ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function($object){
                 return $object->getId();
             }
         ]);
        //  return $this->redirectToRoute('note_show', [
        //      'id' => $product->getId()
        //  ]);
    }

    /**
     * @Route("/delete/{id}", name="delete_note")
     */
    public function delAction(ManagerRegistry $doctrine,int $id,
    NoteRepository $productRepositor): Response
    {
        $entityManager = $doctrine->getManager();
         //1
         $product = $productRepositor
         ->find($id);
        $entityManager->remove($product);
        $entityManager->flush();
        return new Response("Deleted");
    }

    /**
     * @Route("/note_cat", name="add_note_cat")
     */
    public function addProCat(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        // $ar = ['Learn','Entertainment','Pick up'];
        
        // foreach ($ar as $value) {
        //     $category = new CatNote();
        //     $category->setName($value);
        //     $entityManager->persist($category);
        // // $entityManager->persist($product);
        //     $entityManager->flush();
        // }
       

        $product = new Note();
        $product->setContent('Prof. Meeting after dinner');
        $product->setCreated(new \DateTime());
        $product->setAuthor("Khanh@gmail.com");
        $product->setRepeatTime(1);


        $cat = $doctrine->getRepository(CatNote::class)->find(2);
        // relates this product to the category
        $product->setCat($cat);

        
        // $entityManager->persist($category);
        $entityManager->persist($product);
        $entityManager->flush();

        return new Response(
            // 'Saved new product with id: '.$product->getId()
            ' and new category with id: '
        );
    }
    /**
     * @Route("/show/{id}", name="app_show_1")
     */
    public function show(ManagerRegistry $doctrine, int $id): Response
    {
        $product = $doctrine->getRepository(Note::class)->find($id);
        // ...

        $categoryName = $product->getCat()->getName();

        return $this->json(['catName'=>$categoryName]);
    }


    /**
     * @Route("/api/cats", name="apiShowCat",methods={"GET"})
     */
    public function apiShowCatAction(CatNoteRepository $res): Response
    {
        // Get all articles in Database
        $cats = $res->findAllArray();

        return $this->json($cats,Response::HTTP_OK,[],[]);
        // return $this->json($cats,Response::HTTP_OK,[],[
        //     ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function($object){
        //         return $object->getId();
        //     }
        // ]);
        // return new JsonResponse($cats,Response::HTTP_OK,[]);

    }

    /**
     * @Route("/api/cats", name="apiNewCat", methods={"POST"})
     */
    public function apiNewCatAction(Request $req, ManagerRegistry $res): Response
    {
        $req = $this->transformJsonBody($req);
        $name = $req->get('name');
        $entity = $res->getManager();
        $cat = new CatNote();
        $cat->setName($name);
        $entity->persist($cat);
        $entity->flush();


        return $this->json(['id'=>$cat->getId(),'name'=>$cat->getName()],Response::HTTP_OK,[],[]);
        // return new JsonResponse(['id'=>$cat->getId(),'name'=>$cat->getName()],Response::HTTP_OK,[]);
        // return $this->render('$0.html.twig', []);
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
 * @Route("/note/edit/{id}", name="todo_edit")
 */
public function editAction($id, Request $req, NoteRepository $repo, ManagerRegistry $doctrine)
{
    
    $note = $repo->find($id);
    
    $noteForm = $this->createForm(NoteType::class, $note);
    
    $noteForm->handleRequest($req);
    $entityManager = $doctrine->getManager();

    if($noteForm->isSubmitted() && $noteForm->isValid()){
         $data = $noteForm->getData();
         $note->setContent($data->getContent());
         $note->setCreated($data->getCreated());
         $note->setAuthor($data->getAuthor());
         $note->setRepeatTime($data->getRepeatTime());
         $note->setCat($data->getCat());

         $entityManager->persist($note);

         // actually executes the queries (i.e. the INSERT query)
         $entityManager->flush();

        //  $createdDate = $data->getCreated()->format('Y-m-d');
         
         return $this->json([
             'id'=> $note->getId()
         ]);
    }
    
    return $this->render('note/edit.html.twig', [
        'note_form' => $noteForm->createView()
    ]);
}


}
