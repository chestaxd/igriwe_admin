<?php


namespace App\Controller;


use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Service\Image;
use App\Service\ImageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CategoryController extends Controller
{

    /**
     * @Route("/categories/", name="categories_list")
     */
    public function categoriesList()
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        dump($categories);
        return $this->render('category/index.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route ("/category/{categoryId}", name="games_in_category",requirements={"categoryId"="\d+"})
     */
    public function gamesInCategory(Category $categoryId, Request $request)
    {
        $page = $request->query->get('p');
        $games = $this->paginator->paginate($categoryId->getGames(), $page ? $page : 1);
        return $this->render('category/category_games.html.twig', [
            'category' => $categoryId,
            'games' => $games,
        ]);
    }

    /**
     * @Route("/category/edit/{categoryId}", name="category_edit", requirements={"categoryId"="\d+"})
     * @Route("/category/new", name="category_new", defaults={"categoryId":null})
     */
    public function edit(
        $categoryId,
        Request $request,
        EntityManagerInterface $em,
        ImageService $imageService
    ): Response
    {
        $category = $this->createEntity($categoryId, Category::class, $em);
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $categoryImgData = $form->get('img')->getData();
            if ($categoryImgData) {
                $fileName = $imageService->upload(
                    $categoryImgData,
                    Image::fromArray($this->getParameter('image.category.original'))
                );
                $imageService->resize($fileName, Image::fromArray($this->getParameter('image.category.small')));
                $category->setImg($fileName);
            }
            $em->persist($category);
            $em->flush();
            $this->addFlash('success', 'Your changes were saved!');
            return $this->redirectToRoute('category_edit', ['categoryId' => $category->getId()]);
        }
        return $this->render('category/category.html.twig', [
            'form' => $form->createView(),
            'category' => $category,
        ]);

    }

    /**
     * @Route("/category/delete/{id}", name="category_delete", requirements={"id"="\d+"})
     */
    public function delete(Category $category, EntityManagerInterface $em)
    {
        $em->remove($category);
        $em->flush();
        return $this->redirectToRoute('mainpage', [], 301);
    }

}