<?php
namespace Main\CommonBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\MissingOptionsException;

/**
 * Déclaration de la classe PasseportValidator
 * @package RIP
 * @subpackage Validator
 * @category classes
 */
class DateRange extends Constraint
{
    /**
     *message date trop bas
     * @var string
     */
    public $minMessage = 'This date should be greater than {{ limit }}.';
    /**
     *message date trop éléver
     * @var string
     */
    public $maxMessage = 'This date should be less than {{ limit }}.';
    /**
     *message invalide
     * @var string
     */
    public $invalidMessage = 'This value should be a valid date.';
    /**
     *date minimum
     * @var string
     */
    public $min;
    /**
     *date maximum
     * @var string
     */
    public $max;
    /**
     * Constructeur de la classe DateRange
     * @param unknow $options
     * @throws MissingOptionsException
     */
    public function __construct($options = null)
    {
        parent::__construct($options);
       
        if (null === $this->min && null === $this->max) {
            throw new MissingOptionsException('Either option "min" or "max" must be given for constraint ' . __CLASS__, array(
                'min',
                'max'
            ));
        }
       
        if (null !== $this->min) {
            if (is_string($this->min)) {
                $this->min = new \DateTime($this->min);
            }
        }
       
        if (null !== $this->max) {
            if (is_string($this->max)) {
                $this->max = new \DateTime($this->max);
            }
        }
    }
}