<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\AIService;

class ChatbotController extends Controller
{
    public function chat(Request $request, AIService $ai)
    {   
        $userMessage = $request->input('message');        
        if(str_contains(strtolower($userMessage), 'order')){
            // 👉 Fetch dynamic data (important)
            $orders = \App\Models\Order::limit(5)->pluck('status','order_number')->toArray();                        
            $context = "Available orders:\n";
            foreach ($orders as $order_number => $status ) {
                $context .= "$order_number => $status \n";
            }
        }else if(str_contains(strtolower($userMessage), 'product')){
            // 👉 Fetch dynamic data (important)
            $products = \App\Models\Product::limit(5)->pluck('name')->toArray(); 
            $context = "Available products:\n";
            foreach ($products as $name ) {
                $context .= "$name\n";
            }
        }else{
            $context = "Thanks to visit.:\n";
        }           
        

        $prompt = "
            You are a helpful e-commerce assistant.

            Rules:
            - Be polite and short
            - Use only given product data
            - If not sure, say 'Please contact support'

            $context

            User: $userMessage
            ";        
        $result = $ai->chat($prompt);
        return response()->json([
            'data' => $result,           
        ]);        
    }
}
