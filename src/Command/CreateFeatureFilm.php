<?php

namespace App\Command;
use App\Entity\FeatureFilm;
use App\Entity\Film;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;

class CreateFeatureFilm extends Command
{

    protected static $defaultName = 'app:create-feature-films';

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
            ->setDescription('Creates 3 new feature films from random.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        //get all films
        $filmRepository = $this->entityManager->getRepository(Film::class)->findAll();

        //delete current feature films
        $this->entityManager->getRepository(FeatureFilm::class)->deleteAll();

        if (count($filmRepository) === 0) {
            $output->writeln('No films in repository!');
            return Command::FAILURE;
        }

        $availableFilms = $filmRepository;

        //choose 3 new ones from random
        for ($i = 1; $i <= 3; $i++) {
            //pick a film at random from available films
            $reposSize = count($availableFilms);
            $chosenFilm = $availableFilms[random_int(0, $reposSize-1)];
            //Add film to FeatureFilms repo
            $featureFilm = new FeatureFilm();
            $featureFilm->setFilmId($chosenFilm->getId());
            $this->entityManager->persist($featureFilm);
            $this->entityManager->flush();
            //delete the chosen film from available films to avoid duplication
            $chosenFilmIndex = array_search($chosenFilm, $availableFilms, true);
            array_splice($availableFilms, $chosenFilmIndex, 1);
        }

        $output->writeln('3 New Feature Films successfully created!');

        return Command::SUCCESS;
    }

}
