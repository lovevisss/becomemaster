<?php
namespace App\Acme\Billing;

use Stripe\Stripe;
use Config;

class StripeBilling implements BillingInterface
{
    public function __construct()
    {
        // Set Stripe API key
        Stripe::setApiKey(Config('services.stripe.secret'));
    }

    public function charge(array $data)
    {

        try {
            // save the customer
            $customer = \Stripe\Customer::create([
                'email' => $data['description'],
                'source' => $data['token'],
            ]);
            // Charge via Stripe
            \Stripe\Charge::create([
                'amount' => $data['amount'],
                'currency' => 'usd',
                'customer' => $customer->id,
                // 'source' => $data['token'],
                // 'description' => $data['description'],
            ]);
            return $customer->id;
        }catch (Stripe_InvalidRequestError $e) {
            //throw $th;
            throw new Exception($e->getMessage());
        } 
        
        catch (Stripe_CardError $e) {
            //throw $th;
            throw new Exception($e->getMessage());
        }
        catch (Exception $e) {
            //throw $th;
            throw new Exception($e->getMessage());
        }
        
    }
}