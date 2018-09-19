<?php

namespace App\Controller;


use App\Service\Greeting;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @var Greeting
     */
    private $greeting;
    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(Greeting $greeting, SessionInterface $session)
    {
        $this->greeting = $greeting;
        $this->session = $session;
    }

    /**
     * @Route("/", name="blog_index")
     */
    public function index(Request $request)
    {

        return $this->render('blog/index.html.twig', array(
            'posts' => $this->session->get('posts')
        ));
    }

    /**
     * @Route("/add", name="blog_add")
     */
    public function add()
    {
        $posts = $this->session->get('posts');
        $posts[uniqid()] = [
            'title' => 'A random title' . rand(1,600),
            'content' => 'A random content lorem ipsum ' . rand(1,600)
        ];

        $this->session->set('posts', $posts);
    }

    /**
     * @Route("/show/{$id}", name="blog_show")
     */
    public function show($id)
    {
        $posts = $this->session->get('posts');

        if (!$posts || !isset($posts[$id])) {
            throw new NotFoundHttpException('Post not found');
        }

        $this->render('blog/show.html.twig', array(
            'id' => $id,
            'post' => $posts[$id]
        ));
    }
}