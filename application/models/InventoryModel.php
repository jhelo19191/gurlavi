<?php defined('BASEPATH') OR exit('No direct script access allowed');

class InventoryModel extends CI_Model
{
    protected $_inventory = 'inventory';
    protected $_sales_order = 'sales_order';
    protected $_reg_products = 'reg_products';
    protected $_products = 'products';
    protected $_customers = 'customers';
    protected $_sales_invoice = 'sales_invoice';
    protected $_notification = 'notification';
    
    public function insert($sales_order_id,$carrier_name,$position,$message,$account_id)
    {
        $data = array(
                        'sales_order_id' => $sales_order_id,
                        'carrier_name' => $carrier_name,
                        'position' => $position,
                        'message' => $message,
                        'approved_by' => $account_id
                      );
        
        $this->db->insert($this->_inventory,$data);
        
        return $this->db->affected_rows();
    }
    
    public function fetch_inventory($tbName,$start,$limit,$search,$type_ordering,$account_id,$user_level)
    {
        if($user_level==2 || $user_level==4):
            $where = array(
                            $this->_sales_order.'.status' => 1,
                            $this->_sales_order.'.account_id' => $account_id
                           );
        else:
            $where = array(
                            $this->_sales_order.'.status' => 1,
                           );
        endif;
        
        $this->db->select($this->_sales_order.'.*,'.$this->_customers.'.customer_name,'.$this->_sales_invoice.'.invoice_no,'.$this->_sales_invoice.'.invoice_date');
        $this->db->from($this->_sales_order);
        $this->db->join($this->_customers,$this->_customers.'.customers_id='.$this->_sales_order.'.customers_id','LEFT');
        $this->db->join($this->_sales_invoice,$this->_sales_invoice.'.sales_order_id='.$this->_sales_order.'.sales_order_id','LEFT');
        
       
        $this->db->where($where);
        $this->db->where('(`sales_order_no` LIKE \'%'.$search.'%\' OR `psr_name` LIKE \'%'.$search.'%\' OR `invoice_no` LIKE \'%'.$search.'%\' OR `customer_name` LIKE \'%'.$search.'%\')', NULL, FALSE);
        $this->db->order_by($this->_sales_order.'.sales_order_id', $type_ordering);
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
    

    public function countSalesOrder($search,$account_id,$user_level)
    {
        //$this->db->or_like('sales_order_no',$search);
        
        if($user_level==2 || $user_level==4):
            $where = array(
                            $this->_sales_order.'.status' => 1,
                            $this->_sales_order.'.account_id' => $account_id
                           );
        else:
            $where = array(
                            $this->_sales_order.'.status' => 1,
                           );
        endif;
        
        $this->db->select($this->_sales_order.'.*,'.$this->_customers.'.customer_name,'.$this->_sales_invoice.'.invoice_no,'.$this->_sales_invoice.'.invoice_date');
        $this->db->from($this->_sales_order);
        $this->db->join($this->_customers,$this->_customers.'.customers_id='.$this->_sales_order.'.customers_id','LEFT');
        $this->db->join($this->_sales_invoice,$this->_sales_invoice.'.sales_order_id='.$this->_sales_order.'.sales_order_id','LEFT');
        
        $this->db->where($where);
        $this->db->where('(`sales_order_no` LIKE \'%'.$search.'%\' OR `psr_name` LIKE \'%'.$search.'%\' OR `invoice_no` LIKE \'%'.$search.'%\' OR `customer_name` LIKE \'%'.$search.'%\')', NULL, FALSE);
        
        $response = $this->db->get();
        
        return $response->num_rows();
    }
    
    public function updateSalesOrderStatus($sales_order_id)
    {
        $where = array(
                        'sales_order_id' => $sales_order_id
                       );
        $data =array(
                        'status' => 2
                     );
        $this->db->where($where);
        $this->db->update($this->_sales_order,$data);
        
        return $this->db->affected_rows();
    }
    
    public function checkInvetoryRecord($sales_order_id)
    {
        $where =array(
                        'sales_order_id' => $sales_order_id
                      );
        $this->db->where($where);
        $response = $this->db->get($this->_inventory);
        
        return $response->num_rows();
    }
}


?>