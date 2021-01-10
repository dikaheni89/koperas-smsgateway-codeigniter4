<?php namespace App\Controllers;
 
use App\Models\Auth_model;
use CodeIgniter\API\ResponseTrait;

class Auth extends BaseController
{
    use ResponseTrait;
	public function __construct()
    {
    	$this->session = \Config\Services::session();
        $this->security = \Config\Services::security();
        $this->auth = new Auth_model();
        $this->session->start();
    }


    public function check()
    {
    	$username = service('request')->getPost('username');
        $p = service('request')->getPost('password');

        $signin = $this->auth->chekLogin($username);
        $data=array();
        if (count($signin->getResultArray()) > 0){
            $found=$signin->getRowArray();
            if(password_verify($p,$found['password'])){
                $sess_data = array('login' => TRUE, 'user' => $found, 'uid'=>$found['_id']);
                $this->session->set($sess_data);
                $data[] = array(
                    'value'   => 1,
                    'message' => 'Termakasih, Anda Berhasil Login.', 
                );
            }else{
                $data[] = array(
                    'value'   => 0,
                    'message' => 'Password anda salah.',
                );
            }
        }else{
            $data[] = array(
                'value'   => 0,
                'message' => 'Email atau Username anda salah.',
                // 'token'    => $this->security->get_csrf_hash(), 
            );
        }
        $result = $this->respond($data);
        return $result;
    }
}