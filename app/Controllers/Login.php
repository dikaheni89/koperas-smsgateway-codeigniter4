<?php namespace App\Controllers;
 
use App\Models\Admin_model;

class Login extends BaseController
{
	public function __construct()
    {
    	$this->session = \Config\Services::session();
        $this->admin = new Admin_model();
        $this->session->start();
    }


    public function index()
    {
    	$data=[
        		'title'	=> 'Login System Koperasi SMA Mathlaul Anwar'
    	];
         //load the login page
         echo view('login/login',$data); 
    }

}