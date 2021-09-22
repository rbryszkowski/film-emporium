<?php


namespace App\Form;


use App\Entity\Director;
use App\Entity\Film;
use App\Entity\Genre;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilmType extends AbstractType
{
    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

//        $formBase = $builder
//            ->add('title', TextType::class)
//            ->add('description', TextType::class)
//            ->add('submit', SubmitType::class);
//
//        if ($this->em->getRepository(Genre::class)->findAll() !== []) {
//            $formBase
//                ->add('genres', EntityType::class, [
//                    'class' => Genre::class,
//                    'choice_label' => 'name',
//                    'multiple' => true
//                ]);
//        } else {
//            $formBase
//                ->add('genres', TextType::class);
//        }
//
//        if ($this->em->getRepository(Director::class)->findAll() !== []) {
//            $formBase
//                ->add('director', EntityType::class, [
//                    'class' => Director::class,
//                    'choice_label' => 'name',
//                    'multiple' => true
//                ]);
//        } else {
//            $formBase
//                ->add('director', TextType::class);
//        }

        $builder
            ->add('title', TextType::class)
            ->add('description', TextType::class)
            ->add('genres', EntityType::class, [
                'class' => Genre::class,
                'choice_label' => 'name',
                'multiple' => true
            ])
            ->add('director', EntityType::class, [
                'class' => Director::class,
                'choice_label' => 'name'
            ])
            ->add('submit', SubmitType::class)
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Film::class,
        ]);
    }
}