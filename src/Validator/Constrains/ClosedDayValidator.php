<?php


namespace App\Validator\Constrains;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ClosedDayValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$value instanceof \DateTime)
        {
            $value = new \DateTime($value);
        }

        $year = (int) $value->format('Y');
        if (!$year)
        {
            $year = intval(date('Y'));

        }

        $closedDays = [
            mktime(0, 0, 0, 5,  1,  $year),
            mktime(0, 0, 0, 11, 1,  $year),
            mktime(0, 0, 0, 12, 25, $year),
        ];

        $timestamp = $value->getTimestamp();
        $day = (int) date("w",$timestamp);

        if (in_array($timestamp, $closedDays) || $day == 2 || $day == 0) {
            $this->context->addViolation($constraint->message);
       }
    }
}