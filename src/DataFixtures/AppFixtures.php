<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Category;
use App\Entity\Product;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        \Bezhanov\Faker\ProviderCollectionHelper::addAllProvidersTo($faker);
        // $product = new Product();
        // $manager->persist($product);
        for($i=0; $i<10; $i++){
            $cat = new Category();
            $cat->setNom($faker->department);
            for($j=0;$j<mt_rand(1,10); $j++){
                $prod = new Product();
                $prod->setNom($faker->productName)
                     ->setPrix($faker->numberBetween(10,10000))
                     ->setDescription($faker->sentence($nbWords = 6, $variableNbWords = true))
                     ->setContenu($faker->text)
                     //->setImage('http://picsum.photos/id/'.mt_rand(400, 800).'/290/180')
                     ->setImage($faker->imageUrl().mt_rand(400, 800).'/290/180')
                     ->setPromo((bool)rand(0,1));
                $manager->persist($prod);
                $cat->addProduct($prod);
            }
            $manager->persist($cat);
        }

        $manager->flush();
    }
}
