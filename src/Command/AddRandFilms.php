<?php

namespace App\Command;

use App\Entity\Director;
use App\Entity\Film;
use App\Entity\Genre;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;

class AddRandFilms extends Command
{

    protected static $defaultName = 'app:add-films';

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
            ->setDescription('Add 10 randomly created films to database')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $allFilms = $this->entityManager->getRepository(Film::class)->findAll();
        $allGenres = $this->entityManager->getRepository(Genre::class)->findAll();
        $allDirectors = $this->entityManager->getRepository(Director::class)->findAll();

        for ($i = 1; $i <= 10; $i++) {
            $loopCount=0;
            $randTitle = null;
            while($loopCount < 100) {
                $noFilmsWithSameTitle = true;
                $randTitle = $this->returnRanWord() . $this->returnRanWord() . $this->returnRanWord();
                foreach($allFilms as $film) {
                    if ($film->getTitle() === $randTitle) {
                        $noFilmsWithSameTitle = false;
                        break;
                    }
                }
                if($noFilmsWithSameTitle === true) {
                    break;
                }
                $loopCount++;
            }

            $randDesc = $this->returnRanWord();
            $randGenre = [0=>$allGenres[random_int(0, count($allGenres) - 1)]];
            $randDirector = $allDirectors[random_int(0, count($allDirectors) - 1)];


            $film = new Film();
            $film->setTitle($randTitle);
            $film->setDescription($randDesc);
            $film->setGenres($randGenre);
            $film->setDirector($randDirector);
            $this->entityManager->persist($film);
            $this->entityManager->flush();
        }

        $output->writeln('10 new films successfully added!');

        return Command::SUCCESS;
    }

    private function returnRanWord() {

        $filePath = 'Resources/englishDictionary.txt';
        $fileAsArr = file($filePath);
        $lines = count($fileAsArr);
        $randLine = random_int(0, $lines-1);
        return $fileAsArr[$randLine];

    }

}
