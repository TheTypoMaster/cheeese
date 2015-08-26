<?php

namespace Main\CommonBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class DateRangeValidator extends ConstraintValidator
{

    /**
     * teste si la plage de date est valide
     * @param \DateTime $value
     * @param Constraint $constraint
     * @return type
     * @throws UnexpectedTypeException
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof DateRange) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\DateRange');
        }
        if (null === $value) {
            return;
        }

        if (!($value instanceof \DateTime)) {
            $this->context->addViolation($constraint->invalidMessage, array(
                '{{ value }}' => $value->format('Y-m-d H:i:s'),
            ));

            return;
        }

        if (null !== $constraint->max && $value > $constraint->max) {
            $this->context->addViolation($constraint->maxMessage, array(
                '{{ value }}' => $value->format('Y-m-d H:i:s'),
                '{{ limit }}' => $this->formatDate($constraint->max),
            ));
        }

        if (null !== $constraint->min && $value < $constraint->min) {
            $this->context->addViolation($constraint->minMessage, array(
                '{{ value }}' => $value->format('Y-m-d H:i:s'),
                '{{ limit }}' => $this->formatDate($constraint->min),
            ));
        }
    }

    /**
     * formate la date
     * @param DateTime $date
     * @return \DateTime
     */
    protected function formatDate($date)
    {
        $formatter = new \IntlDateFormatter(
            'en',
            \IntlDateFormatter::SHORT,
            \IntlDateFormatter::NONE,
            date_default_timezone_get(),
            \IntlDateFormatter::GREGORIAN
        );

        return $this->processDate($formatter, $date);
    }

    /**
     * format ela date dd/MM/yyyy
     * @param  \IntlDateFormatter $formatter
     * @param  \Datetime          $date
     * @return string
     */
    protected function processDate(\IntlDateFormatter $formatter, \Datetime $date)
    {
        $formatter->setPattern('dd/MM/yyyy');
        return $formatter->format((int) $date->format('U'));
    }
}