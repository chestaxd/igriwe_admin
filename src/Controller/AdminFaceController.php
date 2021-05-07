<?php

namespace App\Controller;


use App\Entity\Game;
use App\Service\RelevantGamesService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminFaceController extends Controller
{
    /**
     * @Route("/", name="mainpage")
     */
    public function index(Request $request, GetRelevantGames $relevantGames): Response
    {
        $page = $request->query->get('p');
        $games = $this->getDoctrine()->getRepository(Game::class)->findAll();
        $games = $this->paginator->paginate($games, $page ? $page : 1);
        return $this->render('index.html.twig', [
            'title' => 'Главная страница',
            'games' => $games,
        ]);
    }

    /**
     * @Route ("/search/", name="search")
     */
    public function search(Request $request): Response
    {
        $searchQuery = $request->query->get('q');
        $page = $request->query->get('p');
        if (is_numeric($searchQuery)) {
            return $this->redirectToRoute('game_edit', ['gameId' => $searchQuery]);
        }
        $games = $this->getDoctrine()->getRepository(Game::class)->searchGames($searchQuery);
        $games = $this->paginator->paginate($games, $page ? $page : 1);

        return $this->render('search.html.twig', [
            'games' => $games,
        ]);
    }


    /**
     * @Route("/rel/{game}", name="rel_test")
     */
    public function relevant(Game $game, RelevantGamesService $relevantGames): Response
    {
        dump($relevantGames->handle($game));
        return new Response('<body>Hi</body>');
    }
}
