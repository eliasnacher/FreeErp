<?php

namespace App\Test\Controller;

use App\Entity\User;
use App\Test\AbstractWebTest;

class UserControllerTest extends AbstractWebTest
{
    private string $path = '/user/';

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('User index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->userRepository->findAll());

        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'user[username]' => 'testNew',
            'user[password]' => hash('sha256', 'testNew'),
        ]);

        self::assertResponseRedirects('/user/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->userRepository->findAll()));
    }

    public function testShow(): void
    {
        $fixture = new User();
        $fixture->setUsername('testShow');
        $fixture->setPassword(hash('sha256', 'testShow'));

        $this->userRepository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('User');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $fixture = new User();
        $fixture->setUsername('testEdit');
        $fixture->setPassword(hash('sha256', 'testEdit'));

        $this->userRepository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'user[username]' => 'editedTestEdit',
            'user[password]' => hash('sha256', 'editedTestEdit'),
        ]);

        self::assertResponseRedirects('/user/');

        $fixture = $this->userRepository->findAll();

        self::assertSame('editedTestEdit', $fixture[0]->getUsername());
        self::assertSame(hash('sha256', 'editedTestEdit'), $fixture[0]->getPassword());
    }

    public function testRemove(): void
    {
        $originalNumObjectsInRepository = count($this->userRepository->findAll());

        $fixture = new User();
        $fixture->setUsername('testRemove');
        $fixture->setPassword(hash('sha256', 'testNew'));

        $this->userRepository->add($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->userRepository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->userRepository->findAll()));
        self::assertResponseRedirects('/user/');
    }
}
