<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address; 
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Order Detail')]
class MyOrderDetailPage extends Component
{
    public $order_id;

    public function mount($order_id)
    {
        $this->order_id = $order_id;
    }

    public function render()
    {
        // Load order items with the product relationship
        $order_items = OrderItem::with('product')->where('order_id', $this->order_id)->get();
        // Load the address for the order
        $address = Address::where('order_id', $this->order_id)->first(); 
        // Load the order itself
        $order = Order::where('id', $this->order_id)->first();

        return view('livewire.my-order-detail-page', [
            'order_items' => $order_items,
            'address' => $address,
            'order' => $order
        ]);
    }
}
