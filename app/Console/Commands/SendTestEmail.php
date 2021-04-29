<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailEntry;

class SendTestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:testmail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Mail::to( 'gapafex637@gridmire.com' )->send(new EmailEntry( 10, 10, 'TEST1 TEST1', 10, 10, 10 ));

        Mail::mailer('smtp')->to('gapafex637@gridmire.com')->send(new EmailEntry(10, 10, 'TEST2 TEST2', 10, 10, 10));
    }
}
