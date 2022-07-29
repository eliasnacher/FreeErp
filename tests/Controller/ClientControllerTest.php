<?php

namespace App\Test\Controller;

use App\Entity\Client;
use App\Test\AbstractWebTest;

class ClientControllerTest extends AbstractWebTest
{
    private string $path = '/client/';

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Client index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->clientRepository->findAll());

        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'client[name]' => 'testNew',
            'client[email]' => 'testnew@client.local',
        ]);

        self::assertResponseRedirects('/client/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->clientRepository->findAll()));
    }

    public function testShow(): void
    {
        $fixture = new Client();
        $fixture->setName('testShow');
        $fixture->setEmail('testshow@client.local');

        $this->clientRepository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Client');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $fixture = new Client();
        $fixture->setName('testEdit');
        $fixture->setEmail('testedit@client.local');

        $this->clientRepository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'client[name]' => 'changedTestEdit',
            'client[email]' => 'changedtestedit@client.local',
        ]);

        self::assertResponseRedirects('/client/');

        $fixture = $this->clientRepository->findAll();

        self::assertSame('changedTestEdit', $fixture[0]->getName());
        self::assertSame('changedtestedit@client.local', $fixture[0]->getEmail());
    }

    public function testRemove(): void
    {
        $originalNumObjectsInRepository = count($this->clientRepository->findAll());

        $fixture = new Client();
        $fixture->setName('testRemove');
        $fixture->setEmail('testremove@client.local');

        $this->clientRepository->add($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->clientRepository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->clientRepository->findAll()));
        self::assertResponseRedirects('/client/');
    }
}
