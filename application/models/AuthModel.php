<?php defined('BASEPATH') OR exit('No direct script access allowed');

class AuthModel extends CI_Model
{
  protected $_system_accounts = "system_accounts";
    public function checkAccount($username,$password)
    {
        $where = array(
                        'username' => $username,
                        'password' => $password
                       );
        $this->db->where($where);
        $result = $this->db->get($this->_system_accounts);

        return $result->num_rows();
    }

    public function createAccount($name,$username,$password,$user_level,$email,$position='null',$c_number='null')
    {
        $data = array(
                        'name' => $name,
                        'username' => $username,
                        'password' => $password,
                        'email' => $email,
                        'position' => $position,
                        'c_number' => $c_number,
                        'user_level' => $user_level
                      );
        $this->db->insert($this->_system_accounts,$data);

        return $this->db->affected_rows();
    }

    public function checkUsername($username)
    {
        $this->db->where('username',$username);
        $response = $this->db->get($this->_system_accounts);

        return $response->num_rows();
    }

    public function updateStatus($id,$status)
    {
        $data = array(
                        'account_status' => $status
                      );
        $this->db->where('account_id',$id);
        $this->db->update($this->_system_accounts,$data);

        return $this->db->affected_rows();
    }

    public function getAccountInfo($username)
    {
        $this->db->where('username',$username);
        $response = $this->db->get($this->_system_accounts);

        return $response->row();
    }

    public function getAccountList($user_level)
    {
        $where = array(
                        'user_level' => $user_level
                       );
        $this->db->where($where);
        $response = $this->db->get($this->_system_accounts);

        return $response;
    }

    public function getAccountingList()
    {
        $where = array(
                        'user_level' => 2
                       );
        $this->db->where($where);

        $response =  $this->db->get($this->_system_accounts);

        return $response;
    }

    public function checkAdministrator()
    {
      $where =array(
        'user_level' => 0
      );
      $this->db->where($where);
      $response = $this->db->get($this->_system_accounts);

      return $response->num_rows();
    }
}

?>
