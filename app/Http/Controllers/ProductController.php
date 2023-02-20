<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ProductController extends Controller
{
    
    public function index(Request $request)
{
    $query = Product::query();

    if ($request->has('name')) {
        $query->where('name', 'like', '%'.$request->input('name').'%');
    }

    if ($request->has('added_by')) {
        $query->where('added_by', $request->input('added_by'));
    }

    $products = $query->with('addedBy')->get();

    // $name = request('name');
    // $user = request('user');
    // $sort = request('sort', 'name');

    // $products = Product::when($name, function ($query, $name) {
    //         return $query->where('name', 'like', '%'.$name.'%');
    //     })
    //     ->when($user, function ($query, $user) {
    //         return $query->whereHas('user', function ($query) use ($user) {
    //             return $query->where('name', 'like', '%'.$user.'%');
    //         });
    //     })
    //     ->orderBy($sort)
    //     ->with('user')
    //     ->paginate();

    return response()->json($products);




    
}

public function show(Product $product)
{
    $product->load('addedBy');

    return response()->json($product);
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'price' => 'required',
        'status' => 'required',
        'type' => 'required',
    ]);

    $product = new Product();
    $product->name = $request->input('name');
    $product->price = $request->input('price');
    $product->status = $request->input('status');
    $product->type = $request->input('type');
    $product->added_by = auth()->id();
    $product->save();

    // send email to person who added the product
    Mail::to(auth()->user()->email)->send(new ProductAdded($product));

    $product->user_id = Auth::user()->id;
    $product->save();

    Mail::to(Auth::user()->email)
        ->send(new ProductCreated($product, Auth::user()));


    if ($product->wasChanged()) {
        $history = new ProductHistory;
        $history->product_id = $product->id;
        $history->field = 'status'; // update the field name as needed
        $history->old_value = $product->getOriginal('status');
        $history->new_value = $product->status;
        $history->user_id = Auth::id();
        $history->save();
    }
    

    return response()->json($product, 201);
}

public function update(Request $request, Product $product)
{
    $request->validate([
        'name' => 'required',
        'price' => 'required',
        'status' => 'required',
        'type' => 'required',
    ]);

    $product->name = $request->input('name');
    $product->price = $request->input('price');
    $product->status = $request->input('status');
    $product->type = $request->input('type');
    $product->save();

    if ($product->wasChanged()) {
        $history = new ProductHistory;
        $history->product_id = $product->id;
        $history->field = 'status'; // update the field name as needed
        $history->old_value = $product->getOriginal('status');
        $history->new_value = $product->status;
        $history->user_id = Auth::id();
        $history->save();
    }
    

    return response()->json($product);
}

    public function destroy(Product $product)
    {
    $product->delete();

    if ($product->wasChanged()) {
        $history = new ProductHistory;
        $history->product_id = $product->id;
        $history->field = 'status'; // update the field name as needed
        $history->old_value = $product->getOriginal('status');
        $history->new_value = $product->status;
        $history->user_id = Auth::id();
        $history->save();
    }
    

    return response()->json(null, 204);

    }


}
