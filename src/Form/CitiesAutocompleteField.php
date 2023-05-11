<?php

namespace App\Form;

use App\Entity\Cities;
use App\Repository\CitiesRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\ParentEntityAutocompleteType;


#[AsEntityAutocompleteField]
class CitiesAutocompleteField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Cities::class,
            'label' => 'Ville',
            //'choice_label' => 'name',
            'multiple' => false,
            //'constraints' => [
            //    new Count(min: 1, minMessage: 'We need to eat *something*'),
            //],
            'filter_query' => function(QueryBuilder $qb, string $query, CitiesRepository $repository) {
                if (!$query) {
                    return;
                }

                $qb->andWhere('entity.name LIKE :filter OR entity.zip_code LIKE :filter')
                    ->setParameter('filter', '%'.$query.'%');
            }

        ]);
    }
    public function getParent(): string
    {
        return ParentEntityAutocompleteType::class;
    }


}