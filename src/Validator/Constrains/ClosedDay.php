<?php


namespace App\Validator\Constrains;

use Symfony\Component\Validator\Constraint;


/**
 * Class ClosedDay
 * @Annotation
 */
class ClosedDay extends Constraint
{
    public $message = "Vous ne pouvez pas réserver à cette date, le musée est fermé.";

}