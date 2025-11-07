<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Student;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function summary(){
        $totalProducts = Product::count();
        $totalStudents = Student::count();
        $totalOrders = Order::count();

        return response()->json([
            'products' => $totalProducts,
            'students' => $totalStudents,
            'orders' => $totalOrders,
        ]);
    }
}
