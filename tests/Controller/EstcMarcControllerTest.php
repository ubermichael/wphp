<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Controller;

use App\DataFixtures\EstcMarcFixtures;
use App\Repository\EstcMarcRepository;
use App\Services\MarcManager;
use Nines\UserBundle\DataFixtures\UserFixtures;
use Nines\UtilBundle\Tests\ControllerBaseCase;

class EstcMarcControllerTest extends ControllerBaseCase {
    protected function fixtures() : array {
        return [
            UserFixtures::class,
            EstcMarcFixtures::class,
        ];
    }

    /**
     * @group anon
     * @group index
     */
    public function testAnonIndex() : void {
        $crawler = $this->client->request('GET', '/resource/estc/');
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @group user
     * @group index
     */
    public function testUserIndex() : void {
        $this->login('user.user');
        $crawler = $this->client->request('GET', '/resource/estc/');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @group admin
     * @group index
     */
    public function testAdminIndex() : void {
        $this->login('user.admin');
        $crawler = $this->client->request('GET', '/resource/estc/');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @group anon
     * @group show
     */
    public function testAnonShow() : void {
        $crawler = $this->client->request('GET', '/resource/estc/1');
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @group user
     * @group show
     */
    public function testUserShow() : void {
        $this->login('user.user');
        $crawler = $this->client->request('GET', '/resource/estc/1');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @group admin
     * @group show
     */
    public function testAdminShow() : void {
        $this->login('user.admin');
        $crawler = $this->client->request('GET', '/resource/estc/1');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
    }

    public function testAnonSearch() : void {
        $crawler = $this->client->request('GET', '/resource/estc/search');
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('Search')->count());
    }

    public function testUserSearch() : void {
        $repo = $this->createMock(EstcMarcRepository::class);
        $repo->method('searchQuery')->willReturn([$this->getReference('estc.0.0.2')]);
        $repo->method('findOneBy')->willReturn($this->getReference('estc.0.0.0'));
        $manager = $this->createMock(MarcManager::class);
        $manager->method('getAuthor')->willReturn('Ben Muffin');
        $manager->method('getTitle')->willReturn('The Cheese Diggers');

        $this->login('user.user');
        $this->client->disableReboot();
        $this->client->getContainer()->set(EstcMarcRepository::class, $repo);
        $this->client->getContainer()->set(MarcManager::class, $manager);

        $formCrawler = $this->client->request('GET', '/resource/estc/search');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $form = $formCrawler->selectButton('Search')->form([
            'q' => 'adventures',
        ]);

        $responseCrawler = $this->client->submit($form);
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('td:contains("Ben Muffin")')->count());
    }

    public function testAdminSearch() : void {
        $repo = $this->createMock(EstcMarcRepository::class);
        $repo->method('searchQuery')->willReturn([$this->getReference('estc.0.0.2')]);
        $repo->method('findOneBy')->willReturn($this->getReference('estc.0.0.0'));
        $manager = $this->createMock(MarcManager::class);
        $manager->method('getAuthor')->willReturn('Ben Muffin');
        $manager->method('getTitle')->willReturn('The Cheese Diggers');

        $this->login('user.admin');
        $this->client->disableReboot();
        $this->client->getContainer()->set(EstcMarcRepository::class, $repo);
        $this->client->getContainer()->set(MarcManager::class, $manager);

        $formCrawler = $this->client->request('GET', '/resource/estc/search');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $form = $formCrawler->selectButton('Search')->form([
            'q' => 'adventures',
        ]);

        $responseCrawler = $this->client->submit($form);
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('td:contains("Ben Muffin")')->count());
    }

    public function testAnonImprintSearch() : void {
        $crawler = $this->client->request('GET', '/resource/estc/imprint_search');
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('Search')->count());
    }

    public function testUserImprintSearch() : void {
        $repo = $this->createMock(EstcMarcRepository::class);
        $repo->method('imprintSearchQuery')->willReturn([$this->getReference('estc.0.0.2')]);
        $repo->method('findOneBy')->willReturn($this->getReference('estc.0.0.0'));
        $manager = $this->createMock(MarcManager::class);
        $manager->method('getFieldValues')->willReturn(['Ben Muffin']);
        $manager->method('getTitle')->willReturn('The Cheese Diggers');

        $this->login('user.user');
        $this->client->disableReboot();
        $this->client->getContainer()->set(EstcMarcRepository::class, $repo);
        $this->client->getContainer()->set(MarcManager::class, $manager);

        $formCrawler = $this->client->request('GET', '/resource/estc/imprint_search');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $form = $formCrawler->selectButton('Search')->form([
            'q' => 'adventures',
        ]);

        $responseCrawler = $this->client->submit($form);
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('td:contains("Ben Muffin")')->count());
    }

    public function testAdminImprintSearch() : void {
        $repo = $this->createMock(EstcMarcRepository::class);
        $repo->method('imprintSearchQuery')->willReturn([$this->getReference('estc.0.0.2')]);
        $repo->method('findOneBy')->willReturn($this->getReference('estc.0.0.0'));
        $manager = $this->createMock(MarcManager::class);
        $manager->method('getFieldValues')->willReturn(['Ben Muffin']);
        $manager->method('getTitle')->willReturn('The Cheese Diggers');

        $this->login('user.admin');
        $this->client->disableReboot();
        $this->client->getContainer()->set(EstcMarcRepository::class, $repo);
        $this->client->getContainer()->set(MarcManager::class, $manager);

        $formCrawler = $this->client->request('GET', '/resource/estc/imprint_search');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $form = $formCrawler->selectButton('Search')->form([
            'q' => 'adventures',
        ]);

        $responseCrawler = $this->client->submit($form);
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('td:contains("Ben Muffin")')->count());
    }
}
