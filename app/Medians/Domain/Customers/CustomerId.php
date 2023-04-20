<?php


namespace Medians\Domain\Customers;

class CustomerId
{
    private $customerId;

    public function __construct($aCuseromdId)
    {
        $this->customerId = $aCuseromdId;
    }

    /**
     * Returns a string that can be parsed by fromString()
     * @return string
     */
    public function __toString()
    {
        return (string) $this->customerId;
    }

    public function equals(CustomerId $other)
    {
        return $this->customerId === $other->customerId;
    }

}