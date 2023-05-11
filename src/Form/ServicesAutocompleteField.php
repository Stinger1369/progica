<?php

namespace App\Form;

use App\Entity\Services;
use Symfony\Component\Form\AbstractType;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use App\Repository\ServicesRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\ParentEntityAutocompleteType;

#[AsEntityAutocompleteField]
class ServicesAutocompleteField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Services::class,
            'label' => 'Services',
            'choice_label' => 'name',
            'multiple' => true,
            //'constraints' => [
            //    new Count(min: 1, minMessage: 'We need to eat *something*'),
            //],
            'filter_query' => function(QueryBuilder $qb, string $query, ServicesRepository $repository) {
                if (!$query) {
                    return;
                }

                $qb->andWhere('entity.name LIKE :filter OR entity.price LIKE :filter')
                    ->setParameter('filter', '%'.$query.'%');
            }

        ]);
    }
    public function getParent(): string
    {
        return ParentEntityAutocompleteType::class;
    }
}