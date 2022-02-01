<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Books;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $datas = file_get_contents("../books.json");
        $books = json_decode($datas);

        foreach ($books as $book){
            $newBook = new Books();
            $newBook->setTitle($book["title"]);
            $newBook->setPageCount($book["pageCount"]);
            $newBook->setPublishedDate($book["publishedDate"]["dt_txt"]);
            $newBook->setShortDescription($book["shortDescription"]);
            $newBook->setLongDescription($book["longDescription"]);
            $newBook->setAuthors($book["authors"]);
            $newBook->setCategories($book["categories"]);
            $newBook->setImageName($book["thumbnailUrl"]);
            $newBook->setLoanDate(null);
            $newBook->setDueDate(null);
            $newBook->setLastUser(null);
            $manager->persist($newBook);
        }

        $manager->flush();
    }
}
