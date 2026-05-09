<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * AdminController
 * 
 * Handles admin dashboard and main admin operations
 * 
 * @package App\Http\Controllers
 */
class AdminController extends Controller
{
    /**
     * Show admin dashboard
     * 
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // TODO: Fetch dashboard statistics
        // - Total products count
        // - Total articles count
        // - Pending feedback count
        // - Total users count
        // - Recent products list
        // - Recent articles list
        
        return view('admin.dashboard');
    }
}
