<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register1", name="app_register")
     */
    public function registerAction(Request $req, UserPasswordHasherInterface $passEncode,
    ManagerRegistry $res)
    {
        $form = $this->createFormBuilder()
        ->add('email')
        ->add('password',RepeatedType::class,[
            'type'=> PasswordType::class,
            'required' => true,
            'first_options' => ['label' => 'Password'],
            'second_options' => ['label' => 'Confirm Password']
        ])
        ->add('save',SubmitType::class,[
            'label' => "Register!"
        ])
        ->getForm();

        $form->handleRequest($req);
        if($form->isSubmitted()){
            $data = $form->getData();
            $user = new User();
            $user->setEmail($data['email']);
            $user->setPassword(
                $passEncode->hashPassword($user,$data['password'])
            );

            $em = $res->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('app_login'));
        }

        return $this->render("register/index.html.twig",[
            'form' => $form->createView()
        ]);
        // return $this->render('register/index.html.twig', [
        //     'controller_name' => 'RegisterController',
        // ]);
    }
}
