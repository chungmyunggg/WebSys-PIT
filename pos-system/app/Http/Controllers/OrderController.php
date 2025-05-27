<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // List orders with pagination
    public function index()
    {
        $orders = Order::with('orderItems.product')->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    // Show form to create a new order
    public function create()
    {
        $products = Product::where('quantity', '>', 0)->get();
        return view('orders.create', compact('products'));
    }

    // Store a new order
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string',
            'products' => 'required|array',
            'products.*' => 'integer|exists:products,id',
            'quantities' => 'required|array',
            'quantities.*' => 'integer|min:1',
            'status' => 'required|string|in:pending,completed,cancelled',
        ]);

        $products = $request->input('products');
        $quantities = $request->input('quantities');
        $status = $request->input('status');

        DB::beginTransaction();

        try {
            $totalPrice = 0;
            $orderItems = [];

            foreach ($products as $index => $productId) {
                $product = Product::findOrFail($productId);
                $qty = $quantities[$index];

                if ($product->quantity < $qty) {
                    return redirect()->back()
                        ->withErrors(['quantity' => 'Not enough stock for ' . $product->name])
                        ->withInput();
                }

                $price = $product->price * $qty;
                $totalPrice += $price;

                $product->quantity -= $qty;
                $product->save();

                $orderItems[] = [
                    'product_id' => $productId,
                    'quantity' => $qty,
                    'price' => $product->price,
                ];
            }

            $order = Order::create([
                'customer_name' => $request->customer_name,
                'total_price' => $totalPrice,
                'status' => $status,
            ]);

            foreach ($orderItems as $item) {
                $order->orderItems()->create($item);
            }

            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Order created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Error creating order: ' . $e->getMessage()])
                ->withInput();
        }
    }

    // Show form to edit an existing order
    public function edit(Order $order)
    {
        $order->load('orderItems.product');
        $products = Product::where('quantity', '>', 0)
            ->orWhereIn('id', $order->orderItems->pluck('product_id'))
            ->get();

        return view('orders.edit', compact('order', 'products'));
    }

    // Update an existing order
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'customer_name' => 'required|string',
            'products' => 'required|array',
            'products.*' => 'integer|exists:products,id',
            'quantities' => 'required|array',
            'quantities.*' => 'integer|min:1',
            'status' => 'required|string|in:pending,completed,cancelled',
        ]);

        $products = $request->input('products');
        $quantities = $request->input('quantities');
        $status = $request->input('status');

        DB::beginTransaction();

        try {
            foreach ($order->orderItems as $oldItem) {
                $product = Product::findOrFail($oldItem->product_id);
                $product->quantity += $oldItem->quantity;
                $product->save();
            }

            $order->orderItems()->delete();

            $totalPrice = 0;
            $orderItems = [];

            foreach ($products as $index => $productId) {
                $product = Product::findOrFail($productId);
                $qty = $quantities[$index];

                if ($product->quantity < $qty) {
                    return redirect()->back()
                        ->withErrors(['quantity' => 'Not enough stock for ' . $product->name])
                        ->withInput();
                }

                $price = $product->price * $qty;
                $totalPrice += $price;

                $product->quantity -= $qty;
                $product->save();

                $orderItems[] = [
                    'product_id' => $productId,
                    'quantity' => $qty,
                    'price' => $product->price,
                ];
            }

            $order->update([
                'customer_name' => $request->customer_name,
                'total_price' => $totalPrice,
                'status' => $status,
            ]);

            foreach ($orderItems as $item) {
                $order->orderItems()->create($item);
            }

            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Error updating order: ' . $e->getMessage()])
                ->withInput();
        }
    }

    // Delete an order and restore stock
    public function destroy(Order $order)
    {
        DB::beginTransaction();

        try {
            foreach ($order->orderItems as $item) {
                $product = Product::findOrFail($item->product_id);
                $product->quantity += $item->quantity;
                $product->save();
            }

            $order->orderItems()->delete();
            $order->delete();

            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('orders.index')
                ->withErrors(['error' => 'Error deleting order: ' . $e->getMessage()]);
        }
    }

    // ✅ NEW: Show a single order details
    public function show(Order $order)
    {
        $order->load('orderItems.product');
        return view('orders.show', compact('order'));
    }
}
