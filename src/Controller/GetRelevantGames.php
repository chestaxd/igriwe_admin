<?php


namespace App\Controller;

use App\Entity\Game;
use App\Service\RelevantGamesService;


class GetRelevantGames
{
    private RelevantGamesService $relevantGames;

    public function __construct(RelevantGamesService $relevantGames)
    {
        $this->relevantGames = $relevantGames;
    }

    public function __invoke(Game $data)
    {

        return $this->relevantGames->handle($data);
    }
}