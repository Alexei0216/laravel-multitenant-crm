<?php

namespace App\Http\Controllers;

class ClientsController extends Controller
{
    public function index()
    {
        return inertia('Clients/Index');
    }
}