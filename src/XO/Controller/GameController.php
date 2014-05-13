<?php

namespace XO\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use XO\Player\PlayerInterface;
use XO\Service\Game;
use XO\Service\PlayerRegistry;

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

    /**
     * @var PlayerRegistry
     */
    protected $registryService;

    /**
     * @param \App $app
     * @param Game $gameService
     * @param PlayerRegistry $registryService
     */
    public function __construct(\App $app, Game $gameService, PlayerRegistry $registryService)
    {
        $this->app = $app;
        $this->gameService = $gameService;
        $this->registryService = $registryService;
    }

    /**
     * @return Response
     */
    public function indexAction()
    {
        return $this->app->render('index.html.twig', ['players' => $this->registryService->getNames()]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function indexJsonAction(Request $request)
    {
        $table = json_decode($request->get('table'));

        $request->get(PlayerInterface::SYMBOL_X) && $this->gameService->addPlayer(
            $this->registryService->get($request->get(PlayerInterface::SYMBOL_X))
        );

        $request->get(PlayerInterface::SYMBOL_O) && $this->gameService->addPlayer(
            $this->registryService->get($request->get(PlayerInterface::SYMBOL_O)),
            PlayerInterface::SYMBOL_O
        );

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
