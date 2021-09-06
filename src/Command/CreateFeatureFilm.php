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

        //get films
        $filmRepository = $this->entityManager->getRepository(Film::class)->findAll();
        $featureFilmRepo = $this->entityManager->getRepository(FeatureFilm::class)->findAll();

        //delete existing feature films
        foreach ($featureFilmRepo as &$film) {
            $this->entityManager->remove($film);
            $this->entityManager->flush();
        }
        unset($film);

        if (count($filmRepository) === 0) {
            $output->writeln('No films in repository!');
            return Command::FAILURE;
        }

        //choose 3 new ones from random
        for ($i = 1; $i <= 3; $i++) {
            $reposSize = count($filmRepository);
            $chosenFilm = $filmRepository[random_int(0, $reposSize-1)];
            $featureFilm = new FeatureFilm();
            $featureFilm->setFilmId($chosenFilm->getId());
            $this->entityManager->persist($featureFilm);
            $this->entityManager->flush();
        }

        $output->writeln('3 New Feature Films successfully created!');

        return Command::SUCCESS;
    }

}
