<?php

namespace App\Form;

use App\Entity\Tasks;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Libellé'
            ])
            ->add('description', TextType::class, [
                'label' => 'Description'
            ])
            ->add('illustration', FileType::class, [
                'label' => 'Illustration',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Renseigner une illustration'
                    ]),
                    new File([
                        'maxSize' => '3M',
                        'mimeTypesMessage' => 'Format invalide'
                    ])
                ]
            ])
            ->add('limitdate', DateTimeType::class, [
                'label' => 'Date limite'
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'RDV' => 'RDV',
                    'Prestation' => 'Prestation',
                    'Deadline' => 'Deadline'
                ]
            ])
            ->add('Envoyer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tasks::class,
        ]);
    }
}
