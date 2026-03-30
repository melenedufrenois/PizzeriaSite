<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Entity\Drink;
use App\Entity\Pizza;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

abstract class WebDatabaseTestCase extends WebTestCase
{
    protected const ADMIN_EMAIL = 'admin@pizzeria.fr';
    protected const ADMIN_PASSWORD = 'admin123';

    protected function setUp(): void
    {
        parent::setUp();

        self::ensureKernelShutdown();
        self::bootKernel();

        $entityManager = static::getEntityManager();
        $metadata = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($entityManager);

        if ($metadata !== []) {
            $schemaTool->dropSchema($metadata);
            $schemaTool->createSchema($metadata);
        }

        $this->createAdminUser($entityManager);

        $entityManager->clear();
        self::ensureKernelShutdown();
    }

    protected static function createAdminClient(): KernelBrowser
    {
        self::ensureKernelShutdown();
        $client = static::createClient();
        $user = static::getEntityManager()->getRepository(User::class)->findOneBy([
            'email' => self::ADMIN_EMAIL,
        ]);

        self::assertInstanceOf(User::class, $user);
        $client->loginUser($user, 'admin');

        return $client;
    }

    protected static function createPizza(array $overrides = []): Pizza
    {
        $pizza = new Pizza();
        $pizza->setName($overrides['name'] ?? 'Pizza test');
        $pizza->setDescription($overrides['description'] ?? 'Pizza de test');
        $pizza->setImage($overrides['image'] ?? 'https://example.com/pizza-test.jpg');
        $pizza->setPrice($overrides['price'] ?? '12.50');
        $pizza->setType($overrides['type'] ?? 'pizza');
        $pizza->setActive($overrides['active'] ?? true);
        $pizza->setPopular($overrides['popular'] ?? false);
        $pizza->setDisplayOrder($overrides['displayOrder'] ?? 0);
        $pizza->setAllergens($overrides['allergens'] ?? []);
        $pizza->setBase($overrides['base'] ?? Pizza::BASE_TOMATE);
        $pizza->setIngredients($overrides['ingredients'] ?? ['tomate', 'mozzarella']);

        $entityManager = static::getEntityManager();
        $entityManager->persist($pizza);
        $entityManager->flush();

        return $pizza;
    }

    protected static function createDrink(array $overrides = []): Drink
    {
        $drink = new Drink();
        $drink->setName($overrides['name'] ?? 'Boisson test');
        $drink->setDescription($overrides['description'] ?? 'Boisson de test');
        $drink->setImage($overrides['image'] ?? 'https://example.com/boisson-test.jpg');
        $drink->setPrice($overrides['price'] ?? '3.50');
        $drink->setType($overrides['type'] ?? 'boisson');
        $drink->setActive($overrides['active'] ?? true);
        $drink->setPopular($overrides['popular'] ?? false);
        $drink->setDisplayOrder($overrides['displayOrder'] ?? 0);
        $drink->setAllergens($overrides['allergens'] ?? []);
        $drink->setVolume($overrides['volume'] ?? '33cl');
        $drink->setIsAlcoholic($overrides['isAlcoholic'] ?? false);

        $entityManager = static::getEntityManager();
        $entityManager->persist($drink);
        $entityManager->flush();

        return $drink;
    }

    protected static function getEntityManager(): EntityManagerInterface
    {
        return static::getContainer()->get('doctrine')->getManager();
    }

    private function createAdminUser(EntityManagerInterface $entityManager): void
    {
        $admin = new User();
        $admin->setEmail(self::ADMIN_EMAIL);
        $admin->setRoles(['ROLE_ADMIN']);

        /** @var UserPasswordHasherInterface $passwordHasher */
        $passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);
        $admin->setPassword($passwordHasher->hashPassword($admin, self::ADMIN_PASSWORD));

        $entityManager->persist($admin);
        $entityManager->flush();
    }
}
