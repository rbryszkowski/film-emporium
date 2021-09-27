<?php

namespace App\Tests\Service;

use App\Exceptions\FilmNotFoundException;
use App\Models\FilmResponse;
use App\Service\OmdbHttpRequest;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class OmdbHttpRequestTest extends TestCase
{

    public function testGetFilmWithNullBodyResponse(): void
    {
        // Create a mock and queue two responses.
        $mockHandler = new MockHandler([
            new Response(200, [], null)
        ]);

        $handlerStack = HandlerStack::create($mockHandler);
        $client = new Client(['handler' => $handlerStack]);

        $omdbreq = new OmdbHttpRequest('34e585c5', $client);

        $result = $omdbreq->getFilmByTitle('bla');

        $this->assertEquals(null, $result->getTitle());
        $this->assertEquals(null, $result->getPlot());
        $this->assertEquals(null, $result->getDirector());
        $this->assertEquals(null, $result->getWriter());
        $this->assertEquals(null, $result->getGenre());
        $this->assertEquals(null, $result->getRated());
        $this->assertEquals(null, $result->getYear());
        $this->assertEquals(null, $result->getRatings());
        $this->assertEquals(null, $result->getPoster());

    }

    public function testGetFilm(): void
    {
        // Create a mock and queue two responses.

        // your json content here doesn't have Response
        $testBody = [
            "Response" => "True",
            "Title" => "title",
            "Plot" => "plot",
            "Director" => "director",
            "Writer" => "writer",
            "Genre" => "genre",
            "Rated" => "rated",
            "Year" => "year",
            "Runtime" => "runtime",
            "Ratings" => "ratings",
            "Poster" => "poster"
            ];

        $testBody = json_encode($testBody);

        $mockHandler = new MockHandler([
            new Response(200, [], $testBody)
        ]);

        $handlerStack = HandlerStack::create($mockHandler);
        $client = new Client(['handler' => $handlerStack]);

        $omdbreq = new OmdbHttpRequest('34e585c5', $client);

        $result = $omdbreq->getFilmByTitle('bla');

        $this->assertEquals('title', $result->getTitle());
        $this->assertEquals('plot', $result->getPlot());
        $this->assertEquals('director', $result->getDirector());
        $this->assertEquals('writer', $result->getWriter());
        $this->assertEquals('genre', $result->getGenre());
        $this->assertEquals('rated', $result->getRated());
        $this->assertEquals('year', $result->getYear());
        $this->assertEquals('ratings', $result->getRatings());
        $this->assertEquals('poster', $result->getPoster());

    }

    public function testOmdbCanHandle500ErrorRequest(): void
    {

        $this->expectException(ServerException::class);

        $mockHandler = new MockHandler([
            new Response(500, [], null)
        ]);

        $handlerStack = HandlerStack::create($mockHandler);

        $client = new Client(['handler' => $handlerStack]);

        $omdbreq = new OmdbHttpRequest('34e585c5', $client);

        $result = $omdbreq->getFilmByTitle('blablabla');

    }

    public function testOmdbCanHandle400ErrorRequest(): void
    {

        $this->expectException(RequestException::class);

        $mockHandler = new MockHandler([
            new Response(400, [], null)
        ]);

        $handlerStack = HandlerStack::create($mockHandler);

        $client = new Client(['handler' => $handlerStack]);

        $omdbreq = new OmdbHttpRequest('34e585c5', $client);

        $result = $omdbreq->getFilmByTitle('blablabla');

    }

    public function testOmdbThrowsInvalidArgumentExceptionWhenAnEmptyStringIsGiven(): void
    {

        $this->expectException(\InvalidArgumentException::class);

        $mockHandler = new MockHandler([
            new Response(200, [], null)
        ]);

        $handlerStack = HandlerStack::create($mockHandler);

        $client = new Client(['handler' => $handlerStack]);

        $omdbreq = new OmdbHttpRequest('34e585c5', $client);

        $result = $omdbreq->getFilmByTitle('');

    }

    public function testOmdbThrowsInvalidArgumentExceptionWhenStringWithWhitespaceIsGiven(): void
    {

        $this->expectException(\InvalidArgumentException::class);

        $mockHandler = new MockHandler([
            new Response(200, [], null)
        ]);

        $handlerStack = HandlerStack::create($mockHandler);

        $client = new Client(['handler' => $handlerStack]);

        $omdbreq = new OmdbHttpRequest('34e585c5', $client);

        $result = $omdbreq->getFilmByTitle('               ');

    }

    public function testOmdbThrowsTypeErrorWhenArgumentNotGiven(): void
    {

        $this->expectException(\TypeError::class);

        $mockHandler = new MockHandler([
            new Response(200, [], null),
        ]);

        $handlerStack = HandlerStack::create($mockHandler);

        $client = new Client(['handler' => $handlerStack]);

        $omdbreq = new OmdbHttpRequest('34e585c5', $client);

        $result = $omdbreq->getFilmByTitle();

    }

}
