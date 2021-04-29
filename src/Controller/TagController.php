<?php


namespace App\Controller;


use App\Entity\Game;
use App\Entity\Tag;
use App\Form\TagFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends Controller
{

    /**
     * @Route("/tags/", name="tags_list")
     */
    public function tagsList()
    {
        $tags = $this->getDoctrine()->getRepository(Tag::class)->findAll();
        return $this->render('tag/index.html.twig', [
            'tags' => $tags
        ]);
    }


    /**
     * @Route ("/tag/{tagId}", name="games_in_tag",requirements={"tagId"="\d+"})
     */
    public function gamesInCategory(Tag $tagId, Request $request)
    {
        $page = $request->query->get('p');
        $games = $this->paginator->paginate($tagId->getGames(), $page ? $page : 1);
        return $this->render('category/category_games.html.twig', [
            'tag' => $tagId,
            'games' => $games,
        ]);
    }


    /**
     * @Route("/tag/edit/{tagId}", name="tag_edit", requirements={"tagId"="\d+"})
     * @Route("/tag/new", name="tag_new", defaults={"tagId":null})
     */
    public function edit($tagId, Request $request, EntityManagerInterface $em): Response
    {
        $tag = $this->createEntity($tagId, Tag::class, $em);

        $form = $this->createForm(TagFormType::class, $tag);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($tag);
            $em->flush();
            return $this->redirectToRoute('tag_edit', ['tagId' => $tag->getId()]);
        }
        return $this->render('tag/tag.html.twig', [
            'controller_name' => 'TagEditController',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/tag/delete/{id}", name="tag_delete", requirements={"id"="\d+"})
     */
    public function delete(Tag $tag, EntityManagerInterface $em)
    {
        $em->remove($tag);
        $em->flush();
        return $this->redirectToRoute('mainpage', [], 301);
    }
}