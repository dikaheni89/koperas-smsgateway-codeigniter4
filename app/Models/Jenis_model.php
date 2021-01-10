<?php namespace App\Models;

use CodeIgniter\Model;

/**
* 
*/
class Jenis_model extends Model
{
	protected $table = 'tbl_jenis';
    protected $primaryKey   = 'idjenis';
    protected $allowedFields = ['nm_simpanan']; 
    protected $useSoftDeletes = false;
    protected $useTimestamps = false;
    protected $deletedField  = 'deleted_at';

    public function insertJenis($data)
    {
        $query = $this->table($this->table)->insert($data);
        return $query ? true : false;
    }

    function updateJenis($id,$data)
    {
        $query = $this->table($this->table)->set($data)->where('idjenis',$id)->update();
        return $query ? true : false;
    }

    function deletedJenis($id)
    {
        $query = $this->table($this->table)
                ->where('idjenis',$id)
                ->delete();
        return $query ? true : false;
    }

    public function getcomboJenis()
    {
        $query=$this->table($this->table)
                ->get();
        return $query->getResultArray();
    }

    function getjenisbyid($id)
    {
         $query = $this->table($this->table)
                ->where('idjenis',$id)
                ->get();
        return $query->getRowArray();
    }

    function countAll()
    {   
        $query = $this->table($this->table)
                ->where('deleted_at', null)
                ->countAllResults();
        return $query;
    }

    function alljenis($limit,$start,$col,$dir)
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

    function jenis_search($limit,$start,$search,$col,$dir)
    {

        $query = $this->table($this->table)
                ->where('deleted_at', null)
                ->like('nm_simpanan',$search)
                ->limit($limit,$start)
                ->orderby($col,$dir)
                ->countAll();
        if ($query > 0){
             $query = $this->table($this->table)
                    ->where('deleted_at', null)
                    ->like('nm_simpanan',$search)
                    ->limit($limit,$start)
                    ->orderby($col,$dir)
                    ->get()
                    ->getResult();
        }else{
            $query = array();
        }
        return $query;
    }

    function jenis_search_count($search)
    {
       $query = $this->table($this->table)
                ->where('deleted_at', null)
                ->like('nm_simpanan',$search);
        return $query->countAllResults();
    } 

}