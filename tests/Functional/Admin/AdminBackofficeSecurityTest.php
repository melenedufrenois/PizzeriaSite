<?php

declare(strict_types=1);

namespace App\Tests\Functional\Admin;

use App\Entity\Pizza;
use App\Tests\Functional\WebDatabaseTestCase;

final class AdminBackofficeSecurityTest extends WebDatabaseTestCase
{
    public function testAnonymousUserIsRedirectedToAdminLogin(): void
    {
        $client = static::createClient();
        $client->request('GET', '/admin/products');

        self::assertResponseStatusCodeSame(302);
        self::assertStringContainsString('/admin/login', (string) $client->getResponse()->headers->get('Location'));
    }

    public function testAdminLoginPageIsPublicAndDisplaysExpectedForm(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/admin/login');

        self::assertResponseIsSuccessful();
        self::assertPageTitleContains('Connexion - Administration Pizzeria');
        self::assertSame(1, $crawler->filter('form[action="/admin/login"]')->count());
        self::assertSame(1, $crawler->filter('input[name="_username"]')->count());
        self::assertSame(1, $crawler->filter('input[name="_password"]')->count());
    }

    public function testAdminCanLogInThroughTheLoginForm(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/admin/login');

        $client->submit($crawler->selectButton('Se connecter')->form([
            '_username' => self::ADMIN_EMAIL,
            '_password' => self::ADMIN_PASSWORD,
        ]));

        self::assertResponseRedirects('/admin/products');

        $client->followRedirect();
        self::assertResponseIsSuccessful();
        self::assertStringContainsString('Gestion des produits', $client->getResponse()->getContent());
    }

    public function testAdminProductsPageCanFilterByCategory(): void
    {
        static::createPizza(['name' => 'Reine']);
        static::createDrink(['name' => 'Cola']);

        $client = static::createAdminClient();
        $client->request('GET', '/admin/products?category=pizza');

        self::assertResponseIsSuccessful();
        self::assertStringContainsString('Reine', $client->getResponse()->getContent());
        self::assertStringNotContainsString('Cola', $client->getResponse()->getContent());
    }

    public function testAdminCanToggleAndDeleteAProduct(): void
    {
        $pizza = static::createPizza([
            'name' => 'Regina admin',
            'active' => true,
        ]);

        $client = static::createAdminClient();
        $client->request('POST', sprintf('/admin/product/%d/toggle', $pizza->getId()));

        self::assertResponseRedirects('/admin/products');

        $entityManager = static::getEntityManager();
        $entityManager->clear();
        /** @var Pizza $updatedPizza */
        $updatedPizza = $entityManager->getRepository(Pizza::class)->find($pizza->getId());
        self::assertInstanceOf(Pizza::class, $updatedPizza);
        self::assertFalse($updatedPizza->isActive());

        $client->request('DELETE', sprintf('/admin/product/%d', $pizza->getId()));

        self::assertResponseRedirects('/admin/products');
        $entityManager->clear();
        self::assertNull($entityManager->getRepository(Pizza::class)->find($pizza->getId()));
    }
}
