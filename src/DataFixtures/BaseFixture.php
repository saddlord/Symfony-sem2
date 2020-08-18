<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

/**
 * Classe "modèle" pour les fixtures
 * On ne peut pas instancier une abstraction
 */
abstract class BaseFixture extends Fixture
{
    /** @var ObjectManager */
    private $manager;
    /** @var Generator */
    protected $faker;

    /**
     * Méthode à implémenter par les classes qui héritent
     * pour générer les fausses données
     */
    abstract protected function loadData();

    /**
     * Méthode appelée par le système de fixtures
     */
    public function load(ObjectManager $manager)
    {
        // On enregistre le ObjectManager
        $this->manager = $manager;
        // On instancie Faker
        $this->faker = Factory::create('fr_FR');

        // On appelle loadData() pour avoir les fausses données
        $this->loadData();
        // On exécute l'enregistrement en base
        $this->manager->flush();
    }
    
    /**
     * Enregistrer plusieurs entités
     * @param int $count    nombre d'entités à gerer
     * @param callable $factory     fonction qui génère 1 entité
     */

     protected function createMany(int $count, callable $factory)
     {
         for ($i = 0; $i < $count; $i++) {
             // On exécute $factory qui doit executer l'entité generée
            $entity =  $factory();

             // Verifier que l'entité ait été retourné

             if ($entity === null){
                throw_new \LogicException('L\'entité doit etre retournée, auriez vous oublié un return ?');
                
            }
             // On prépare à l'enregisgtrement de l'entité
             $this->manager->persist($entity);
         }
     }
}