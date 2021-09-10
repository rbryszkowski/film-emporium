<?php

namespace App\Command;

use App\Entity\Director;
use App\Entity\Film;
use App\Entity\Genre;
use Doctrine\ORM\Mapping\Entity;
use GuzzleHttp\Client;
use PhpParser\Error;
use PHPUnit\Util\Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use function PHPUnit\Framework\throwException;

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
            ->setDescription('Add 10 random films from OMDB')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        for ($i = 1; $i <= 10; $i++) {

            do {
                $output->writeln('searching OMDB...');
                $randOmdbFilm = $this->getRandOmdbFilm();
            } while( $randOmdbFilm['Response'] === 'False' || $randOmdbFilm['Type'] !== 'movie' || $this->entityManager->getRepository(Film::class)->findOneBy(['title' => $randOmdbFilm['Title']]) );

            $output->writeln('found film with title: ' . $randOmdbFilm['Title']);

            $film = new Film();
            $film->setTitle($randOmdbFilm['Title']);
            $film->setDescription($randOmdbFilm['Plot']);

            if ($randOmdbFilm['Genre'] !== 'N/A') {
                //turn genres string into an array of Genre objects
                $omdbGenreArr = explode(',', $randOmdbFilm['Genre']);
                $genres = [];
                foreach($omdbGenreArr as &$omdbGenre) {
                    $omdbGenre = trim($omdbGenre);
                    //if the genre doesnt exist in the db, add it
                    if ( !$genre = $this->entityManager->getRepository(Genre::class)->findOneBy(['name' => $omdbGenre])) {
                        $genre = new Genre();
                        $genre->setName($omdbGenre);
                        $this->entityManager->persist($genre);
                    }
                    $genres[] = $genre;
                }
                unset($omdbGenre);

                //set these to films genres property
                $film->setGenres($genres);
            }

            if ( !$director = $this->entityManager->getRepository(Director::class)->findOneBy(['name' => $randOmdbFilm['Director']]) ) {
                $director = new Director();
                $director->setName($randOmdbFilm['Director']);
                $this->entityManager->persist($director);
            }
            $film->setDirector($director);

            $this->entityManager->persist($film);
            $this->entityManager->flush();

            dump($this->entityManager->getRepository(Film::class)->findOneBy(['title' => $randOmdbFilm['Title']]));

        }

        $output->writeln('10 new films successfully added!');

        return Command::SUCCESS;
    }

    private function returnRandWord() {

        $filePath = 'Resources/englishDictionary.txt';
        $fileAsArr = file($filePath);
        $lines = count($fileAsArr);
        $randLine = random_int(0, $lines-1);
        return $fileAsArr[$randLine];

    }

    private function getRandOmdbFilm() {
        $client = new Client();

        //IMDB id is a 7 digit number prefixed with 'tt' (between 0 and 2155529)
        $randImdbId = 'tt' . $this->pad(random_int(0, 2155529), 7);

        $params = http_build_query([
            'apikey' => '34e585c5',
            'i' => $randImdbId
        ]);

        $url = 'http://www.omdbapi.com/?' . $params;

        $response = $client->request('GET', $url);

        $omdbData = json_decode($response->getBody(), true);

        return $omdbData;
    }

    private function pad($number, $length) :string
    {
        $str = '' . $number;
        while(strlen($str) < $length) {
            $str = '0' . $str;
        }
        return $str;
    }


}
