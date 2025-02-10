<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navber;
use App\Models\Product;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Title;
use Livewire\Component;


#[Title('Products Details - My ecommers')]

class Productdetailspage extends Component

{
    use LivewireAlert;

    public $name;

    public $quantity = 1;
    
    public function mount($name){
        $this->name = $name;
    }

    public function increaseQty(){
        $this->quantity++;
    }

    public function decreaseQty(){
       if($this->quantity>1) {
        $this->quantity--;

       }
    }

    //add product to cart methode

    public function  addToCart($product_id){
        $total_count = CartManagement::addItemToCartWithQty($product_id,$this->quantity);
        $this->dispatch('update-cart-count', total_count: $total_count)->to(Navber::class);
    
        $this->alert('success', 'product added to the cart successfully!', [
            'position' => 'bottom',
            'timer' => 3000,
            'toast' => true,
           ]);
    
    
       }


    public function render()
    {
        return view('livewire.productdetailspage',[

            'product' => Product::where('name',$this->name)->firstOrFail()

        ]);
    }
}
