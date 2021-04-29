<?php


namespace App\Controller;


use App\Entity\Game;
use App\Form\GameFormType;
use App\Service\Image;
use App\Service\ImageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class GameController extends Controller
{
    /**
     * @Route("/game/edit/{gameId}", name="game_edit", requirements={"gameId"="\d+"})
     * @Route("/game/new", name="game_new", defaults={"gameId":null})
     */
    public function edit(
        $gameId,
        Request $request,
        EntityManagerInterface $em,
        ImageService $imageService): Response
    {
        $game = $this->createEntity($gameId, Game::class, $em);
        $form = $this->createForm(GameFormType::class, $game);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            dump($form->getData());

            /** @var UploadedFile $gameImgData */
            $gameImgData = $form->get('img')->getData();
            if ($gameImgData) {
                $fileName = $imageService->upload(
                    $gameImgData,
                    Image::fromArray($this->getParameter('image.game.original'))
                );
                $game->setImg($fileName);
            }
            $em->persist($game);
            $em->flush();
            $this->addFlash('success', 'Your changes were saved!');
            return $this->redirectToRoute('game_edit', ['gameId' => $game->getId()]);
        }
        return $this->render('game/game.html.twig', [
            'controller_name' => 'GameEditController',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/game/delete/{id}", name="game_delete", requirements={"id"="\d+"})
     */
    public function delete(Game $game, EntityManagerInterface $em)
    {
        $em->remove($game);
        $em->flush();
        return $this->redirectToRoute('mainpage', [], 301);
    }
}