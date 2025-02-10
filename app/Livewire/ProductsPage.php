<?php

namespace App\Livewire;

use App\Filament\Resources\BrandResource;
use App\Helpers\CartManagement;
use App\Livewire\Partials\Navber;
use App\Models\brand;
use App\Models\Category;
use App\Models\Product;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Products - My ecommers')]
class ProductsPage extends Component
{
    use LivewireAlert;
    use WithPagination;

    #[Url]

    public $selected_categories = [];

    #[url]
    public $selected_brands =[];

    #[url]
    public $featured;

    #[url]
    public $on_sale;

    #[url] 
    public $price_range=100000;

    #[url]
    public $sort = 'latest';

    //add product to cart methode

   public function  addToCart($product_id){
    $total_count = CartManagement::addItemToCart($product_id);
    $this->dispatch('update-cart-count', total_count: $total_count)->to(Navber::class);

    $this->alert('success', 'product added to the cart successfully!', [
        'position' => 'bottom',
        'timer' => 3000,
        'toast' => true,
       ]);


   }


    public function render()
    {
        $productQuery = Product::query()->where('is_active', 1);


        if(!empty($this->selected_categories)){

            $productQuery->whereIn('category_id', $this->selected_categories);
        }


        if(!empty($this->selected_brands)){

            $productQuery->whereIn('brand_id', $this->selected_brands);
        }



        if($this->featured){

            $productQuery->where('is_featured',1);
        }


        
        if($this->on_sale){

            $productQuery->where('on_sale',1);
        }



        
        if($this->price_range){

            $productQuery->whereBetween('price',[0, $this->price_range]);
        }

     
      
        if($this->sort == 'latest'){

            $productQuery->latest();
        }




        if($this->sort == 'price'){

            $productQuery->orderBy('price');
        }




        return view('livewire.products-page', [

            'products' => $productQuery->paginate('9'),
            
            'brands' =>Brand::where('is_active',1)->get([ 'id','name','title'  ]),

            'categories' =>Category::where('is_active',1)->get([ 'id','name','title'  ])
        ]);
    }
}
