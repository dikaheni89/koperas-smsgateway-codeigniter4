<?php namespace App\Models;

use CodeIgniter\Model;

/**
* 
*/
class Users_model extends Model
{
	protected $table = 'tbl_users';
    protected $primaryKey   = '_id';
    protected $allowedFields = ['user', 'email', 'full_name', 'phone', 'password','is_active','is_level','photo']; 
    protected $useSoftDeletes = false;
    protected $useTimestamps = false;
    protected $deletedField  = 'deleted_at';

    public function insertUsers($data)
    {
        $query = $this->table($this->table)->insert($data);
        return $query ? true : false;
    }

    function updateUsers($id,$data)
    {
        $query = $this->table($this->table)->set($data)->where('_id',$id)->update();
        return $query ? true : false;
    }

    function deleteduser($id)
    {
        $query = $this->table($this->table)
                ->where('_id',$id)
                ->delete();
        return $query ? true : false;
    }

    function resetpassword($id,$data)
    {
        $query = $this->table($this->table)->set($data)->where('_id',$id)->update();
        return $query ? true : false;
    }
    public function getusersbyemail()
    {
        $q = isset($_POST['q']) ? $_POST['q'] : '';
         $query=$this->table($this->table)
                ->where('deleted_at', null)
                ->Where('email',$q)
                ->countAllResults();
        return $query;
    }

    function countAll()
    {   
        $query = $this->table($this->table)
                ->where('deleted_at', null)
                ->countAllResults();
        return $query;
    }

    function allusers($limit,$start,$col,$dir)
    {   
        $result = $this->table($this->table)
                ->where('deleted_at', null)
                ->limit($limit,$start)
                ->orderby($col,$dir)
                ->countAll();
        if ($result > 0){
            $result = $this->table($this->table)
                ->where('deleted_at', null)
                ->limit($limit,$start)
                ->orderby($col,$dir)
                ->get()
                ->getResult();
        }else{
            $result = array();
        }
        
        return $result;
    }

    function users_search($limit,$start,$search,$col,$dir)
    {

        $query = $this->table($this->table)
                ->where('deleted_at', null)
                ->like('user',$search)
                ->orlike('full_name',$search)
                ->orlike('email',$search)
                ->orlike('phone',$search)
                ->limit($limit,$start)
                ->orderby($col,$dir)
                ->countAll();
        if ($query > 0){
             $query = $this->table($this->table)
                    ->where('deleted_at', null)
                    ->like('user',$search)
                    ->orlike('full_name',$search)
                    ->orlike('email',$search)
                    ->orlike('phone',$search)
                    ->limit($limit,$start)
                    ->orderby($col,$dir)
                    ->get()
                    ->getResult();
        }else{
            $query = array();
        }
        return $query;
    }

    function users_search_count($search)
    {
       $query = $this->table($this->table)
                ->where('deleted_at', null)
                ->like('user',$search)
                ->orlike('full_name',$search)
                ->orlike('email',$search)
                ->orlike('phone',$search);
        return $query->countAllResults();
    } 

}