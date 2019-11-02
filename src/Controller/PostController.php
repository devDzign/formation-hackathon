<?php


namespace App\Controller;


use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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


    /**
     * @Route("/post/new", name="post_new", methods={"POST", "GET"})
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager)
    {

        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) {

            $manager->persist($post);
            $manager->flush();

            return $this->redirectToRoute("post_show", ['id' => $post->getId()]);
        }

        return $this->render(
            "post/new.html.twig",
            [
                "form" => $form->createView(),
                "post" => $post,
            ]
        );
    }

    /**
     * @Route("/post/{id}", name="post_show", methods={"GET"})
     */
    public function show(Post $post)
    {
        return $this->render(
            'post/show.html.twig',
            [
                "post" => $post,
            ]
        );
    }

    /**
     * @Route("/post/{id}/edit", name="post_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Post $post, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) {
            $manager->flush();

            return $this->redirectToRoute('post_show', ['id' => $post->getId()]);
        }

        return $this->render(
            'post/edit.html.twig',
            [
                'post' => $post,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="post_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Post $post): Response
    {
        if ( $this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token')) ) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('post_list');
    }

}