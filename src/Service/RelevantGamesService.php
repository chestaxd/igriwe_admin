<?php


namespace App\Service;


use App\Entity\Game;
use App\Repository\GameRepository;

class RelevantGamesService
{
    /**
     * @var GameRepository
     */
    private GameRepository $gameRepository;

    public function __construct(GameRepository $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    public function handle(Game $game)
    {
        return $this->getRelevantGames($game);
    }

    private function getRelevantGames(Game $game)
    {
        $gameTags = $game->getTags();

        $tagCountIdx = [];
        $tagGameTotal = 0;
        $showTotalGames = 10;
        $tagCount = [];
        foreach ($gameTags as $tag) {
            $tags[$tag->getId()] = $tag;
            $countGamesInTag = $tag->getGames()->count();
            $tagGameTotal += $countGamesInTag;
            $tagCount[] = [
                'count' => $countGamesInTag,
                'id' => $tag->getId(),
            ];
        }
        if ($tagGameTotal < $showTotalGames) {
            return [];
        }
        if ($tagGameTotal == 0) {
            $tagGameTotal = $showTotalGames;
        }
        $k = $showTotalGames / $tagGameTotal; // коеф пропорциональности для подсчета количестра игр с тега

        $showTagList = [];

        foreach ($tagCount as $item) {
            $showTagList[$item['id']] = $item['count'] * $k; //сколько игры с тега показывать чтобы набрать нужное количество игр
            $tagCountIdx[$item['id']] = $item['count']; // id тега и сколько там игр
        }
        asort($showTagList);
        $tmp = $showTagList; // первый элемент имееет найменьшое значение
        arsort($showTagList);
        $tmp2 = array_values($showTagList); // первый элемент имееет найбольшое значение
        $i = 0;
        $relevantGames = [];
        $k = 1;
        $involved = 0;

        foreach ($tmp as $key => $value) {
            $showTagList[$key] = ceil($tmp2[$i] * $k); // задаем количество игр для определенного тега(с самого маленького тега показывать больше всего игр)
            unset($tmp2[$i]);
            if ($showTagList[$key] == 0) { // если игр получается 0, убираем тег и идем на следующею иттерацию
                unset($showTagList[$key]);
            } else {
                if ($showTagList[$key] > ($showTotalGames / 2) && $key == 1 && count($tagCount) > 1) { // если в теге больше игр чем половина всего и количество тего больше чем 1
                    $left = array_sum($tmp2);
                    $involved += ($showTotalGames / 2);
                    $k = ($showTotalGames - $involved) / $left;
                    $showTagList[$key] = $showTotalGames / 2;
                } else {
                    $involved += $showTagList[$key]; // сколько игр было задействоавно
                }
                if ($showTagList[$key] > $tagCountIdx[$key]) { // если в теге меньше игр чем нужно
                    $left = array_sum($tmp2); // сколько игр осталось в тегах
                    $tagCountIdx[$key]--;
                    $involved -= $showTagList[$key] - $tagCountIdx[$key];
                    $showTagList[$key] = $tagCountIdx[$key];
                    $k = ($showTotalGames - $involved) / $left;
                }
                $shift = 0;
                if ($tagCountIdx[$key] > 100) {
                    $shift = rand(0, 50);
                }
                if ($showTagList[$key] <= 0) continue;
                $games = $tags[$key]->getGames()->slice($shift, ceil($showTagList[$key]));
                foreach ($games as $game) {
                    $relevantGames[] = $game;
                }
            }
            $i++;
        }
        return array_slice($relevantGames, 0, $showTotalGames);
    }
}