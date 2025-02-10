<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class SuccessPage extends Component
{
    public $session_id;

    #[\Livewire\Attributes\Title('Success My EcoMMers')]
    public function mount($session_id = null)
    {
        $this->session_id = $session_id;
    }

    public function render()
    {
        // Retrieve the latest order for the authenticated user
        $latest_order = Order::with('address')
            ->where('user_id', auth()->user()->id)
            ->latest()
            ->first();

        if ($this->session_id) {
            // Set the Stripe API key
            Stripe::setApiKey(env('STRIPE_SECRET'));

            try {
                // Retrieve the session information from Stripe
                $session_info = Session::retrieve($this->session_id);

                // Check the payment status and update the order accordingly
                if ($session_info->payment_status != 'paid') {
                    $latest_order->payment_status = 'failed';
                    $latest_order->save();

                    return redirect()->route('cancel');
                } elseif ($session_info->payment_status == 'paid') {
                    $latest_order->payment_status = 'paid';
                    $latest_order->save();
                }
            } catch (\Exception $e) {
                // Handle any exceptions that occur during the Stripe session retrieval
                return redirect()->route('error')->with('error', $e->getMessage());
            }
        }

        // Render the success page view with the latest order
        return view('livewire.success-page', [
            'order' => $latest_order,
        ]);
    }
}
