<?php

namespace App\Form\Type;


use App\Form\Model\TicketsDTO;
use App\Form\Model\UserFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', TextType::class)
            ->add('name', TextType::class)
            ->add('description', TextType::class)
            ->add('type', TextType::class)
            ->add('state', TextType::class)
            ->add('date',  DateType::class, ['widget' => 'single_text', 'format' => 'dd/MM/yyyy', 'html5' => false])
            ->add('priority', TextType::class)
            ->add('project', CollectionType::class, [
                'entry_type' => ProjectType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ])
            ->add('user', CollectionType::class, [
                'entry_type' => UserFormType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TicketsDTO::class,
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }

    public function getName(): string
    {
        return '';
    }
}