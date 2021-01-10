<?php namespace App\Models;

use CodeIgniter\Model;

/**
* 
*/
class Anggota_model extends Model
{
	protected $table = 'tbl_anggota';
    protected $primaryKey   = 'idanggota';
    protected $allowedFields = ['no_identitas', 'nm_anggota', 'jk', 'alamat', 'no_telp','photo','tgl_masuk','status_anggota']; 
    protected $useSoftDeletes = false;
    protected $useTimestamps = false;
    protected $deletedField  = 'deleted_at';

    public function insertAnggota($data)
    {
        $query = $this->table($this->table)->insert($data);
        return $query ? true : false;
    }

    public function updateAnggota($id,$data)
    {
        $query = $this->table($this->table)->set($data)->where('idanggota',$id)->update();
        return $query ? true : false;
    }

    public function deletedAnggota($id)
    {
        $query = $this->table($this->table)
                ->where('idanggota',$id)
                ->delete();
        return $query ? true : false;
    }

    function getanggotabyid($id)
    {
         $query = $this->table($this->table)
                ->where('deleted_at', null)
                ->where('idanggota',$id)
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

    function allanggota($limit,$start,$col,$dir)
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

    function anggota_search($limit,$start,$search,$col,$dir)
    {

        $query = $this->table($this->table)
                ->where('deleted_at', null)
                ->like('nm_anggota',$search)
                ->orlike('no_identitas',$search)
                ->orlike('no_telp',$search)
                ->limit($limit,$start)
                ->orderby($col,$dir)
                ->countAll();
        if ($query > 0){
             $query = $this->table($this->table)
                    ->where('deleted_at', null)
                    ->like('nm_anggota',$search)
                    ->orlike('no_identitas',$search)
                    ->orlike('no_telp',$search)
                    ->limit($limit,$start)
                    ->orderby($col,$dir)
                    ->get()
                    ->getResult();
        }else{
            $query = array();
        }
        return $query;
    }

    function anggota_search_count($search)
    {
       $query = $this->table($this->table)
                ->where('deleted_at', null)
                ->like('nm_anggota',$search)
                ->orlike('no_identitas',$search)
                ->orlike('no_telp',$search);
        return $query->countAllResults();
    } 

}