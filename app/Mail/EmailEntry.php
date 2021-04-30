<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailEntry extends Mailable
{
    use Queueable, SerializesModels;

	public $order_cost;

	public $entry_multiplier;

	public $customer_name;

	public $entry_points;

	public $total_entry_points;

	public $order_number;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order_cost, $entry_multiplier, $customer_name, $entry_points, $total_entry_points, $order_number)
    {
        $this->order_cost = $order_cost;
		$this->entry_multiplier = $entry_multiplier;
		$this->customer_name = $customer_name;
		$this->entry_points = $entry_points;
		$this->total_entry_points = $total_entry_points;
		$this->order_number = $order_number;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->subject('Savage Card Club – Your Entries Are In!')->view('email.template')->with(array(
			'customer_name' => $this->customer_name,
			'order_cost' => $this->order_cost,
			'entry_multiplier' => $this->entry_multiplier,
			'entry_points' => $this->entry_points,
			'total_entry_points' => $this->total_entry_points,
			'order_number' => $this->order_number
		));
    }
}