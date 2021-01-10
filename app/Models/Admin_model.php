<?php namespace App\Models;

use CodeIgniter\Model;

/**
* 
*/
class Admin_model extends Model
{
	protected $table = 'tbl_users';
    protected $primeryKey ='_id';


    public function chekLogin($user)
    {
        $result = $this->table($this->table)
                ->Where('deleted_at', null)
                ->where('is_active', 0)
                ->orwhere('email', $user)
                ->get();
        return $result;
    }
}