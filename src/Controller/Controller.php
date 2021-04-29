<?php


namespace App\Controller;


use App\Entity\Category;
use App\Entity\Tag;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class Controller extends AbstractController
{
    protected $paginator;

    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }


    public function createEntity($id, $entityName, $em)
    {
        $entity = new $entityName();
        if ($id) {
            $entity = $em->getRepository($entityName)->find($id);
            if (!$entity) {
                throw $this->createNotFoundException('No product found for id ' . $id);
            }
        }
        return $entity;
    }
}