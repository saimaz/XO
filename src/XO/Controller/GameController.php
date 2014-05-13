<?php

namespace XO\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use XO\Player\PlayerInterface;
use XO\Service\Game;

class GameController
{

    /**
     * @var \App
     */
    protected $app;

    /**
     * @var Game
     */
    protected $gameService;

    public function __construct(\App $app, Game $gameService)
    {
        $this->app = $app;
        $this->gameService = $gameService;
    }

    public function indexAction()
    {
        return $this->app->render('index.html.twig', []);
    }

    public function indexJsonAction(Request $request)
    {
        $table = json_decode($request->get('table'));

        $this->gameService->setTable($table);

        $this->gameService->getTurn();

        return new JsonResponse(
            [
                "status" => "ok",
                'table' => $this->gameService->getTable(),
                'winner' => $this->gameService->getWinner()
            ]
        );
    }
}
