<?php

namespace XO\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Helper\TableHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use XO\Player\PlayerInterface;
use XO\Service\Game;
use XO\Service\PlayerRegistry;

class FightCommand extends Command
{
    /**
     * Configure command
     */
    protected function configure()
    {
        $this->setName('xo:fight')
            ->addArgument('player-x', InputArgument::REQUIRED, 'Name for X player')
            ->addArgument('player-o', InputArgument::REQUIRED, 'Name for O player')
            ->addOption('games', 'g', InputOption::VALUE_REQUIRED, 'Count of games to play', 100);
    }

    /**
     * Execute command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $registry = PlayerRegistry::getDefaultPlayers();
        $player1 = $input->getArgument('player-x');
        $player2 = $input->getArgument('player-o');
        $count = (int) $input->getOption('games');

        $progress = new ProgressHelper();
        $progress->start($output, $count);

        $stats = [
            PlayerInterface::SYMBOL_X => 0,
            PlayerInterface::SYMBOL_O => 0,
            'Draw' => 0,
        ];

        for ($i = 0; $i < $count; $i++) {
            $game = new Game();
            $game->addPlayer($registry->get($player1), PlayerInterface::SYMBOL_X);
            $game->addPlayer($registry->get($player2), PlayerInterface::SYMBOL_O);

            $winner = $game->autoPlay();
            $stats[$winner ? $winner : 'Draw']++;
            $progress->advance();
        }

        $progress->finish();

        $output->writeln('');
        $output->writeln('Winning statistics');

        $table = new TableHelper();
        $table->setHeaders([$player1, $player2, "Draw"]);
        $table->addRow(array_values($stats));
        $table->render($output);
    }
}
