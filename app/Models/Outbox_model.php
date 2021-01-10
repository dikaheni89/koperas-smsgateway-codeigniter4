<?php namespace App\Models;

use CodeIgniter\Model;

/**
* 
*/
class Outbox_model extends Model
{
	protected $table = 'outbox';
    protected $primaryKey   = 'ID';
    protected $allowedFields = ['DestinationNumber', 'TextDecoded', 'CreatorID']; 

    public function insertOutbox($data)
    {
        $query = $this->table($this->table)->insert($data);
        return $query ? true : false;
    }
}