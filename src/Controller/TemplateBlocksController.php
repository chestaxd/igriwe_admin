<?php


namespace App\Controller;


use App\Entity\Category;
use App\Entity\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TemplateBlocksController extends AbstractController
{
    public function getNavBarCategoriesList()
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        return $this->render('blocks/_dropdown_categories.html.twig', [
            'categories' => $categories
        ]);
    }


    public function getNavBarTagsList()
    {
        $tags = $this->getDoctrine()->getRepository(Tag::class)->findAll();
        return $this->render('blocks/_dropdown_tags.html.twig', [
            'tags' => $tags
        ]);
    }

}