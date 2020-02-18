<?php


namespace App\Form;

use App\Entity\Ticket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $currentDate = new \DateTime();
        $currentYear = $currentDate->format('Y');
        $year = $currentYear - 20;

        $builder->add('firstname');
        $builder->add('lastname');
        $builder->add('birthdate', BirthdayType::class, array(
            'format' =>'ddMMyyyy',
            'widget' => 'choice',
            'data' => new \DateTime($year.'-01-01')
        ));
        $builder->add('country', CountryType::class, array('data' => 'FR'));
        $builder->add('dayticket', ChoiceType::class, array(
            'choices' => array (
                'Journée complète' => true,
                'Demi-journée (à partir de 14h)' => false,
            ),
            'attr' => array('class' => 'ticketType'),
            'data' =>'true',


        ));
        $builder->add('discountticket', CheckboxType::class, array(
            'required' => false,
            'label' => 'Tarif réduit'
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
        ]);
    }
}