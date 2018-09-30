<?php

namespace App\Controller;


use App\Entity\MicroPost;
use App\Repository\MicroPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MicroPostController
 * @package App\Controller
 * @Route("/micro-post")
 */
class MicroPostController extends AbstractController
{

    /**
     * @var MicroPostRepository
     */
    private $microPostRepository;
    /**
     * @var \Twig_Environment
     */
    private $twig;
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(
        MicroPostRepository $microPostRepository, \Twig_Environment $twig,
        FormFactoryInterface $formFactory, EntityManagerInterface $manager
    ) {
        $this->microPostRepository = $microPostRepository;
        $this->twig = $twig;
        $this->formFactory = $formFactory;
        $this->manager = $manager;
    }

    /**
     * @Route("/", name="micro_post_index")
     */
    public function index()
    {
        $posts = $this->microPostRepository->findBy([], ['time' => 'DESC']);

        return $this->render('micro-post/index.html.twig', ['posts' => $posts]);
    }


    /**
     * @Route("/add", name="micro_post_add")
     */
    public function add(Request $request)
    {
        $microPost = new MicroPost();
        $microPost->setTime(new \DateTime());

        // Other method of create form by using form factory interface
        // $form = $this->formFactory->create('App\Form\MicroPostType', $microPost);
        $form = $this->createForm('App\Form\MicroPostType', $microPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($microPost);
            $em->flush();

            return $this->redirectToRoute('micro_post_index');
        }
        return new Response(
            $this->twig->render('micro-post/add.html.twig', [
                'form' => $form->createView()
            ])
        );
    }

    /**
     * @Route("/edit/{id}", name="micro_post_edit")
     * @param MicroPost $post
     * @param Request $request
     * @return Response
     */
    public function edit(MicroPost $post, Request $request)
    {
        $form = $this->createForm('App\Form\MicroPostType', $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('micro_post_index');
        }

        return $this->render('micro-post/edit.html.twig', [
           'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="micro_post_post")
     */
    public function post(MicroPost $post)
    {

        return $this->render('micro-post/post.html.twig', ['post' => $post]);
    }


}