<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\ Memo;

class MemoFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i = 1; $i <= 5; $i++){
            $memo = new Memo();
            $memo->setTitre("titre du memo $i")
                 ->setContenu("<p>cont du memo n: $i</p>")
                 ->setAuteur("Auteur n: $i");

            $manager->persist($memo);
        }


        $manager->flush();
    }
}
