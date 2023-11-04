<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\Table;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['store']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::latest()->with('table')->paginate(5);
        return response()->json(['orders' => $orders]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $tableNumber = $request->input('table_number');
            $table = Table::where('table_number', $tableNumber)->first();
    
            if(!$table) {
                return response()->json(['error' => 'Table not found'], 404);
            }
    
            $table->status = 0;
            $table->save();
    
            $orderData = [
                'name' => $request->input('name'),
                'date' => $request->input('date'),
                'table_number' => $tableNumber,
                'items' => $request->input('carts'),
                'options' => $request->input('options'),
                'total_price' => $request->input('totalPrice'),
                'completion_status' => $request->input('status')
            ];
    
            $order = $table->orders()->create($orderData);

            foreach ($order->items as $item) {
                $menu = Menu::where('id', $item['id'])->first();
                $menu->stock -= $item['menuQty'];
                $menu->save();
            }
    
            return response()->json(['message' => 'Order created successfully', 'order' => $order]);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Change completion_status
     */
    public function completeOrder(Order $order)
    {
        $order->update(['completion_status' => 1]);

        return response()->json(['message' => 'Order completed successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return response()->json(['message' => 'Order deleted successfuly']);
    }

    /**
     * Get all metrics from orders.
     */
    public function metrics()
    {
        //TodayRevenue
        $today = Carbon::now();
        $order = Order::whereDate('created_at', '=', $today)->get();
        $todayRevenue = $order->sum('total_price');
        
        //TotalRevenue
        $orders = Order::all();
        $totalRevenue = $orders->sum('total_price');

        //ProductSold
        $totalProductSold = 0;
        foreach ($orders as $order) {
            foreach ($order['items'] as $item) {
                $totalProductSold += $item['menuQty'];
            }
        }

        return response()->json([
            'todayRevenue' => $todayRevenue,
            'totalRevenue' => $totalRevenue,
            'productSold' => $totalProductSold
        ]);
    }

    /**
     * Get weekly orders.
     */
    public function getWeeklyOrders()
    {

        $startOfWeek = date('Y-m-d', strtotime('monday this week'));
        $endOfWeek = date('Y-m-d', strtotime('sunday this week'));

        $weeklyIncome = Order::select(
            DB::raw('DATE(created_at) as order_date'),
            DB::raw('SUM(total_price) as total_income')
        )
        ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
        ->groupBy('order_date')
        ->get()
        ->pluck('total_income', 'order_date')
        ->toArray();
        
        $datesInWeek = [];
        $currentDate = new DateTime($startOfWeek);
        $endOfWeekObj = new DateTime($endOfWeek);
        
        while ($currentDate <= $endOfWeekObj) {
            $dateString = $currentDate->format('Y-m-d');
            $datesInWeek[$dateString] = 0;
            $currentDate->modify('+1 day');
        }
        
        $weeklyIncomes = array_merge($datesInWeek, $weeklyIncome);


        return response()->json([
            'weeklyIncome' => $weeklyIncomes
        ]);
    }

    /**
     * Get total orders by category.
     */
    public function getOrderByCategory()
    {
        $orders = Order::latest()->get();
        
        $byCategory = [];

        foreach ($orders as $order) {
            foreach ($order['items'] as $item) {
                $category = $item['category'];
                $menuQty = $item['menuQty'];
                if (isset($byCategory[$category])) {
                    $byCategory[$category] += $menuQty;
                } else {
                    $byCategory[$category] = $menuQty;
                }
            }
        }

        return response()->json(['orderByCategory' => $byCategory]);
    }
}
