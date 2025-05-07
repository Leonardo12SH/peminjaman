<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {     
        // $whr = "";
        // if(!empty($_GET['id'])){
        //     $id = (int)$_GET['id'];
        //     $whr = " AND id = $id";
        // }

        // $sql = "SELECT * FROM t_user WHERE aktif = 1 $whr";
        
        return view('admin/home');
    }
}