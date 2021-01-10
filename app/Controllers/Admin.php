<?php namespace App\Controllers;

use App\Controllers\Menu;
use TCPDF;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\I18n\Time;

class Admin extends BaseController
{
	protected $session;
    protected $baseUrl;

    public function __construct()
    {
    	$this->session = \Config\Services::session();
        $this->security = \Config\Services::security();
        $this->baseUrl = base_url();
        $this->menus = new Menu();
        helper(['url','fatih']);
        $this->sess_id=$this->session->has('uid');

        $this->usersmodel = model('App\Models\Users_model',false);
        $this->level = model('App\Models\Level_model',false);

        $this->anggotamodel = model('App\Models\Anggota_model',false);
        $this->pinjamanmodel = model('App\Models\Pinjaman_model',false);
        $this->angsuranmodel = model('App\Models\Angsuran_model',false);
        $this->transaksipinjamanmodel = model('App\Models\Transaksipinjam_model',false);
        $this->jenismodel = model('App\Models\Jenis_model',false);
        $this->simpananmodel = model('App\Models\Simpanan_model',false);
        $this->outboxmodel = model('App\Models\Outbox_model',false);
    }

    public function index()
    {
    	$data=[
	        'title'     => 'KOPERASI SMA MATHLAUL ANWAR',
	        'apps'      => 'KOPSIP',
	        'profil'    => $this->session->get('user'),
        ];
        $data['menus']  = $this->menus->getmenus($this->sess_id);
        $data['css_files'][] = '';
        $data['js_files'][] ='';
        return render('admin/dashboard',$data);
    }

    //Check Email
    function getUserByEmail()
    {
        $data=$this->usersmodel->getusersbyemail();
        if ($data > 0){
            echo json_encode(FALSE);
        } else {
            echo json_encode(TRUE);
        }
    }

    public function profil()
    {
      $data=[
        'title'     => 'Setting Profile',
        'apps'      => 'KOPSIP',
        'profil'    => $this->session->get('user')
      ];
      $data['level'] = $this->level->getcomboLevel();
      $data['menus']  = $this->menus->getmenus($this->sess_id);
      $data['css_files'][] = base_url('template/vendors/DataTables/datatables.min.css');
      $data['js_files'][] = base_url('template/vendors/DataTables/datatables.min.js');
      $data['css_files'][] = base_url('template/vendors/select2/dist/css/select2.css');
      $data['js_files'][] = base_url('template/vendors/select2/dist/js/select2.full.min.js');
      $data['js_files'][] = base_url('template/vendors/jquery-validation/dist/jquery.validate.min.js');
      return render('admin/profil/profil', $data);
    }

    public function updateprofil()
    {
        $user = $this->request->getPost('user');
        $full_name = $this->request->getPost('full_name');
        $email = $this->request->getPost('email');
        $phone = $this->request->getPost('phone');
        $level = $this->request->getPost('is_level');
        $aktif = $this->request->getPost('is_active');
        $img = $this->request->getFile('photo');
        $data=array();
        $img->move('./uploads/profil/');
        $data = array(
            'user'      => $user,
            'full_name' => $full_name,
            'email'     => $email,
            'phone'     => $phone,
            'is_level'  => $level,
            'is_active' => $aktif,
            'photo'     => $img->getName()
        );
        $id = $this->request->getPost('id');
        $result = $this->usersmodel->updateUsers($id, $data);
        if ($result){
            echo json_encode(array('message'=>'Update Success'));
        } else {
            echo json_encode(array('errorMsg'=>'Some errors occured.'));
        }
    }

    public function users()
    {
    	$data=[
    		'title'		=> 'MANAGEMENT ADMINISTRATOR',
    		'apps'		=> 'KOPSIP',
    		'profil'	=> $this->session->get('user'),
    	];
        $data['menus']  = $this->menus->getmenus($this->sess_id);
    	$data['css_files'][] = base_url('template/vendors/DataTables/datatables.min.css');
        $data['js_files'][] = base_url('template/vendors/DataTables/datatables.min.js');
        return render('admin/administrator/admin',$data);
    }

    public function getusers()
    {  
        $columns = array(
            0=>'_id', 
            1=>'user',
            2=> 'full_name',
            3=> 'email',
            4=> 'phone'
        );
        $limit = $this->request->getPost('length');
        $start = $this->request->getPost('start');
        $order = $columns[$this->request->getPost('order')[0]['column']];
        $dir = $this->request->getPost('order')[0]['dir'];
        $totalData = $this->usersmodel->countAll();

        $totalFiltered = $totalData; 

        if(empty($this->request->getPost('search')['value']))
        {            
            $users = $this->usersmodel->allusers($limit,$start,$order,$dir);
        }
        else {
            $search = $this->request->getPost('search')['value']; 

            $users =  $this->usersmodel->users_search($limit,$start,$search,$order,$dir);

            $totalFiltered = $this->usersmodel->users_search_count($search);
        }

        $data = array();
        $no=1;
        foreach ($users as $user)
        {
            $nestedData['no'] = $no++;
            $nestedData['_id'] = $user->_id;
            $nestedData['user'] = $user->user;
            $nestedData['full_name'] = $user->full_name;
            $nestedData['email'] = $user->email;
            $nestedData['phone'] = $user->phone;
            $nestedData['is_active'] = $user->is_active;
            
            $data[] = $nestedData;

        }
        $output = array(
            "draw"            => intval($this->request->getpost('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
        );
        return $this->response->setJSON($output);
    }

    public function addadmin()
    {
    	$data=[
            'title'     => 'Tambah Data Administrator',
            'apps'      => 'KOPSIP .::. Tambah Data Administrator',
            'profil'    => $this->session->get('user'),
        ];
        $data['menus']  = $this->menus->getmenus($this->sess_id);
        $data['level'] = $this->level->getcomboLevel();
        $data['css_files'][] = base_url('template/vendors/DataTables/datatables.min.css');
        $data['js_files'][] = base_url('template/vendors/DataTables/datatables.min.js');
        $data['css_files'][] = base_url('template/vendors/select2/dist/css/select2.css');
        $data['js_files'][] = base_url('template/vendors/select2/dist/js/select2.full.min.js');
        $data['js_files'][] = base_url('template/vendors/jquery-validation/dist/jquery.validate.min.js');
        return render('admin/administrator/addadmin',$data);
    }

    public function saveUsers()
    {
        $user = $this->request->getPost('user');
        $email = $this->request->getPost('email');
        $full_name = $this->request->getPost('full_name');
        $phone = $this->request->getPost('phone');
        $is_aktif = $this->request->getPost('is_active');
        $is_level = $this->request->getPost('is_level');
        $options = [
            'cost' => 10,
        ];
        $data=array();
        $data = array(
            'user'         => $user,
            'password'     => password_hash($user, PASSWORD_DEFAULT, $options),
            'email'        => $email,
            'full_name'    => $full_name,
            'phone'        => $phone,
            'is_active'    => $is_aktif,
            'is_level'     => $is_level,
        );
        $result = $this->usersmodel->insertUsers($data);
        if ($result){
            echo json_encode(array('message'=>'Save Success'));
        } else {
            echo json_encode(array('errorMsg'=>'Some errors occured.'));
        }
    }

    public function resetuser()
    {
        $where =  $this->request->getPost('id');
        $user = $this->request->getPost('user');
        $data=array();
        $options = [
            'cost' => 10,
        ];
        $data = array(
            'password'     => password_hash($user, PASSWORD_DEFAULT, $options),
        );
        $result = $this->usersmodel->resetpassword($where,$data);
        if ($result){
            echo json_encode(array('message'=>'Resert Success'));
        } else {
            echo json_encode(array('errorMsg'=>'Some errors occured.'));
        }
    }

    public function deleteduser()
    {
        $where =  $this->request->getPost('id');
        $result = $this->usersmodel->deleteduser($where);
        if ($result){
            echo json_encode(array('message'=>'Deleted Success'));
        } else {
            echo json_encode(array('errorMsg'=>'Some errors occured.'));
        }
    }

    // level
    public function level()
    {
      $data=[
        'title'     => 'Level Users',
        'apps'      => 'KOPSIPJAM .::. Level Users',
        'profil'    => $this->session->get('user')
      ];
      $data['menus']  = $this->menus->getmenus($this->sess_id);
      $data['css_files'][] = base_url('template/vendors/DataTables/datatables.min.css');
      $data['js_files'][] = base_url('template/vendors/DataTables/datatables.min.js');
      $data['js_files'][] = base_url('template/vendors/jquery-validation/dist/jquery.validate.min.js');
      return render('admin/level/level', $data);
    }

    public function getlevel()
    {  
        $columns = array(
            0=>'_id', 
            1=>'level',
        );
        $limit = $this->request->getPost('length');
        $start = $this->request->getPost('start');
        $order = $columns[$this->request->getPost('order')[0]['column']];
        $dir = $this->request->getPost('order')[0]['dir'];
        $totalData = $this->level->countAll();

        $totalFiltered = $totalData; 

        if(empty($this->request->getPost('search')['value']))
        {            
            $users = $this->level->alllevel($limit,$start,$order,$dir);
        }
        else {
            $search = $this->request->getPost('search')['value']; 

            $users =  $this->level->level_search($limit,$start,$search,$order,$dir);

            $totalFiltered = $this->level->level_search_count($search);
        }

        $data = array();
        $no=1;
        foreach ($users as $level)
        {
            $nestedData['no'] = $no++;
            $nestedData['_id'] = $level->_id;
            $nestedData['level'] = $level->level;
            
            $data[] = $nestedData;

        }
        $output = array(
            "draw"            => intval($this->request->getpost('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
        );
        return $this->response->setJSON($output);
    }

    public function addlevel()
    {
        $data=[
            'title'     => 'Tambah Data Level Users',
            'apps'      => 'KOPSIPJAM .::. Tambah Data Level Users',
            'profil'    => $this->session->get('user'),
        ];
        $data['menus']  = $this->menus->getmenus($this->sess_id);
        $data['css_files'][] = base_url('template/vendors/DataTables/datatables.min.css');
        $data['js_files'][] = base_url('template/vendors/DataTables/datatables.min.js');
        $data['js_files'][] = base_url('template/vendors/jquery-validation/dist/jquery.validate.min.js');
        return render('admin/level/addlevel',$data);
    }

    public function editlevel($id)
    {
        $data=[
            'title'     => 'Edit Data Level Users',
            'apps'      => 'KOPSIPJAM .::. Edit Data Level Users',
            'profil'    => $this->session->get('user'),
            'level'     => $this->level->getlevelbyid($id)
        ];
        $data['menus']  = $this->menus->getmenus($this->sess_id);
        $data['css_files'][] = base_url('template/vendors/DataTables/datatables.min.css');
        $data['js_files'][] = base_url('template/vendors/DataTables/datatables.min.js');
        $data['js_files'][] = base_url('template/vendors/jquery-validation/dist/jquery.validate.min.js');
        return render('admin/level/editlevel',$data);
    }

    public function savelevel()
    {
        $level = $this->request->getPost('level');
        $data=array();
        $data = array(
            'level'         => $level
        );
        $result = $this->level->insertLevel($data);
        if ($result){
            echo json_encode(array('message'=>'Save Success'));
        } else {
            echo json_encode(array('errorMsg'=>'Some errors occured.'));
        }
    }

    public function updatelevel()
    {
        $level = $this->request->getPost('level');
        $data=array();
        $data = array(
            'level'         => $level
        );
        $id = $this->request->getPost('id');
        $result = $this->level->updateLevel($id, $data);
        if ($result){
            echo json_encode(array('message'=>'Update Success'));
        } else {
            echo json_encode(array('errorMsg'=>'Some errors occured.'));
        }
    }

    public function deletedlevel()
    {
        $where =  $this->request->getPost('id');
        $result = $this->level->deleteLevel($where);
        if ($result){
            echo json_encode(array('message'=>'Deleted Success'));
        } else {
            echo json_encode(array('errorMsg'=>'Some errors occured.'));
        }
    }

    //Combo Level
    public function comboLevel()
    {
        $data=$this->level->getcomboLevel();
        $result = $this->response->setJSON($data);
        return $result;
    }

    public function comboJenis()
    {
        $data=$this->jenismodel->getcomboJenis();
        $result = $this->response->setJSON($data);
        return $result;
    }

    // anggota
    public function anggota()
    {
      $data=[
        'title'     => 'Data Anggota',
        'apps'      => 'KOPSIPJAM .::. Data Anggota',
        'profil'    => $this->session->get('user')
      ];
      $data['menus']  = $this->menus->getmenus($this->sess_id);
      $data['css_files'][] = base_url('template/vendors/DataTables/datatables.min.css');
      $data['css_files'][] = "https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.css";
      $data['js_files'][] = base_url('template/vendors/DataTables/datatables.min.js');
      $data['js_files'][] = "https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.js";
      return render('admin/anggota/anggota', $data);
    }

    public function getanggota()
    {  
        $columns = array(
            0=>'idanggota', 
            1=>'no_identitas',
            2=>'nm_anggota',
            3=>'jk',
            4=>'alamat',
            5=>'no_telp',
            6=>'photo',
            7=>'tgl_masuk',
            8=>'status_anggota'
        );
        $limit = $this->request->getPost('length');
        $start = $this->request->getPost('start');
        $order = $columns[$this->request->getPost('order')[0]['column']];
        $dir = $this->request->getPost('order')[0]['dir'];
        $totalData = $this->anggotamodel->countAll();

        $totalFiltered = $totalData; 

        if(empty($this->request->getPost('search')['value']))
        {            
            $list = $this->anggotamodel->allanggota($limit,$start,$order,$dir);
        }
        else {
            $search = $this->request->getPost('search')['value']; 

            $list =  $this->anggotamodel->anggota_search($limit,$start,$search,$order,$dir);

            $totalFiltered = $this->anggotamodel->anggota_search_count($search);
        }

        $data = array();
        $no=1;
        foreach ($list as $anggota)
        {
            $nestedData['no'] = $no++;
            $nestedData['idanggota'] = $anggota->idanggota;
            $nestedData['no_identitas'] = $anggota->no_identitas;
            $nestedData['nm_anggota'] = $anggota->nm_anggota;
            $nestedData['jk'] = $anggota->jk;
            $nestedData['alamat'] = $anggota->alamat;
            $nestedData['no_telp'] = $anggota->no_telp;
            $nestedData['photo'] = $anggota->photo;
            $nestedData['tgl_masuk'] = $anggota->tgl_masuk;
            $nestedData['status_anggota'] = $anggota->status_anggota;
            
            $data[] = $nestedData;

        }
        $output = array(
            "draw"            => intval($this->request->getpost('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
        );
        return $this->response->setJSON($output);
    }

    public function addanggota()
    {
        $data=[
            'title'     => 'Tambah Data Anggota',
            'apps'      => 'KOPSIP .::. Tambah Data Anggota',
            'profil'    => $this->session->get('user'),
        ];
        $data['menus']  = $this->menus->getmenus($this->sess_id);
        $data['css_files'][] = base_url('template/vendors/DataTables/datatables.min.css');
        $data['css_files'][] = base_url('template/vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css');
        $data['js_files'][] = base_url('template/vendors/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js');

        $data['css_files'][] = base_url('template/vendors/bootstrap_date_time/css/bootstrap-datetimepicker.min.css');
        $data['js_files'][] = base_url('template/vendors/bootstrap_date_time/js/bootstrap-datetimepicker.min.js');
        $data['js_files'][] = base_url('template/vendors/DataTables/datatables.min.js');
        $data['js_files'][] = base_url('template/vendors/jquery-validation/dist/jquery.validate.min.js');
        return render('admin/anggota/addanggota',$data);
    }

    public function saveanggota()
    {
        $no_identitas = $this->request->getPost('no_identitas');
        $nm_anggota = $this->request->getPost('nm_anggota');
        $jk = $this->request->getPost('jk');
        $alamat = $this->request->getPost('alamat');
        $no_telp = $this->request->getPost('no_telp');
        $photo = $this->request->getFile('photo');
        $tgl_masuk = $this->request->getPost('tgl_masuk');
        $status_anggota = $this->request->getPost('status_anggota');
        $data=array();
        if ($photo != null) {
            $photo->move('./uploads/anggota/');
            $data = array(
                'no_identitas' => $no_identitas,
                'nm_anggota'   => $nm_anggota,
                'jk'           => $jk,
                'alamat'       => $alamat,
                'no_telp'      => $no_telp,
                'photo'        => $photo->getName(),
                'tgl_masuk'    => $tgl_masuk,
                'status_anggota' => $status_anggota,
            );
        }else{
            $data = array(
                'no_identitas' => $no_identitas,
                'nm_anggota'   => $nm_anggota,
                'jk'           => $jk,
                'alamat'       => $alamat,
                'no_telp'      => $no_telp,
                'tgl_masuk'    => $tgl_masuk,
                'status_anggota' => $status_anggota,
            );
        }
        
        $result = $this->anggotamodel->insertAnggota($data);
        if ($result){
            echo json_encode(array('message'=>'Save Success'));
        } else {
            echo json_encode(array('errorMsg'=>'Some errors occured.'));
        }
    }

    public function editanggota($id)
    {
        $data=[
            'title'     => 'Update Data Anggota',
            'apps'      => 'KOPSIP .::. Update Data Anggota',
            'profil'    => $this->session->get('user'),
            'anggota'   => $this->anggotamodel->getanggotabyid($id)
        ];
        $data['menus']  = $this->menus->getmenus($this->sess_id);
        $data['css_files'][] = base_url('template/vendors/DataTables/datatables.min.css');
        $data['css_files'][] = base_url('template/vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css');
        $data['js_files'][] = base_url('template/vendors/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js');

        $data['css_files'][] = base_url('template/vendors/bootstrap_date_time/css/bootstrap-datetimepicker.min.css');
        $data['js_files'][] = base_url('template/vendors/bootstrap_date_time/js/bootstrap-datetimepicker.min.js');
        $data['js_files'][] = base_url('template/vendors/DataTables/datatables.min.js');
        $data['js_files'][] = base_url('template/vendors/jquery-validation/dist/jquery.validate.min.js');
        return render('admin/anggota/editanggota',$data);
    }

    public function updateanggota()
    {
        $no_identitas = $this->request->getPost('no_identitas');
        $nm_anggota = $this->request->getPost('nm_anggota');
        $jk = $this->request->getPost('jk');
        $alamat = $this->request->getPost('alamat');
        $no_telp = $this->request->getPost('no_telp');
        $photo = $this->request->getFile('photo');
        $tgl_masuk = $this->request->getPost('tgl_masuk');
        $status_anggota = $this->request->getPost('status_anggota');
        $data=array();
        if ($photo != null) {
            $photo->move('./uploads/anggota/');
            $data = array(
                'no_identitas' => $no_identitas,
                'nm_anggota'   => $nm_anggota,
                'jk'           => $jk,
                'alamat'       => $alamat,
                'no_telp'      => $no_telp,
                'photo'        => $photo->getName(),
                'tgl_masuk'    => $tgl_masuk,
                'status_anggota' => $status_anggota,
            );
        }else{
            $data = array(
                'no_identitas' => $no_identitas,
                'nm_anggota'   => $nm_anggota,
                'jk'           => $jk,
                'alamat'       => $alamat,
                'no_telp'      => $no_telp,
                'tgl_masuk'    => $tgl_masuk,
                'status_anggota' => $status_anggota,
            );
        }
        $id = $this->request->getPost('id');
        $result = $this->anggotamodel->updateanggota($id, $data);
        if ($result){
            echo json_encode(array('message'=>'Update Success'));
        } else {
            echo json_encode(array('errorMsg'=>'Some errors occured.'));
        }
    }

    public function deletedanggota()
    {
        $where =  $this->request->getPost('id');
        $result = $this->anggotamodel->deletedanggota($where);
        if ($result){
            echo json_encode(array('message'=>'Deleted Success'));
        } else {
            echo json_encode(array('errorMsg'=>'Some errors occured.'));
        }
    }

    // pinjaman
    public function pengajuan()
    {
      $data=[
        'title'     => 'Data Pengajuan Pinjaman Anggota',
        'apps'      => 'KOPSIPJAM .::. Data Pengajuan Pinjaman Anggota',
        'profil'    => $this->session->get('user')
      ];
      $data['menus']  = $this->menus->getmenus($this->sess_id);
      $data['css_files'][] = base_url('template/vendors/DataTables/datatables.min.css');
      $data['css_files'][] = "https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.css";
      $data['js_files'][] = base_url('template/vendors/DataTables/datatables.min.js');
      $data['js_files'][] = "https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.js";
      return render('admin/pinjaman/anggotapinjam', $data);
    }

    public function riwayat($id)
    {
      $data=[
        'title'     => 'Data Riwayat Pinjaman Anggota',
        'apps'      => 'KOPSIPJAM .::. Data Riwayat Pinjaman Anggota',
        'profil'    => $this->session->get('user'),
        'anggota'   => $this->anggotamodel->getanggotabyid($id),
        'pinjam'    => $this->pinjamanmodel->getpinjambyid($id)
      ];
      $data['menus']  = $this->menus->getmenus($this->sess_id);
      $data['css_files'][] = base_url('template/vendors/DataTables/datatables.min.css');
      $data['css_files'][] = "https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.css";
      $data['js_files'][] = base_url('template/vendors/DataTables/datatables.min.js');
      $data['js_files'][] = "https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.js";
      return render('admin/pinjaman/riwayat', $data);
    }

    public function getriwayat($id)
    {  
        $columns = array(
            0=>'idpinjam', 
            1=>'idanggota',
            2=>'tgl_pinjam',
            3=>'tgl_tempo',
            4=>'jml_pinjaman',
            5=>'nominal_pinjam',
            6=>'jml_angsuran',
            7=>'jml_bunga',
            8=>'nominal_angsuran',
            9=>'pembayaran_ke',
            10=>'acc',
            11=>'status_pembayaran'
        );
        $limit = $this->request->getPost('length');
        $start = $this->request->getPost('start');
        $order = $columns[$this->request->getPost('order')[0]['column']];
        $dir = $this->request->getPost('order')[0]['dir'];
        $totalData = $this->anggotamodel->countAll();

        $totalFiltered = $totalData; 

        if(empty($this->request->getPost('search')['value']))
        {            
            $list = $this->pinjamanmodel->allpinjam($id,$limit,$start,$order,$dir);
        }
        else {
            $search = $this->request->getPost('search')['value']; 

            $list =  $this->pinjamanmodel->pinjam_search($id,$limit,$start,$search,$order,$dir);

            $totalFiltered = $this->pinjamanmodel->pinjam_search_count($id,$search);
        }

        $data = array();
        $no=1;
        foreach ($list as $pinjaman)
        {
            $nestedData['no'] = $no++;
            $nestedData['idpinjam'] = $pinjaman->idpinjam;
            $nestedData['nm_anggota'] = $pinjaman->nm_anggota;
            $nestedData['tgl_pinjam'] = $pinjaman->tgl_pinjam;
            $nestedData['tgl_tempo'] = $pinjaman->tgl_tempo;
            $nestedData['jml_pinjaman'] = 'Rp. '.rupiah($pinjaman->jml_pinjaman).' -,';
            $nestedData['nominal_pinjam'] = 'Rp. '.rupiah($pinjaman->nominal_pinjam).' -,';
            $nestedData['jml_angsuran'] = $pinjaman->jml_angsuran;
            $nestedData['jml_bunga'] = $pinjaman->jml_bunga;
            $nestedData['nominal_angsuran'] = 'Rp. '.rupiah($pinjaman->nominal_angsuran).' -,';
            $nestedData['pembayaran_ke'] = $pinjaman->pembayaran_ke;
            $nestedData['acc'] = $pinjaman->acc;
            $nestedData['status_pembayaran'] = $pinjaman->status_pembayaran;
            $nestedData['level'] = $this->session->get('user')['is_level'];
            
            $data[] = $nestedData;

        }
        $output = array(
            "draw"            => intval($this->request->getpost('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
        );
        return $this->response->setJSON($output);
    }

    public function pengajuanpinjaman($id)
    {
        $data=[
            'title'     => 'Data Pengajuan Pinjaman Anggota',
            'apps'      => 'KOPSIP .::. Data Pengajuan Pinjaman Anggota',
            'profil'    => $this->session->get('user'),
            'anggota'   => $this->anggotamodel->getanggotabyid($id)
        ];
        $data['menus']  = $this->menus->getmenus($this->sess_id);
        $data['css_files'][] = base_url('template/vendors/DataTables/datatables.min.css');
        $data['css_files'][] = base_url('template/vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css');
        $data['js_files'][] = base_url('template/vendors/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js');

        $data['css_files'][] = base_url('template/vendors/bootstrap_date_time/css/bootstrap-datetimepicker.min.css');
        $data['js_files'][] = base_url('template/vendors/bootstrap_date_time/js/bootstrap-datetimepicker.min.js');
        $data['js_files'][] = base_url('template/vendors/DataTables/datatables.min.js');
        $data['js_files'][] = base_url('template/vendors/jquery-validation/dist/jquery.validate.min.js');
        return render('admin/pinjaman/pengajuan',$data);
    }

    public function savepengajuan()
    {
        $idanggota = $this->request->getPost('idanggota');
        $nominal_pinjam = $this->request->getPost('nominal_pinjam');
        $jml_angsuran = $this->request->getPost('jml_angsuran');
        $jml_bunga = $this->request->getPost('jml_bunga');
        $nominal_angsuran = $this->request->getPost('nominal_angsuran');
        $data=array();
        $data = array(
            'idanggota'        => $idanggota,
            'nominal_pinjam'   => $nominal_pinjam,
            'jml_pinjaman'     => $nominal_pinjam,
            'jml_angsuran'     => $jml_angsuran,
            'jml_bunga'        => $jml_bunga,
            'nominal_angsuran' => $nominal_angsuran
        );
        
        $result = $this->pinjamanmodel->insertPinjaman($data);
        if ($result){
            echo json_encode(array('message'=>'Save Success'));
        } else {
            echo json_encode(array('errorMsg'=>'Some errors occured.'));
        }
    }

    public function verifikasidata()
    {
        $id =  $this->request->getPost('id');
        $tgl_pinjam = date('Y-m-d');
        $tgl_tempo = date('Y-m-d', strtotime('+10 month', strtotime($tgl_pinjam)));
        $data=array();
        $data = array(
            'tgl_pinjam'    => $tgl_pinjam,
            'tgl_tempo'     => $tgl_tempo,
            'acc'           => 1
        );
        $result = $this->pinjamanmodel->updatePinjaman($id,$data);
        if ($result){
            echo json_encode(array('message'=>'Verifikasi Success'));
        } else {
            echo json_encode(array('errorMsg'=>'Some errors occured.'));
        }
    }

    public function angsuran($id)
    {
      $data=[
        'title'     => 'Detail Pinjaman Anggota',
        'apps'      => 'KOPSIPJAM .::. Detail Pinjaman Anggota',
        'profil'    => $this->session->get('user'),
        'id'        => $id
      ];
      $data['menus']  = $this->menus->getmenus($this->sess_id);
      $data['css_files'][] = base_url('template/vendors/DataTables/datatables.min.css');
      $data['js_files'][] = base_url('template/vendors/DataTables/datatables.min.js');
      return render('admin/pinjaman/angsuran', $data);
    }

    public function getangsuran($id)
    {  
        $columns = array(
            0=>'idangsuran', 
            1=>'idpinjam',
            2=>'tgl_angsuran',
            3=>'jml_bayar',
            4=>'sisa_jml_bayar'
        );
        $limit = $this->request->getPost('length');
        $start = $this->request->getPost('start');
        $order = $columns[$this->request->getPost('order')[0]['column']];
        $dir = $this->request->getPost('order')[0]['dir'];
        $totalData = $this->pinjamanmodel->countAll();

        $totalFiltered = $totalData; 

        if(empty($this->request->getPost('search')['value']))
        {            
            $list = $this->angsuranmodel->allangsuran($id,$limit,$start,$order,$dir);
        }
        else {
            $search = $this->request->getPost('search')['value']; 

            $list =  $this->angsuranmodel->angsuran_search($id,$limit,$start,$search,$order,$dir);

            $totalFiltered = $this->angsuranmodel->angsuran_search_count($id,$search);
        }

        $data = array();
        $no=1;
        foreach ($list as $angsuran)
        {
            $nestedData['no'] = $no++;
            $nestedData['idangsuran'] = $angsuran->idangsuran;
            $nestedData['idpinjam'] = $angsuran->idpinjam;
            $nestedData['nm_anggota'] = $angsuran->nm_anggota;
            $nestedData['tgl_angsuran'] = getbulan($angsuran->tgl_angsuran);
            $nestedData['jml_bayar'] = 'Rp. '.rupiah($angsuran->jml_bayar).' -,';
            $nestedData['sisa_jml_bayar'] = 'Rp. '.rupiah($angsuran->sisa_jml_bayar).' -,';
            
            $data[] = $nestedData;

        }
        $output = array(
            "draw"            => intval($this->request->getpost('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
        );
        return $this->response->setJSON($output);
    }

    public function transaksipinjam()
    {
        $data=[
        'title'     => 'Data Transaksi Pinjaman Anggota',
        'apps'      => 'KOPSIPJAM .::. Data Transaksi Pinjaman Anggota',
        'profil'    => $this->session->get('user')
      ];
      $data['menus']  = $this->menus->getmenus($this->sess_id);
      $data['css_files'][] = base_url('template/vendors/DataTables/datatables.min.css');
      $data['css_files'][] = "https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.css";
      $data['js_files'][] = base_url('template/vendors/DataTables/datatables.min.js');
      $data['js_files'][] = "https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.js";
      return render('admin/transaksi/transaksipinjam', $data);
    }

    public function gettransaksipinjam()
    {  
        $columns = array(
            0=>'idpinjam', 
            1=>'idanggota',
            2=>'tgl_pinjam',
            3=>'tgl_tempo',
            4=>'jml_pinjaman',
            5=>'nominal_pinjam',
            6=>'jml_angsuran',
            7=>'jml_bunga',
            8=>'nominal_angsuran',
            9=>'pembayaran_ke',
            10=>'acc',
            11=>'status_pembayaran'
        );
        $limit = $this->request->getPost('length');
        $start = $this->request->getPost('start');
        $order = $columns[$this->request->getPost('order')[0]['column']];
        $dir = $this->request->getPost('order')[0]['dir'];
        $totalData = $this->anggotamodel->countAll();

        $totalFiltered = $totalData; 

        if(empty($this->request->getPost('search')['value']))
        {            
            $list = $this->transaksipinjamanmodel->allTransaksipinjam($limit,$start,$order,$dir);
        }
        else {
            $search = $this->request->getPost('search')['value']; 

            $list =  $this->transaksipinjamanmodel->Transaksipinjam_search($limit,$start,$search,$order,$dir);

            $totalFiltered = $this->transaksipinjamanmodel->Transaksipinjam_search_count($search);
        }

        $data = array();
        $no=1;
        foreach ($list as $transaksipinjaman)
        {
            $nestedData['no'] = $no++;
            $nestedData['idpinjam'] = $transaksipinjaman->idpinjam;
            $nestedData['nm_anggota'] = $transaksipinjaman->nm_anggota;
            $nestedData['tgl_pinjam'] = $transaksipinjaman->tgl_pinjam;
            $nestedData['tgl_tempo'] = $transaksipinjaman->tgl_tempo;
            $nestedData['jml_pinjaman'] = 'Rp. '.rupiah($transaksipinjaman->jml_pinjaman);
            $nestedData['nominal_pinjam'] = 'Rp. '.rupiah($transaksipinjaman->nominal_pinjam).' -,';
            $nestedData['jml_angsuran'] = $transaksipinjaman->jml_angsuran;
            $nestedData['jml_bunga'] = $transaksipinjaman->jml_bunga;
            $nestedData['nominal_angsuran'] = 'Rp. '.rupiah($transaksipinjaman->nominal_angsuran).' -,';
            $nestedData['pembayaran_ke'] = $transaksipinjaman->pembayaran_ke;
            $nestedData['acc'] = $transaksipinjaman->acc;
            $nestedData['status_pembayaran'] = $transaksipinjaman->status_pembayaran;
            
            $data[] = $nestedData;

        }
        $output = array(
            "draw"            => intval($this->request->getpost('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
        );
        return $this->response->setJSON($output);
    }

    public function bayar($id)
    {
        $data=[
            'title'     => 'Data Pembayaran Angsuran Anggota',
            'apps'      => 'KOPSIP .::. Data Pembayaran Angsuran Anggota',
            'profil'    => $this->session->get('user'),
            'pinjam'    => $this->transaksipinjamanmodel->gettranspinjambyid($id)
        ];
        $data['menus']  = $this->menus->getmenus($this->sess_id);
        $data['css_files'][] = base_url('template/vendors/DataTables/datatables.min.css');
        $data['js_files'][] = base_url('template/vendors/DataTables/datatables.min.js');
        $data['js_files'][] = base_url('template/vendors/jquery-validation/dist/jquery.validate.min.js');
        return render('admin/transaksi/bayar',$data);
    }

    public function savepembayaran()
    {
        $idpinjam = $this->request->getPost('idpinjam');
        $no_telp = $this->request->getPost('no_telp');
        $pembayaran_ke = $this->request->getPost('pembayaran_ke');
        $tgl_angsuran = date('Y-m-d');
        $jml_bayar = $this->request->getPost('jml_bayar');
        $sisa_jml_bayar = $this->request->getPost('sisa_jml_bayar')-$jml_bayar;
        $status_pembayaran = $this->request->getPost('status_pembayaran');
        $data=array();
        $data = array(
            'idpinjam'         => $idpinjam,
            'tgl_angsuran'     => $tgl_angsuran,
            'jml_bayar'        => $jml_bayar,
            'sisa_jml_bayar'   => $sisa_jml_bayar
        );
        $data1=array();
        $data1 = array(
            'pembayaran_ke' => $pembayaran_ke,
            'nominal_pinjam'=> $sisa_jml_bayar,
            'status_pembayaran' => $status_pembayaran
        );
        $data2=array();
        $data2 = array(
            'DestinationNumber' => $no_telp,
            'TextDecoded'   => 'Anda telah membayar Angsuran Ke-"'.$pembayaran_ke.'" dengan jumlah nominal "'.$jml_bayar.'" Sisa Angsuran Anda adalah sebesar "'.$sisa_jml_bayar.'" pada tanggal "'.$tgl_angsuran.'"',
            'CreatorID' => 'Gammu'
        );
        $this->outboxmodel->insertOutbox($data2);
        $this->angsuranmodel->insertAngsuran($data);
        $result = $this->transaksipinjamanmodel->updateTransaksipinjam($idpinjam,$data1);
        if ($result){
            echo json_encode(array('message'=>'Save Success'));
        } else {
            echo json_encode(array('errorMsg'=>'Some errors occured.'));
        }
    }

    public function simpanan()
    {
      $data=[
        'title'     => 'Data Simpanan Anggota',
        'apps'      => 'KOPSIPJAM .::. Data Simpanan Anggota',
        'profil'    => $this->session->get('user')
      ];
      $data['menus']  = $this->menus->getmenus($this->sess_id);
      $data['css_files'][] = base_url('template/vendors/DataTables/datatables.min.css');
      $data['css_files'][] = "https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.css";
      $data['js_files'][] = base_url('template/vendors/DataTables/datatables.min.js');
      $data['js_files'][] = "https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.js";
      return render('admin/simpanan/simpanan', $data);
    }

    public function addsimpanan($id)
    {
      $data=[
        'title'     => 'Input Simpanan Anggota',
        'apps'      => 'KOPSIPJAM .::. Input Simpanan Anggota',
        'profil'    => $this->session->get('user'),
        'simpanan'  => $this->anggotamodel->getanggotabyid($id)
      ];
      $data['menus']  = $this->menus->getmenus($this->sess_id);
      $data['css_files'][] = base_url('template/vendors/DataTables/datatables.min.css');
      $data['js_files'][] = base_url('template/vendors/DataTables/datatables.min.js');
      $data['css_files'][] = base_url('template/vendors/select2/dist/css/select2.css');
      $data['js_files'][] = base_url('template/vendors/select2/dist/js/select2.full.min.js');
      $data['js_files'][] = base_url('template/vendors/jquery-validation/dist/jquery.validate.min.js');
      return render('admin/simpanan/addsimpanan', $data);
    }

    public function savesimpanan()
    {
        $idanggota = $this->request->getPost('idanggota');
        $tgl_pembayaran = date('Y-m-d');
        $pokok = $this->request->getPost('pokok');
        $wajib  = $this->request->getPost('wajib');
        $sukarela  = $this->request->getPost('sukarela');
        $status_simpanan  = $this->request->getPost('status_simpanan');

        $jml_pkk = $this->simpananmodel->getsimpananbyid($idanggota)['jml_pokok'];
        $jml_wjb = $this->simpananmodel->getsimpananbyid($idanggota)['jml_wajib'];
        $jml_skrl = $this->simpananmodel->getsimpananbyid($idanggota)['jml_sukarela'];
        $phone = $this->anggotamodel->getanggotabyid($idanggota)['no_telp'];

        $tot_pokok = ((float)$jml_pkk+(float)$pokok);
        $tot_wajib = ((float)$jml_wjb+(float)$wajib);
        $tot_sukarela = ((float)$jml_skrl+(float)$sukarela);
        
        $data=array();
        $data = array(
            'idanggota'       => $idanggota,
            'tgl_pembayaran'  => $tgl_pembayaran,
            'pokok'           => $pokok,
            'wajib'           => $wajib,
            'sukarela'        => $sukarela,
            'status_simpanan' => $status_simpanan,
        );
        if ($pokok != '') {
            $data2=array();
            $data2 = array(
                'DestinationNumber' => $phone,
                'TextDecoded'   => 'Anda telah membayar Simpanan Pokok dengan jumlah nominal "'.number_format($pokok, 0).'" Jumlah Simpanan Wajib anda Sebesar "'.number_format($jml_wjb, 0).'" Simpanan Pokok Sebesar "'.number_format($tot_pokok, 0).'" dan Simpanan Sukarela Anda Sebesar "'.number_format($jml_skrl, 0).'"',
                'CreatorID' => 'Gammu'
            );
        }else if ($wajib != '') {
            $data2=array();
            $data2 = array(
                'DestinationNumber' => $phone,
                'TextDecoded'   => 'Anda telah membayar Simpanan Sukarela dengan jumlah nominal "'.number_format($sukarela, 0).'" Jumlah Simpanan Wajib anda Sebesar "'.number_format($jml_wajib, 0).'" Simpanan Pokok Sebesar "'.number_format($jml_pkk, 0).'" dan Simpanan Sukarela Anda Sebesar "'.number_format($tot_sukarela, 0).'"',
                'CreatorID' => 'Gammu'
            );
        }else{
            $data2=array();
            $data2 = array(
                'DestinationNumber' => $phone,
                'TextDecoded'   => 'Anda telah membayar Simpanan Wajib dengan jumlah nominal "'.number_format($wajib, 0).'" Jumlah Simpanan Wajib anda Sebesar "'.number_format($jml_wjb, 0).'" Simpanan Pokok Sebesar "'.number_format($jml_pkk, 0).'" dan Simpanan Sukarela Anda Sebesar "'.number_format($jml_skrl, 0).'"',
                'CreatorID' => 'Gammu'
            );
        }
        $this->outboxmodel->insertOutbox($data2);
        $result = $this->simpananmodel->insertSimpanan($data);
        if ($result){
            echo json_encode(array('message'=>'Update Success'));
        } else {
            echo json_encode(array('errorMsg'=>'Some errors occured.'));
        }
    }

    public function laporansimpanan()
    {
        $data=[
            'title'     => 'LAPORAN SIMPANAN',
            'apps'      => 'KOPERASI ..::.. Laporan Simpanan',
            'profil'    => $this->session->get('user')
        ];
        $data['menus']  = $this->menus->getmenus($this->sess_id);
        $data['css_files'][] = base_url('template/easyui/themes/metro/easyui.css');
        $data['css_files'][] = base_url('template/plugins/bootstrap_date_time/css/bootstrap-datetimepicker.min.css');
        $data['css_files'][] = base_url('template/plugins/daterangepicker/daterangepicker-bs3.css');
        $data['css_files'][] = base_url('template/easyui/themes/icon.css');
        $data['css_files'][] = base_url('template/easyui/texteditor.css');
        $data['js_files'][] = base_url('template/easyui/jquery.easyui.min.js');
        $data['js_files'][] = base_url('template/easyui/datagrid-groupview.js');
        $data['js_files'][] = base_url('template/easyui/jquery.texteditor.js');
        $data['js_files'][] = base_url('template/easyui/plugins/datagrid-scrollview.js');
        $data['js_files'][] = base_url('template/plugins/bootstrap_date_time/js/bootstrap-datetimepicker.min.js');
        $data['js_files'][] = base_url('template/plugins/bootstrap_date_time/js/locales/bootstrap-datetimepicker.id.js');
        $data['js_files'][] = base_url('template/easyui/moment.js');
        $data['js_files'][] = base_url('template/plugins/daterangepicker/daterangepicker.js');
        $data['js_files'][] = base_url('template/js/laporansimpanan.js');
        return render('admin/laporan/laporansimpanan',$data);
    }

    public function getlaporansimpanan()
    {
        $data=$this->simpananmodel->getlaporansimpanan();
        $result = $this->response->setJSON($data);
        return $result;
    }

    public function laporansimpananpdf()
    {
        $start = $this->request->getGet('start');
        $end = $this->request->getGet('end');
        $result = $this->simpananmodel->getlaporanpdf($start,$end);

        $html = view('admin/laporan/cetaklaporansimpanan',[
          'start' => $start,
          'end' => $end,
          'lap'=> $result
        ]);
    
        $pdf = new TCPDF('P', PDF_UNIT, 'F4', true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        // $pdf->SetAuthor('Dea Venditama');
        $pdf->SetTitle('REKAP DATA DAFTAR SIMPANAN AKHIR ');
        $pdf->SetSubject('N');

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->addPage();
        // output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');
        //line ini penting
        $this->response->setContentType('application/pdf');
        //Close and output PDF document
        $pdf->Output('laporan.pdf', 'I');
    }

    public function laporanpinjaman()
    {
        $data=[
            'title'     => 'LAPORAN PINJAMAN',
            'apps'      => 'KOPERASI ..::.. Laporan Pinjaman',
            'profil'    => $this->session->get('user')
        ];
        $data['menus']  = $this->menus->getmenus($this->sess_id);
        $data['css_files'][] = base_url('template/easyui/themes/metro/easyui.css');
        $data['css_files'][] = base_url('template/plugins/bootstrap_date_time/css/bootstrap-datetimepicker.min.css');
        $data['css_files'][] = base_url('template/plugins/daterangepicker/daterangepicker-bs3.css');
        $data['css_files'][] = base_url('template/easyui/themes/icon.css');
        $data['css_files'][] = base_url('template/easyui/texteditor.css');
        $data['js_files'][] = base_url('template/easyui/jquery.easyui.min.js');
        $data['js_files'][] = base_url('template/easyui/datagrid-groupview.js');
        $data['js_files'][] = base_url('template/easyui/jquery.texteditor.js');
        $data['js_files'][] = base_url('template/easyui/plugins/datagrid-scrollview.js');
        $data['js_files'][] = base_url('template/plugins/bootstrap_date_time/js/bootstrap-datetimepicker.min.js');
        $data['js_files'][] = base_url('template/plugins/bootstrap_date_time/js/locales/bootstrap-datetimepicker.id.js');
        $data['js_files'][] = base_url('template/easyui/moment.js');
        $data['js_files'][] = base_url('template/plugins/daterangepicker/daterangepicker.js');
        $data['js_files'][] = base_url('template/js/laporanpinjaman.js');
        return render('admin/laporan/laporanpinjaman',$data);
    }

    public function getlaporanpinjaman()
    {
        $data=$this->angsuranmodel->getlaporanpinjaman();
        $result = $this->response->setJSON($data);
        return $result;
    }

    public function laporanpinjamanpdf()
    {
        $start = $this->request->getGet('start');
        $end = $this->request->getGet('end');
        $result = $this->angsuranmodel->getlaporanpinjamanpdf($start,$end);

        $html = view('admin/laporan/cetaklaporanpinjaman',[
          'start' => $start,
          'end' => $end,
          'lap'=> $result
        ]);
    
        $pdf = new TCPDF('P', PDF_UNIT, 'F4', true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        // $pdf->SetAuthor('Dea Venditama');
        $pdf->SetTitle('REKAP DATA DAFTAR PINJAMAN AKHIR ');
        $pdf->SetSubject('');

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->addPage();
        // output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');
        //line ini penting
        $this->response->setContentType('application/pdf');
        //Close and output PDF document
        $pdf->Output('laporan.pdf', 'I');
    }

    public function laporanangsuran()
    {
        $data=[
            'title'     => 'LAPORAN DATA ANGSURAN',
            'apps'      => 'KOPERASI ..::.. Laporan Data Angsuran',
            'profil'    => $this->session->get('user')
        ];
        $data['menus']  = $this->menus->getmenus($this->sess_id);
        $data['css_files'][] = base_url('template/easyui/themes/metro/easyui.css');
        $data['css_files'][] = base_url('template/plugins/bootstrap_date_time/css/bootstrap-datetimepicker.min.css');
        $data['css_files'][] = base_url('template/plugins/daterangepicker/daterangepicker-bs3.css');
        $data['css_files'][] = base_url('template/easyui/themes/icon.css');
        $data['css_files'][] = base_url('template/easyui/texteditor.css');
        $data['js_files'][] = base_url('template/easyui/jquery.easyui.min.js');
        $data['js_files'][] = base_url('template/easyui/datagrid-groupview.js');
        $data['js_files'][] = base_url('template/easyui/jquery.texteditor.js');
        $data['js_files'][] = base_url('template/easyui/plugins/datagrid-scrollview.js');
        $data['js_files'][] = base_url('template/plugins/bootstrap_date_time/js/bootstrap-datetimepicker.min.js');
        $data['js_files'][] = base_url('template/plugins/bootstrap_date_time/js/locales/bootstrap-datetimepicker.id.js');
        $data['js_files'][] = base_url('template/easyui/moment.js');
        $data['js_files'][] = base_url('template/plugins/daterangepicker/daterangepicker.js');
        $data['js_files'][] = base_url('template/js/laporanangsuran.js');
        return render('admin/laporan/laporanangsuran',$data);
    }

    public function getlaporanangsuran()
    {
        $data=$this->angsuranmodel->getlaporanangsuran();
        $result = $this->response->setJSON($data);
        return $result;
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/');
    }
}