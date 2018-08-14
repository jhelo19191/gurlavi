<?php defined('BASEPATH') OR exit('No direct script access allowed');

class NotificationModel extends CI_Model
{
    protected $_notification = 'notification';
    protected $_payment_comments = 'payment_comments';
    protected $_sales_order = 'sales_order';
    protected $_system_accounts = 'system_accounts';
    
    public function getNotification()
    {
        $this->db->order_by('notification_id','DESC');
        return $this->db->get($this->_notification);
    }
    
    public function countNotification()
    {
        $where = array(
                        'status' => 0
                       );
        $this->db->where($where);
        $this->db->order_by('status ASC, created_date DESC');
        $response = $this->db->get($this->_notification);
        
        return $response->num_rows();
    }
    
    public function updateStatus($sales_order_id)
    {
        $where =array(
                        'sales_order_id' => $sales_order_id
                      );
        
        $data = array(
                        'status' => 1
                       );
        $this->db->where($where);
        $this->db->update($this->_notification,$data);
        
        return $this->db->affected_rows();
    }
    
    public function paymentComment($payment_id,$payment_type)
    {
        $where = array(
                        $this->_payment_comments.'.payment_id' => $payment_id,
                        $this->_payment_comments.'.payment_type' => $payment_type
                       );
        $this->db->where($where);
        $this->db->select($this->_payment_comments.'.*,'.$this->_system_accounts.'.name,'.$this->_system_accounts.'.account_status');
        $this->db->from($this->_payment_comments);
        $this->db->join($this->_system_accounts,$this->_system_accounts.'.account_id='.$this->_payment_comments.'.sender_id','LEFT');
        $this->db->order_by('created_date','DESC');
        $response = $this->db->get();
        
        return $response;
    }
    
    public function getAllRejectMessage($account_id)
    {
        $where = array(
                        $this->_payment_comments.'.receiver_id' => $account_id
                       );
        
        $this->db->where($where);
        $this->db->select($this->_payment_comments.'.*,'.$this->_system_accounts.'.name,'.$this->_system_accounts.'.account_status');
        $this->db->from($this->_payment_comments);
        $this->db->join($this->_system_accounts,$this->_system_accounts.'.account_id='.$this->_payment_comments.'.sender_id','LEFT');
        $this->db->order_by('flag ASC, created_date DESC');
        $this->db->limit(10,0);
        
        $response = $this->db->get();
        
        return $response;
    }
    
    public function countActiveMessage($account_id)
    {
        $where = array(
                        'receiver_id' => $account_id,
                        'flag' => 0
                       );
        $this->db->where($where);
        
        $response = $this->db->get($this->_payment_comments);
        
        return $response->num_rows();
    }
}

?>