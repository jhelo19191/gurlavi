<?php defined('BASEPATH') OR exit('No direct script access allowed');

class OnholdModel extends CI_Model
{
    protected $_onhold = 'onhold';
    protected $_system_accounts = 'system_accounts';
    protected $_pdc_info = 'pdc_info';
    protected $_sales_order = 'sales_order';
    
    public function insert($receiver_name,$payment_type,$amount,$created_by)
    {
        $data = array(
                        'receiver_id' => $receiver_name,
                        'payment_type' => $payment_type,
                        'amount' => $amount,
                        'created_by' => $created_by
                        
                      );
        $this->db->set('updated_date','NOW()',false);
        $this->db->insert($this->_onhold,$data);
        
        return $this->db->affected_rows();
    }
    
    public function fetch_payments($tbName,$start,$limit,$search,$type_ordering,$account_id,$user_level)
    {
        if($user_level==4 || $user_level==3):
            $where = array(
                            'receiver_id' => $account_id
                           );
            $this->db->where($where);
        endif;
        
        $this->db->select($this->_onhold.'.*,'.$this->_system_accounts.'.name');
        $this->db->from($this->_onhold);
        $this->db->where('(`name` LIKE \'%'.$search.'%\')', NULL, FALSE);
        $this->db->order_by($this->_onhold.'.created_date', $type_ordering);
        $this->db->join($this->_system_accounts,$this->_system_accounts.'.account_id='.$this->_onhold.'.receiver_id','LEFT');
        $this->db->limit($limit,$start);
        
        $query = $this->db->get();
        
        if($query->num_rows() > 0)
        {
            foreach($query->result() as $row)
            {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }
    

    public function countHoldedPayment($search,$account_id,$user_level)
    {
        //$this->db->or_like('sales_order_no',$search);
        
        if($user_level==4 || $user_level==3):
            $where = array(
                            'receiver_id' => $account_id
                           );
            $this->db->where($where);
        endif;
        
        $this->db->select($this->_onhold.'.*,'.$this->_system_accounts.'.name');
        $this->db->from($this->_onhold);
        $this->db->where('(`name` LIKE \'%'.$search.'%\')', NULL, FALSE);
        $this->db->join($this->_system_accounts,$this->_system_accounts.'.account_id='.$this->_onhold.'.receiver_id','LEFT');
        
        $response = $this->db->get();
        
        return $response->num_rows();
    }
    
    public function getAccountList()
    {
        $response = $this->db->get($this->_system_accounts);
        
        return $response;
    }
    
    public function getOnHoldDetails($transaction_id)
    {
        $where = array(
                        'transaction_id' => $transaction_id
                       );
        $this->db->where($where);
        $response = $this->db->get($this->_onhold);
        
        return $response;
    }
    
    public function update($transaction_id,$receiver_name,$payment_type,$amount,$created_by)
    {
        $where = array(
                        'transaction_id' => $transaction_id
                       );
        $data = array(
                        'receiver_id' => $receiver_name,
                        'payment_type' => $payment_type,
                        'amount' => $amount,
                        'created_by' => $created_by
                        
                      );
        $this->db->where($where);
        $this->db->set('updated_date','NOW()',false);
        $this->db->update($this->_onhold,$data);
        
        return $this->db->affected_rows();
    }
    
    public function remove($transaction_id)
    {
        $where = array(
                        'transaction_id' => $transaction_id
                       );
        $this->db->where($where);
        $this->db->delete($this->_onhold);
        
        return $this->db->affected_rows();
    }
}


?>