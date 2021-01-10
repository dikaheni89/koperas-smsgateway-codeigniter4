<?php namespace App\Models;

use CodeIgniter\Model;

/**
* 
*/
class Simpanan_model extends Model
{
	protected $table = 'tbl_simpanan';
    protected $primaryKey   = 'idsimpanan';
    protected $allowedFields = ['idanggota', 'tgl_pembayaran', 'pokok', 'wajib', 'sukarela', 'status_simpanan']; 
    protected $useSoftDeletes = false;
    protected $useTimestamps = false;
    protected $deletedField  = 'deleted_at';

    public function insertSimpanan($data)
    {
        $query = $this->table($this->table)->insert($data);
        return $query ? true : false;
    }

    public function updateSimpanan($id,$data)
    {
        $query = $this->table($this->table)->set($data)->where('idsimpanan',$id)->update();
        return $query ? true : false;
    }

    public function deletedSimpanan($id)
    {
        $query = $this->table($this->table)
                ->where('idsimpanan',$id)
                ->delete();
        return $query ? true : false;
    }

    function getsimpananbyid($id)
    {
         $query = $this->table($this->table)
                ->select('tbl_simpanan .*, SUM(tbl_simpanan.pokok) as jml_pokok, SUM(tbl_simpanan.wajib) as jml_wajib, SUM(tbl_simpanan.sukarela) as jml_sukarela')
                ->where('tbl_simpanan.idanggota',$id)
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

    function allsimpanan($limit,$start,$col,$dir)
    {   
        $result = $this->table($this->table)
                ->select('tbl_simpanan .*, tbl_anggota.nm_anggota, tbl_anggota.no_telp')
                ->join('tbl_anggota', 'tbl_simpanan.idanggota=tbl_anggota.idanggota')
                ->where('tbl_simpanan.deleted_at', null)
                ->limit($limit,$start)
                ->orderby($col,$dir)
                ->countAll();
        if ($result > 0){
            $result = $this->table($this->table)
                ->select('tbl_simpanan .*, tbl_anggota.nm_anggota, tbl_anggota.no_telp')
                ->join('tbl_anggota', 'tbl_simpanan.idanggota=tbl_anggota.idanggota')
                ->where('tbl_simpanan.deleted_at', null)
                ->limit($limit,$start)
                ->orderby($col,$dir)
                ->get()
                ->getResult();
        }else{
            $result = array();
        }
        
        return $result;
    }

    function simpanan_search($limit,$start,$search,$col,$dir)
    {
        $query = $this->table($this->table)
                ->select('tbl_simpanan .*, tbl_anggota.nm_anggota, tbl_anggota.no_telp')
                ->join('tbl_anggota', 'tbl_simpanan.idanggota=tbl_anggota.idanggota')
                ->where('tbl_simpanan.deleted_at', null)
                ->like('tbl_anggota.nm_anggota',$search)
                ->limit($limit,$start)
                ->orderby($col,$dir)
                ->countAll();
        if ($query > 0){
             $query = $this->table($this->table)
                    ->select('tbl_simpanan .*, tbl_anggota.nm_anggota, tbl_anggota.no_telp')
                    ->join('tbl_anggota', 'tbl_simpanan.idanggota=tbl_anggota.idanggota')
                    ->where('tbl_simpanan.deleted_at', null)
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

    function simpanan_search_count($search)
    {
       $query = $this->table($this->table)
                ->select('tbl_simpanan .*, tbl_anggota.nm_anggota, tbl_anggota.no_telp')
                ->join('tbl_anggota', 'tbl_simpanan.idanggota=tbl_anggota.idanggota')
                ->where('tbl_simpanan.deleted_at', null)
                ->like('tbl_anggota.nm_anggota',$search);
        return $query->countAllResults();
    } 

    public function getlaporansimpanan()
    {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'tbl_simpanan.idsimpanan';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'DESC';
        $start = isset($_POST['start']) ? strval($_POST['start']) : '';
        $end = isset($_POST['end']) ? strval($_POST['end']) : '';
        $level = isset($_POST['is_level']) ? strval($_POST['is_level']) : '';
        $offset = ($page-1)*$rows;
        $result = array();
        $query  = $this->table($this->table)
                ->select('tbl_simpanan .*, SUM(tbl_simpanan.pokok) as jml_pokok, SUM(tbl_simpanan.wajib) as jml_wajib, SUM(tbl_simpanan.sukarela) as jml_sukarela, tbl_anggota.nm_anggota, tbl_anggota.no_telp')
                ->join('tbl_anggota', 'tbl_simpanan.idanggota=tbl_anggota.idanggota')
                ->where('tbl_simpanan.tgl_pembayaran BETWEEN "'. $start . '" and "'. $end .'"')
                ->groupBy('tbl_simpanan.idanggota')
                ->countAllResults();
        $result['total'] = $query;

        $query  = $this->table($this->table)
                ->select('tbl_simpanan .*, SUM(tbl_simpanan.pokok) as jml_pokok, SUM(tbl_simpanan.wajib) as jml_wajib, SUM(tbl_simpanan.sukarela) as jml_sukarela, tbl_anggota.nm_anggota, tbl_anggota.no_telp')
                ->join('tbl_anggota', 'tbl_simpanan.idanggota=tbl_anggota.idanggota')
                ->where('tbl_simpanan.tgl_pembayaran BETWEEN "'. $start . '" and "'. $end .'"')
                ->groupBy('tbl_simpanan.idanggota')
                ->orderBy($sort,$order)
                ->limit($rows,$offset)
                ->get();
        
        $item = $query->getResultArray();
        $result = array_merge($result, ['rows' => $item]);
        return $result;
    }

    function getlaporanpdf($start, $end)
    {
        $query  = $this->table($this->table)
                ->select('tbl_simpanan .*, SUM(tbl_simpanan.pokok) as jml_pokok, SUM(tbl_simpanan.wajib) as jml_wajib, SUM(tbl_simpanan.sukarela) as jml_sukarela, tbl_anggota.nm_anggota, tbl_anggota.no_telp')
                ->join('tbl_anggota', 'tbl_simpanan.idanggota=tbl_anggota.idanggota')
                ->where('tbl_simpanan.tgl_pembayaran BETWEEN "'. $start . '" and "'. $end .'"')
                ->groupBy('tbl_simpanan.idanggota')
                ->get()->getResultArray();
        return $query;
    }
}