<?php

namespace App\Form;

use App\Entity\Departments;
use App\Repository\DepartmentsRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\ParentEntityAutocompleteType;


#[AsEntityAutocompleteField]
class DepartmentsAutocompleteField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Departments::class,
            'label' => 'Département',
            //'choice_label' => 'name',
            'multiple' => false,
            //'constraints' => [
            //    new Count(min: 1, minMessage: 'We need to eat *something*'),
            //],
            'filter_query' => function(QueryBuilder $qb, string $query, DepartmentsRepository $repository) {
                if (!$query) {
                    return;
                }

                $qb->andWhere('entity.name LIKE :filter' )
                    ->setParameter('filter', '%'.$query.'%');
            }

        ]);
    }
    public function getParent(): string
    {
        return ParentEntityAutocompleteType::class;
    }
}