<?php namespace App\Controllers;

use App\Models\Menu_model;

class Menu extends BaseController
{
	public function __construct()
    {
        $this->menus = new Menu_model();
        $this->session = \Config\Services::session();
    }

	public function getmenus()
	{
		$sess_id = $this->session->get('user');
		$level = $sess_id['is_level'];
		$id=$this->session->has('uid');
        $menu=$this->menus->getMenus($level);
        $data=array();
        $induks=array();
        foreach ($menu->getResultArray() as $row) {
        	$submenu=$this->menus->getSubMenus((int)$level, $row['_id']);
        	if (count($submenu->getResultArray()) > 0){
        		$subm=array();
        		foreach ($submenu->getResultArray() as $sub) {
	        		$subm[]=array(
	        				'uri'	=> $sub['uri'],
	        				'title'	=> $sub['title'],
	        				'icon'	=> $sub['icon']
	        			);
	        	}
        		$data[]=array(
        			'id'		=> $row['_id'],
	        		'iconmain'	=> $row['icon'],
	        		'titlemain'	=> $row['title'],
	        		'numsub'	=> count($submenu->getResultArray()),
	        		'icon'		=> $row['icon'],
	        		'submenu'	=> $subm
        		);
        	}else{
        		$data[]=array(
	        		'iconmain'	=> $row['icon'],
	        		'titlemain'	=> $row['title'],
	        		'uri'		=> $row['uri'],
	        		'numsub'	=> 0
        		);
        	}
        }
		return json_encode($data);
		// var_dump($data);
	}

	public function getmenuspermission()
	{
        $menu=$this->menus->getMenus($sess_id);
        $data=array();
        foreach ($menu->getResultArray() as $row) {
        	$submenu=$this->menus->getSubMenus((int)$sess_id, $row['_id']);
        	if (count($submenu->getResultArray()) > 0){
        		$subm=array();
        		foreach ($submenu->getResultArray() as $sub) {
	        		$subm[]=array(
	        				'uri'	=> $sub['uri'],
	        				'title'	=> $sub['title'],
	        				'icon'	=> $sub['icon']
	        			);
	        	}
        		$data[]=array(
        			'id'		=> $row['_id'],
	        		'iconmain'	=> $row['icon'],
	        		'titlemain'	=> $row['title'],
	        		'numsub'	=> count($submenu->getResultArray()),
	        		'icon'		=> $row['icon'],
	        		'submenu'	=> $subm
        		);
        	}else{
        		$data[]=array(
	        		'iconmain'	=> $row['icon'],
	        		'titlemain'	=> $row['title'],
	        		'uri'		=> $row['uri'],
	        		'numsub'	=> 0
        		);
        	}
        }
		return json_encode($data);
	}

}
