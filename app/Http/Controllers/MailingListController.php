<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class MailingListController extends Controller
{

    public static function subscribe($email) {

        $client = new Client(['base_uri' => env('MAILCHIMP_URL')]);

        $client->request('POST', 'members/', [
            'auth' => ['mytentsite', env('MAILCHIMP_API_KEY')],
            'json' => [
                'email_address' => $email,
                'status' => 'subscribed'
            ]
        ]);
    }
}
