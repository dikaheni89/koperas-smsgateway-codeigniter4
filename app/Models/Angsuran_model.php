<?php namespace App\Models;

use CodeIgniter\Model;

/**
* 
*/
class Angsuran_model extends Model
{
	protected $table = 'tbl_angsuran';
    protected $primaryKey   = 'idangsuran';
    protected $allowedFields = ['idangsuran', 'idpinjam', 'tgl_angsuran', 'jml_bayar', 'sisa_jml_bayar']; 
    protected $useSoftDeletes = false;
    protected $useTimestamps = false;
    protected $deletedField  = 'deleted_at';

    public function insertAngsuran($data)
    {
        $query = $this->table($this->table)->insert($data);
        return $query ? true : false;
    }

    public function updateAngsuran($id,$data)
    {
        $query = $this->table($this->table)->set($data)->where('idangsuran',$id)->update();
        return $query ? true : false;
    }

    public function deletedAngsuran($id)
    {
        $query = $this->table($this->table)
                ->where('idangsuran',$id)
                ->delete();
        return $query ? true : false;
    }

    function getpinjamanbyid($id)
    {
         $query = $this->table('tbl_pinjaman')
                ->where('deleted_at', null)
                ->where('idpinjam',$id)
                ->get();
        return $query->getRowArray();
    }

    function countAll()
    {   
        $query = $this->table($this->table)
                ->where('tbl_angsuran.deleted_at', null)
                ->countAllResults();
        return $query;
    }

    function allangsuran($id,$limit,$start,$col,$dir)
    {   
        $result = $this->table($this->table)
                ->select('tbl_angsuran .*, tbl_pinjaman.idpinjam, tbl_anggota.nm_anggota')
                ->join('tbl_pinjaman', 'tbl_pinjaman.idpinjam=tbl_angsuran.idpinjam')
                ->join('tbl_anggota', 'tbl_anggota.idanggota=tbl_pinjaman.idanggota')
                ->where('tbl_angsuran.deleted_at', null)
                ->where('tbl_angsuran.idpinjam', $id)
                ->limit($limit,$start)
                ->orderby($col,$dir)
                ->countAll();
        if ($result > 0){
            $result = $this->table($this->table)
                ->select('tbl_angsuran .*, tbl_pinjaman.idpinjam, tbl_anggota.nm_anggota')
                ->join('tbl_pinjaman', 'tbl_pinjaman.idpinjam=tbl_angsuran.idpinjam')
                ->join('tbl_anggota', 'tbl_anggota.idanggota=tbl_pinjaman.idanggota')
                ->where('tbl_angsuran.deleted_at', null)
                ->where('tbl_angsuran.idpinjam', $id)
                ->limit($limit,$start)
                ->orderby($col,$dir)
                ->get()
                ->getResult();
        }else{
            $result = array();
        }
        
        return $result;
    }

    function angsuran_search($id,$limit,$start,$search,$col,$dir)
    {
        $query = $this->table($this->table)
                ->select('tbl_angsuran .*, tbl_pinjaman.idpinjam, tbl_anggota.nm_anggota')
                ->join('tbl_pinjaman', 'tbl_pinjaman.idpinjam=tbl_angsuran.idpinjam')
                ->join('tbl_anggota', 'tbl_anggota.idanggota=tbl_pinjaman.idanggota')
                ->where('tbl_angsuran.deleted_at', null)
                ->where('tbl_angsuran.idpinjam', $id)
                ->like('tbl_anggota.nm_anggota',$search)
                ->limit($limit,$start)
                ->orderby($col,$dir)
                ->countAll();
        if ($query > 0){
             $query = $this->table($this->table)
                    ->select('tbl_angsuran .*, tbl_pinjaman.idpinjam, tbl_anggota.nm_anggota')
                    ->join('tbl_pinjaman', 'tbl_pinjaman.idpinjam=tbl_angsuran.idpinjam')
                    ->join('tbl_anggota', 'tbl_anggota.idanggota=tbl_pinjaman.idanggota')
                    ->where('tbl_angsuran.deleted_at', null)
                    ->where('tbl_angsuran.idpinjam', $id)
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

    function angsuran_search_count($id,$search)
    {
       $query = $this->table($this->table)
                ->select('tbl_angsuran .*, tbl_pinjaman.idpinjam, tbl_anggota.nm_anggota')
                ->join('tbl_pinjaman', 'tbl_pinjaman.idpinjam=tbl_angsuran.idpinjam')
                ->join('tbl_anggota', 'tbl_anggota.idanggota=tbl_pinjaman.idanggota')
                ->where('tbl_angsuran.deleted_at', null)
                ->where('tbl_angsuran.idpinjam', $id)
                ->like('tbl_anggota.nm_anggota',$search);
        return $query->countAllResults();
    } 

    public function getlaporanpinjaman()
    {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'tbl_angsuran.idangsuran';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'DESC';
        $start = isset($_POST['start']) ? strval($_POST['start']) : '';
        $end = isset($_POST['end']) ? strval($_POST['end']) : '';
        $offset = ($page-1)*$rows;
        $result = array();
        $query  = $this->table($this->table)
                ->select('tbl_angsuran .*, SUM(tbl_angsuran.jml_bayar) as total_angsuran, tbl_pinjaman.nominal_pinjam, tbl_anggota.nm_anggota')
                ->join('tbl_pinjaman', 'tbl_pinjaman.idpinjam=tbl_angsuran.idpinjam')
                ->join('tbl_anggota', 'tbl_anggota.idanggota=tbl_pinjaman.idanggota')
                ->where('tbl_angsuran.tgl_angsuran BETWEEN "'. $start . '" and "'. $end .'"')
                ->groupBy('tbl_pinjaman.idanggota')
                ->countAllResults();
        $result['total'] = $query;

        $query  = $this->table($this->table)
                ->select('tbl_angsuran .*, SUM(tbl_angsuran.jml_bayar) as total_angsuran, tbl_pinjaman.nominal_pinjam, tbl_anggota.nm_anggota')
                ->join('tbl_pinjaman', 'tbl_pinjaman.idpinjam=tbl_angsuran.idpinjam')
                ->join('tbl_anggota', 'tbl_anggota.idanggota=tbl_pinjaman.idanggota')
                ->where('tbl_angsuran.tgl_angsuran BETWEEN "'. $start . '" and "'. $end .'"')
                ->groupBy('tbl_pinjaman.idanggota')
                ->orderBy($sort,$order)
                ->limit($rows,$offset)
                ->get();
        
        $item = $query->getResultArray();
        $result = array_merge($result, ['rows' => $item]);
        return $result;
    }

    function getlaporanpinjamanpdf($start, $end)
    {
        $query  = $this->table($this->table)
                ->select('tbl_angsuran .*, SUM(tbl_angsuran.jml_bayar) as total_angsuran, tbl_pinjaman.nominal_pinjam, tbl_anggota.nm_anggota')
                ->join('tbl_pinjaman', 'tbl_pinjaman.idpinjam=tbl_angsuran.idpinjam')
                ->join('tbl_anggota', 'tbl_anggota.idanggota=tbl_pinjaman.idanggota')
                ->where('tbl_angsuran.tgl_angsuran BETWEEN "'. $start . '" and "'. $end .'"')
                ->groupBy('tbl_pinjaman.idanggota')
                ->get()->getResultArray();
        return $query;
    }

    function getlaporanpinjamantotal($start, $end)
    {
        $query  = $this->table($this->table)
                ->select('tbl_angsuran .*, SUM(tbl_angsuran.jml_bayar) as total_angsuran, tbl_pinjaman.nominal_pinjam, tbl_anggota.nm_anggota')
                ->join('tbl_pinjaman', 'tbl_pinjaman.idpinjam=tbl_angsuran.idpinjam')
                ->join('tbl_anggota', 'tbl_anggota.idanggota=tbl_pinjaman.idanggota')
                ->where('tbl_angsuran.tgl_angsuran BETWEEN "'. $start . '" and "'. $end .'"')
                ->groupBy('tbl_pinjaman.idanggota')
                ->get()->getRowArray();
        return $query;
    }

    public function getlaporanangsuran()
    {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'tbl_angsuran.idangsuran';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'ASC';
        $start = isset($_POST['start']) ? strval($_POST['start']) : '';
        $end = isset($_POST['end']) ? strval($_POST['end']) : '';
        $offset = ($page-1)*$rows;
        $result = array();
        $query  = $this->table($this->table)
                ->select('tbl_angsuran .*, tbl_pinjaman.jml_pinjaman, tbl_pinjaman.nominal_pinjam, tbl_anggota.nm_anggota')
                ->join('tbl_pinjaman', 'tbl_pinjaman.idpinjam=tbl_angsuran.idpinjam')
                ->join('tbl_anggota', 'tbl_anggota.idanggota=tbl_pinjaman.idanggota')
                ->where('tbl_angsuran.tgl_angsuran BETWEEN "'. $start . '" and "'. $end .'"')
                ->countAllResults();
        $result['total'] = $query;

        $query  = $this->table($this->table)
                ->select('tbl_angsuran .*, tbl_pinjaman.jml_pinjaman, tbl_pinjaman.nominal_pinjam, tbl_anggota.nm_anggota')
                ->join('tbl_pinjaman', 'tbl_pinjaman.idpinjam=tbl_angsuran.idpinjam')
                ->join('tbl_anggota', 'tbl_anggota.idanggota=tbl_pinjaman.idanggota')
                ->where('tbl_angsuran.tgl_angsuran BETWEEN "'. $start . '" and "'. $end .'"')
                ->orderBy($sort,$order)
                ->limit($rows,$offset)
                ->get();
        
        $item = $query->getResultArray();
        $result = array_merge($result, ['rows' => $item]);
        return $result;
    }
}