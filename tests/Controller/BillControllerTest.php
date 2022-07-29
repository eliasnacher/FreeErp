<?php

namespace App\Test\Controller;

use App\Entity\Bill;
use App\Test\AbstractWebTest;

class BillControllerTest extends AbstractWebTest
{
    private string $path = '/bill/';

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Bill index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->billRepository->findAll());

        $this->createDummyClient('testNew Client', 'testNew@client.local');

        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);


        $this->client->submitForm('Save', [
            'bill[base]' => '19.32',
            'bill[tax]' => '21.00',
            'bill[number]' => 'TST123',
            'bill[description]' => 'Testing',
            'bill[payAt]' => '2019-06-22',
            'bill[client]' => 0
        ]);

        self::assertResponseRedirects('/bill/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->billRepository->findAll()));
    }

    public function testShow(): void
    {
        $dummyClient = $this->createDummyClient('testShow Client', 'testshow@client.local');

        $fixture = new Bill();
        $fixture->setNumber('TST2135');
        $fixture->setClient($dummyClient);
        $fixture->setBase('123.23');
        $fixture->setTax('21.00');
        $fixture->setDescription('Test Description 1');
        $fixture->setPayAt(new \DateTime('1997-06-07'));

        $this->billRepository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Bill');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $dummyClient = $this->createDummyClient('testEdit Client', 'testedit@client.local');

        $fixture = new Bill();
        $fixture->setNumber('TST2135');
        $fixture->setClient($dummyClient);
        $fixture->setBase('242.12');
        $fixture->setTax('16.00');
        $fixture->setDescription('Test Description 2');
        $fixture->setPayAt(new \DateTime('1997-11-21'));

        $this->billRepository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'bill[base]' => '125.25',
            'bill[tax]' => '6.00',
            'bill[number]' => 'TST523',
            'bill[description]' => 'Test Description 2 Edit',
            'bill[payAt]' => '2021-02-21',
            'bill[client]' => '0'
        ]);

        self::assertResponseRedirects('/bill/');

        $fixture = $this->billRepository->findAll();

        self::assertSame('125.25', $fixture[0]->getBase());
        self::assertSame('6.00', $fixture[0]->getTax());
        self::assertSame('TST523', $fixture[0]->getNumber());
        self::assertSame('Test Description 2 Edit', $fixture[0]->getDescription());
        self::assertSame('2021-02-21', $fixture[0]->getPayAt()->format('Y-m-d'));
        self::assertSame($dummyClient->getId(), $fixture[0]->getClient()->getId());
    }

    public function testRemove(): void
    {
        $originalNumObjectsInRepository = count($this->billRepository->findAll());

        $dummyClient = $this->createDummyClient('testRemove Client', 'testremove@client.local');

        $fixture = new Bill();
        $fixture->setNumber('TSB2152');
        $fixture->setClient($dummyClient);
        $fixture->setBase('25.32');
        $fixture->setTax('32.00');
        $fixture->setDescription('Test Description 3');
        $fixture->setPayAt(new \DateTime('1988-02-01'));

        $this->billRepository->add($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->billRepository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->billRepository->findAll()));
        self::assertResponseRedirects('/bill/');
    }
}
