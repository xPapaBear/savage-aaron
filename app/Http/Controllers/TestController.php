<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailEntry;

class TestController extends Controller
{
    /**
     * Get Top 5 Customer based on Entry Points
     * EP = Entry Points
     */
    public function testTopCustomerEP( Request $request ) {
        Mail::to( 'markgerald08.24@gmail.com' )->send(new EmailEntry( 10, 10, 'TEST1 TEST1', 10, 10, 10 ));

        Mail::mailer('smtp')->to('markgerald08.24@gmail.com')->send(new EmailEntry(10, 10, 'TEST2 TEST2', 10, 10, 10));
    }
}
