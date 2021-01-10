<?php namespace App\Models;

use CodeIgniter\Model;

/**
* 
*/
class Transaksipinjam_model extends Model
{
	protected $table = 'tbl_pinjaman';
    protected $primaryKey   = 'idpinjam';
    protected $allowedFields = ['idanggota', 'tgl_pinjam', 'tgl_tempo', 'nominal_pinjam', 'jml_angsuran', 'jml_bunga', 'nominal_angsuran', 'pembayaran_ke', 'acc', 'status_pembayaran']; 
    protected $useSoftDeletes = false;
    protected $useTimestamps = false;
    protected $deletedField  = 'deleted_at';

    public function insertTransaksipinjam($data)
    {
        $query = $this->table($this->table)->insert($data);
        return $query ? true : false;
    }

    public function updateTransaksipinjam($id,$data)
    {
        $query = $this->table($this->table)->set($data)->where('idpinjam',$id)->update();
        return $query ? true : false;
    }

    public function deletedTransaksipinjam($id)
    {
        $query = $this->table($this->table)
                ->where('idpinjam',$id)
                ->delete();
        return $query ? true : false;
    }

    function gettranspinjambyid($id)
    {
         $query = $this->table($this->table)
                ->select('tbl_pinjaman .*, tbl_anggota.nm_anggota, tbl_anggota.no_telp')
                ->join('tbl_anggota', 'tbl_anggota.idanggota=tbl_pinjaman.idanggota')
                ->where('tbl_pinjaman.deleted_at', null)
                ->where('tbl_pinjaman.idpinjam', $id)
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

    function allTransaksipinjam($limit,$start,$col,$dir)
    {   
        $result = $this->table($this->table)
                ->select('tbl_pinjaman .*, tbl_anggota.nm_anggota')
                ->join('tbl_anggota', 'tbl_anggota.idanggota=tbl_pinjaman.idanggota')
                ->where('tbl_pinjaman.deleted_at', null)
                ->where('tbl_pinjaman.acc', 1)
                ->where('tbl_pinjaman.status_pembayaran', 0)
                ->limit($limit,$start)
                ->orderby($col,$dir)
                ->countAll();
        if ($result > 0){
            $result = $this->table($this->table)
                ->select('tbl_pinjaman .*, tbl_anggota.nm_anggota')
                ->join('tbl_anggota', 'tbl_anggota.idanggota=tbl_pinjaman.idanggota')
                ->where('tbl_pinjaman.deleted_at', null)
                ->where('tbl_pinjaman.acc', 1)
                ->where('tbl_pinjaman.status_pembayaran', 0)
                ->limit($limit,$start)
                ->orderby($col,$dir)
                ->get()
                ->getResult();
        }else{
            $result = array();
        }
        
        return $result;
    }

    function Transaksipinjam_search($limit,$start,$search,$col,$dir)
    {
        $query = $this->table($this->table)
                ->select('tbl_pinjaman .*, tbl_anggota.nm_anggota')
                ->join('tbl_anggota', 'tbl_anggota.idanggota=tbl_pinjaman.idanggota')
                ->where('tbl_pinjaman.deleted_at', null)
                ->where('tbl_pinjaman.acc', 1)
                ->where('tbl_pinjaman.status_pembayaran', 0)
                ->like('tbl_anggota.nm_anggota',$search)
                ->limit($limit,$start)
                ->orderby($col,$dir)
                ->countAll();
        if ($query > 0){
             $query = $this->table($this->table)
                    ->select('tbl_pinjaman .*, tbl_anggota.nm_anggota')
                    ->join('tbl_anggota', 'tbl_anggota.idanggota=tbl_pinjaman.idanggota')
                    ->where('tbl_pinjaman.deleted_at', null)
                    ->where('tbl_pinjaman.acc', 1)
                    ->where('tbl_pinjaman.status_pembayaran', 0)
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

    function Transaksipinjam_search_count($id,$search)
    {
       $query = $this->table($this->table)
                ->select('tbl_pinjaman .*, tbl_anggota.nm_anggota')
                ->join('tbl_anggota', 'tbl_anggota.idanggota=tbl_pinjaman.idanggota')
                ->where('tbl_pinjaman.deleted_at', null)
                ->where('tbl_pinjaman.acc', 1)
                ->where('tbl_pinjaman.status_pembayaran', 0)
                ->like('tbl_anggota.nm_anggota',$search);
        return $query->countAllResults();
    } 

}