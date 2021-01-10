<?php namespace App\Models;

use CodeIgniter\Model;

/**
* 
*/
class Pinjaman_model extends Model
{
	protected $table = 'tbl_pinjaman';
    protected $primaryKey   = 'idpinjam';
    protected $allowedFields = ['idanggota', 'tgl_pinjam', 'tgl_tempo', 'nominal_pinjam', 'jml_angsuran', 'jml_bunga', 'nominal_angsuran', 'pembayaran_ke', 'acc', 'status_pembayaran']; 
    protected $useSoftDeletes = false;
    protected $useTimestamps = false;
    protected $deletedField  = 'deleted_at';

    public function insertPinjaman($data)
    {
        $query = $this->table($this->table)->insert($data);
        return $query ? true : false;
    }

    public function updatePinjaman($id,$data)
    {
        $query = $this->table($this->table)->set($data)->where('idpinjam',$id)->update();
        return $query ? true : false;
    }

    public function deletedPinjaman($id)
    {
        $query = $this->table($this->table)
                ->where('idpinjam',$id)
                ->delete();
        return $query ? true : false;
    }

    function getpinjambyid($id)
    {
         $query = $this->table($this->table)
                ->where('deleted_at', null)
                ->where('tbl_pinjaman.idanggota',$id)
                ->get();
        return $query->getRowArray();
    }

    function countAll()
    {   
        $query = $this->table($this->table)
                ->where('tbl_pinjaman.deleted_at', null)
                ->countAllResults();
        return $query;
    }

    function allpinjam($id,$limit,$start,$col,$dir)
    {   
        $result = $this->table($this->table)
                ->select('tbl_pinjaman .*, tbl_anggota.nm_anggota')
                ->join('tbl_anggota', 'tbl_anggota.idanggota=tbl_pinjaman.idanggota')
                ->where('tbl_pinjaman.deleted_at', null)
                ->where('tbl_pinjaman.idanggota', $id)
                ->limit($limit,$start)
                ->orderby($col,$dir)
                ->countAll();
        if ($result > 0){
            $result = $this->table($this->table)
                ->select('tbl_pinjaman .*, tbl_anggota.nm_anggota')
                ->join('tbl_anggota', 'tbl_anggota.idanggota=tbl_pinjaman.idanggota')
                ->where('tbl_pinjaman.deleted_at', null)
                ->where('tbl_pinjaman.idanggota', $id)
                ->limit($limit,$start)
                ->orderby($col,$dir)
                ->get()
                ->getResult();
        }else{
            $result = array();
        }
        
        return $result;
    }

    function pinjam_search($id,$limit,$start,$search,$col,$dir)
    {
        $query = $this->table($this->table)
                ->select('tbl_pinjaman .*, tbl_anggota.nm_anggota')
                ->join('tbl_anggota', 'tbl_anggota.idanggota=tbl_pinjaman.idanggota')
                ->where('tbl_pinjaman.deleted_at', null)
                ->where('tbl_pinjaman.idanggota', $id)
                ->like('tbl_anggota.nm_anggota',$search)
                ->limit($limit,$start)
                ->orderby($col,$dir)
                ->countAll();
        if ($query > 0){
             $query = $this->table($this->table)
                    ->select('tbl_pinjaman .*, tbl_anggota.nm_anggota')
                    ->join('tbl_anggota', 'tbl_anggota.idanggota=tbl_pinjaman.idanggota')
                    ->where('tbl_pinjaman.deleted_at', null)
                    ->where('tbl_pinjaman.idanggota', $id)
                    ->like('tbl_anggota.nm_anggota',$search)
                    ->limit($limit,$start)
                    ->orderby($col,$dir)
                    ->get()
                    ->getResult();
        }else{
            $query = array();
        }
        return $query;
    }

    function pinjam_search_count($id,$search)
    {
       $query = $this->table($this->table)
                ->select('tbl_pinjaman .*, tbl_anggota.nm_anggota')
                ->join('tbl_anggota', 'tbl_anggota.idanggota=tbl_pinjaman.idanggota')
                ->where('tbl_pinjaman.deleted_at', null)
                ->where('tbl_pinjaman.idanggota', $id)
                ->like('tbl_anggota.nm_anggota',$search);
        return $query->countAllResults();
    } 

}