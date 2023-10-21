<?php 
namespace App\Acme\Billing;

interface BillingInterface
{
    public function charge(array $data);
}