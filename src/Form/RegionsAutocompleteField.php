<?php

namespace App\Form;

use App\Entity\Regions;
use App\Repository\RegionsRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\ParentEntityAutocompleteType;

#[AsEntityAutocompleteField]
class RegionsAutocompleteField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Regions::class,
            'label' => 'Région',
            //'choice_label' => 'name',
            'multiple' => false,
            //'constraints' => [
            //    new Count(min: 1, minMessage: 'We need to eat *something*'),
            //],
            'filter_query' => function(QueryBuilder $qb, string $query, RegionsRepository $repository) {
                if (!$query) {
                    return;
                }

                $qb->andWhere('entity.name LIKE :filter')
                    ->setParameter('filter', '%'.$query.'%');
            }

        ]);
    }
    public function getParent(): string
    {
        return ParentEntityAutocompleteType::class;
    }
}