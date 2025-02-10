<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\brand;
use App\Models\category;
use App\Models\Product;

#[Title('Home page - My ecommers')]

class HomePage extends Component
{

    public function render()
    {
        $brands = brand::where('is_active',1)->get();

        $categories = Category::where('is_active', 1)->get();

        $products = Product::where('is_active', 1)->get();



        return view('livewire.home-page',[

            'brands' =>$brands,

            'categories' =>$categories,

            'products' => $products

        ]);
    }
}
