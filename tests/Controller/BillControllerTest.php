<?php

namespace App\Test\Controller;

use App\Entity\Bill;
use App\Repository\BillRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BillControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private BillRepository $repository;
    private string $path = '/bill/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = (static::getContainer()->get('doctrine'))->getRepository(Bill::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

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
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'bill[base]' => 'Testing',
            'bill[tax]' => 'Testing',
            'bill[number]' => 'Testing',
            'bill[description]' => 'Testing',
            'bill[createdAt]' => 'Testing',
            'bill[updatedAt]' => 'Testing',
            'bill[payAt]' => 'Testing',
            'bill[client]' => 'Testing',
        ]);

        self::assertResponseRedirects('/bill/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Bill();
        $fixture->setBase('My Title');
        $fixture->setTax('My Title');
        $fixture->setNumber('My Title');
        $fixture->setDescription('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setUpdatedAt('My Title');
        $fixture->setPayAt('My Title');
        $fixture->setClient('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Bill');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Bill();
        $fixture->setBase('My Title');
        $fixture->setTax('My Title');
        $fixture->setNumber('My Title');
        $fixture->setDescription('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setUpdatedAt('My Title');
        $fixture->setPayAt('My Title');
        $fixture->setClient('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'bill[base]' => 'Something New',
            'bill[tax]' => 'Something New',
            'bill[number]' => 'Something New',
            'bill[description]' => 'Something New',
            'bill[createdAt]' => 'Something New',
            'bill[updatedAt]' => 'Something New',
            'bill[payAt]' => 'Something New',
            'bill[client]' => 'Something New',
        ]);

        self::assertResponseRedirects('/bill/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getBase());
        self::assertSame('Something New', $fixture[0]->getTax());
        self::assertSame('Something New', $fixture[0]->getNumber());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getCreatedAt());
        self::assertSame('Something New', $fixture[0]->getUpdatedAt());
        self::assertSame('Something New', $fixture[0]->getPayAt());
        self::assertSame('Something New', $fixture[0]->getClient());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Bill();
        $fixture->setBase('My Title');
        $fixture->setTax('My Title');
        $fixture->setNumber('My Title');
        $fixture->setDescription('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setUpdatedAt('My Title');
        $fixture->setPayAt('My Title');
        $fixture->setClient('My Title');

        $this->repository->add($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/bill/');
    }
}
