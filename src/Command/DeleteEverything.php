<?php

namespace App\Command;

use App\Entity\Director;
use App\Entity\FeatureFilm;
use App\Entity\Film;
use App\Entity\Genre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class DeleteEverything extends Command
{

    protected static $defaultName = 'app:delete-everything';

    private $entityManager;

    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManager = $em;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Deletes all films, feature films, genres and directors from the database.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {

        $this->entityManager->getRepository(Film::class)->deleteAll();
        $this->entityManager->getRepository(FeatureFilm::class)->deleteAll();
        $this->entityManager->getRepository(Genre::class)->deleteAll();
        $this->entityManager->getRepository(Director::class)->deleteAll();

        $output->writeln('database wiped!');

        return Command::SUCCESS;

    }

}
