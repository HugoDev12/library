<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Books;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $datas = file_get_contents("./books.json");
        $books = json_decode($datas, true);

        foreach ($books as $book){
            $newBook = new Books();

            if (array_key_exists("shortDescription", $book))
            {
                $newBook->setDescription($book["shortDescription"]);
            }

            if (array_key_exists("thumbnailUrl", $book))
            {
                $newBook->setImageName($book["thumbnailUrl"]);
            }

            // array_key_exists(string|int $key, array $array): bool

        
        $newBook->setTitle($book["title"]);
        $newBook->setDate(new \DateTime("now"));
        $newBook->setAuthor($book["authors"]);
        $newBook->setCategory($book["categories"]);
        $newBook->setStatus(1);
        $newBook->setImageFile(null);
        $newBook->setLoanDate(null);
        $newBook->setDueDate(null);
        $newBook->setLastUser(null);
        $manager->persist($newBook);
        }

        $manager->flush();
    }
}
