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
    /** @var array listes des references connues (cf : memoîsation) */
    private $references = [];
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
     * @param string $groupName nom du groupe de référence
     * @param callable $factory     fonction qui génère 1 entité
     */

     protected function createMany(int $count,string $groupName, callable $factory)
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

             // On enregistre une référence à l'entité
             $reference = sprintf('%s_%d', $groupName, $i);
             $this->addReference($reference, $entity);
         }
     }

     /**
      * Récuperer 1 entité par son nom de groupe de référence
      * @param string $groupName 
      */

    protected function getRandomReference(string $groupName)
    {
            // Vérifier si  on a deja enregistrer les références des groupes demandés
            if (!isset($this->references[$groupName])) {
                // si non, on va les rechercher
                $this->references[$groupName] = [];
                
                foreach ($this->referenceRepository->getReferences() as $key => $ref) {
                    // la clé $key correspond à nos references
                    // si $key commence par $groupName, on le sauvegarde
                    if (strpos($key, $groupName) === 0) {
                        $this->references[$groupName][] = $key;

                }                
            }
        }

    // Verifier que l'on à recuperé des references
    if ($this->references[$groupName] === []) {
        throw new \Exception(sprintf('Aucune références trouvée pout le groupe"%s"', $groupName));
    }

    // Retourner une entité correspondant à une réféernce aléatoire
    $randomReference = $this->faker->randomElement($this->references[$groupName]);
    return $this->getReference($randomReference);
    }
}