<?php

namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GenresControllerTest extends KernelTestCase
{
    public function testDeleteGenreWithIdNotANumber () : void
    {
        self::bootKernel([
            'environment' => 'my_test_env',
            'debug'       => false,
        ]);






    }
}
