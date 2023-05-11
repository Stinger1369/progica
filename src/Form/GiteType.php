<?php

namespace App\Form;

use App\Entity\Gite;
use App\Entity\Services;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\BooleanType;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormTypeInterface;





class GiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('adress', TextType::class, [
                'required' => true,
                'label' => 'Adresse'
            ])
            ->add('city', CitiesAutocompleteField::class

            )
            ->add('department', DepartmentsAutocompleteField::class
            )
            ->add('region', RegionsAutocompleteField::class
            )
            ->add('surface')
            ->add('photos',FileType::class,[
                'label' =>'Photo du gîte',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '4000k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/gif',
                            'image/pjpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez selectionner un format valide',
                    ])
                ],
            ])
            ->add('rooms', TextType::class, [
                'required' => true,
                'label' => 'Nombre de chambres'
            ])
            ->add('beds', TextType::class, [
                'required' => true,
                'label' => 'Nombre de couchage'
            ])
            ->add('base_price', TextType::class, [
                'required' => true,
                'label' => 'Prix de base'
            ])
            ->add('id_price_period', TextType::class, [
                'required' => true,
                'label' => "Période de l'année"
            ])
            ->add('pets', CheckboxType::class, [
                'required' => false,
                'label' => 'Animaux acceptés'
            ])
            ->add('pets_price', TextType::class, [
                'required' => false,
                'label' => 'Si animaux acceptés veuillez en indiquer le tarif'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Gite::class,
        ]);
    }
}
