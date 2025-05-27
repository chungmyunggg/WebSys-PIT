<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'name');
        $direction = $request->get('direction', 'asc');

        $allowedSorts = ['name', 'quantity', 'price'];
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'name';
        }

        // Get paginated products sorted by user preference
        $products = Product::orderBy($sort, $direction)->paginate(10);

        // Total number of products (optional)
        $totalProducts = Product::count();

        // Low stock products - threshold of 5 or less
        $lowStockProducts = Product::where('quantity', '<=', 5)->get();

        // Top selling products by total quantity sold
        $topSellingProducts = Product::select(
                'products.id',
                'products.name',
                DB::raw('SUM(order_items.quantity) as total_sold')
            )
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        return view('products.index', compact(
            'products', 
            'totalProducts', 
            'lowStockProducts', 
            'topSellingProducts'
        ));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        $product->update($request->all());

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
