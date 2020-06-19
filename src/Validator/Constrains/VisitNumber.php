<?php


namespace App\Validator\Constrains;

use Symfony\Component\Validator\Constraint;

/**
 * Class VisitNumber
 * @Annotation
 */
class VisitNumber extends Constraint
{
    public $message = "Il y a trop de visiteurs à cette date, merci de sélectionner une autre date pour votre visite.";

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}