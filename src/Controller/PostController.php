<?php


namespace App\Controller;


use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/post", name="post_list", methods={"GET"})
     */

    public function list(PostRepository $postRepository)
    {

        return $this->render(
            'post/list.html.twig',
            [
                'posts' => $postRepository->findAll(),
            ]
        );
    }

    public function show()
    {
    }

    public function create()
    {
    }

    public function update()
    {
    }

    public function delete()
    {
    }

}