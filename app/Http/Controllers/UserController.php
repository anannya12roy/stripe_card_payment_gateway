<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Stripe;
use Session;

class UserController extends Controller
{
    //
    public function call(Request $request) {
        \Stripe\Stripe::setApiKey('sk_test_51HrmZ3A2VCO2Q31fEQzOxearfiXpQgXCtQS3ZkEkScvnYewlBDOMxdip3Kfpk4BR4iYV5qPGWzh11pE0XdgadGUA00o744L7ry');
        $customer = \Stripe\Customer::create(array(
            'name' => 'test',
            'description' => 'test description',
            'email' => 'email@gmail.com',
            'source' => $request->input('stripeToken'),
            "address" => ["city" => "San Francisco", "country" => "US", "line1" => "510 Townsend St", "postal_code" => "98140", "state" => "CA"]

        ));
        try {
            \Stripe\Charge::create ( array (
                "amount" => 300 * 100,
                "currency" => "usd",
                "customer" =>  $customer["id"],
                "description" => "Test payment."
            ) );
            Session::flash ( 'success-message', 'Payment done successfully !' );
            return view ( 'cardForm' );
        } catch ( \Stripe\Error\Card $e ) {
            Session::flash ( 'fail-message', $e->get_message() );
            return view ( 'cardForm' );
        }
    }
}
