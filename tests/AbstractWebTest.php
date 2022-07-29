<?php

namespace App\Test;

use App\Entity\Bill;
use App\Entity\User;
use App\Entity\Client;
use App\Repository\BillRepository;
use App\Repository\UserRepository;
use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

abstract class AbstractWebTest extends WebTestCase
{
    public UserRepository $userRepository;
    public ClientRepository $clientRepository;
    public BillRepository $billRepository;
    public KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $this->userRepository   = (static::getContainer()->get('doctrine'))->getRepository(User::class);
        $this->clientRepository = (static::getContainer()->get('doctrine'))->getRepository(Client::class);
        $this->billRepository   = (static::getContainer()->get('doctrine'))->getRepository(Bill::class);

        foreach ($this->userRepository->findAll()   as $object)  $this->userRepository->remove($object, true);
        foreach ($this->clientRepository->findAll() as $object)  $this->clientRepository->remove($object, true);
        foreach ($this->billRepository->findAll()   as $object)  $this->billRepository->remove($object, true);
    }

    public function createDummyUser (string $username) : User
    {
        $dummyUser = new User ();
        $dummyUser->setUsername($username);
        $this->userRepository->add($dummyUser, true);
        return $dummyUser;
    }

    public function createDummyClient (string $name, string $email) : Client
    {
        $dummyClient = new Client ();
        $dummyClient->setName($name);
        $dummyClient->setEmail($email);
        $this->clientRepository->add($dummyClient, true);
        return $dummyClient;
    }

    public function createDummyBill (string $number, Client $client) : Bill
    {
        $dummyBill = new Bill ();
        $dummyBill->setNumber($number);
        $dummyBill->setClient($client);
        $this->billRepository->add($dummyBill, true);
        return $dummyBill;
    }

}
