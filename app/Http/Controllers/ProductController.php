<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use File;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return view('admin.dashboard', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.product.create');
    }

    /**
     * Store a newly created resource in storage.
     */
 public function store(ProductStoreRequest $request){
        // dd($request->all());
        $product = new Product();
        if($request->hasFile('image')) {
            $image = $request->file('image');
            $fileName = $image->store('', 'public');
            $filePath = 'uploads/' . $fileName;
            $product->image = $filePath;
        }
        $product->name = $request->name;
        $product->price = $request->price;
        $product->short_description = $request->short_description;
        $product->qty = $request->qty;
        $product->sku = $request->sku;
        $product->description = $request->description;
        $product->save();

        if($request->has('colors') && $request->filled('colors')) {
            foreach($request->colors as $color) {
                ProductColor::create([
                    'product_id' => $product->id,
                    'name' => $color
                ]);
            }
        }

        if($request->hasFile('images')) {
            foreach($request->file('images') as $image) {

                $fileName = $image->store('', 'public');
                $filePath = 'uploads/' . $fileName;

                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $filePath
                ]);
            }
        }
        notyf('The Product is Created Successfully.');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return "<h1>Show PAGE</h1>";
    }

    /**
     * Show the form for editing the specified resource.
     */

    /**
     * ************ *
     * Eager Load
     * Eager loading is the process of getting all the required data from the database in one go up front to avoid multiple unnecessary trips to the database.
     */
    public function edit(string $id)
    {

       $product = Product::with(['colors', 'images'])->findOrFail($id);
       $colors = $product->colors->pluck('name')->toArray();

        return view('admin.product.edit', compact('product', 'colors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdateRequest $request, string $id)
    {

        $product =  Product::findOrFail($id);
        if($request->hasFile('image')) {
            File::delete(public_path($product->image));
            $image = $request->file('image');
            $fileName = $image->store('', 'public');
            $filePath = 'uploads/' . $fileName;
            $product->image = $filePath;
        }
        $product->name = $request->name;
        $product->price = $request->price;
        $product->short_description = $request->short_description;
        $product->qty = $request->qty;
        $product->sku = $request->sku;
        $product->description = $request->description;
        $product->save();

        // insert colors
        if($request->has('colors') && $request->filled('colors')) {
            foreach ($product->colors as $color) {
                $color->delete();
            }
            foreach($request->colors as $color) {
                ProductColor::create([
                    'product_id' => $product->id,
                    'name' => $color
                ]);
            }
        }

        // insert imgs
        if($request->hasFile('images')) {
            // delete the old ones to add new ones (delete from the storage then from DB)
            foreach ($product->images as $image) {
                File::delete(public_path($image->path));
                /// $image->delete(); // we will leverage the relationship fpr deletion
            }

            // we will leverage the relationship fpr deletion
            $product->images()->delete();

            foreach($request->file('images') as $image) {
                // storing images locally
                // $image->store('uploads', 'public');
                $fileName = $image->store('', 'public'); // as we hardcoded the uploads folder in public disk config we needn't to assign it one more time.
                $filePath = 'uploads/' . $fileName;

                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $filePath
                ]);
            }
        }

        notyf('The Product is Updated Successfully.');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

       $product = Product::findOrFail($id);
       File::delete(public_path($product->image));
       $product->colors()->delete();
       foreach($product->images as $image) {
        File::delete(public_path($image->path));
       }
       $product->images()->delete();
       $product->delete();

       notyf('The Product is Deleted Successfully.');
       return redirect()->back();
    }
}
