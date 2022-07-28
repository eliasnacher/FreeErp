<?php

namespace App\Form;

use App\Entity\Bill;
use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface ;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class BillType extends AbstractType
{
    private $entityManager;

    function __construct(EntityManagerInterface  $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $clientRepository = $this->entityManager->getRepository(Client::class);

        // Load clients list to display it in a select.
        $clientOptions = [];
        foreach($clientRepository->findAll() as $client)
            $clientOptions[$client->getId() . ' - ' . $client->getName()] = $client;

        $builder
            ->add('base')
            ->add('tax')
            ->add('number')
            ->add('description')
            ->add('client', ChoiceType::class, [
                'choices' => $clientOptions
            ])
            ->add('payAt', DateType::class, [ 'widget' => 'single_text' ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bill::class,
        ]);
    }
}
