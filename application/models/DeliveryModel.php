<?php defined('BASEPATH') OR exit('No direct script access allowed');

class DeliveryModel extends CI_Model
{
    protected $_delivery_request = 'delivery_request';
    protected $_sales_invoice = 'sales_invoice';
    protected $_sales_order = 'sales_order';
    protected $_reg_products = 'reg_products';
    protected $_products = 'products';
    protected $_customers = 'customers';
    protected $_notification = 'notification';
    
    public function insert($sales_order_id,$cactual_delivery_date,$message,$account_id)
    {
        $data = array(
                        'sales_order_id' => $sales_order_id,
                        'actual_delivery_date' => $cactual_delivery_date,
                        'message' => $message,
                        'approved_by' => $account_id
                      );
        
        $this->db->insert($this->_delivery_request,$data);
        
        return $this->db->affected_rows();
    }
    
    public function fetch_delivery($tbName,$start,$limit,$search,$type_ordering,$account_id,$user_level)
    {
        if($user_level==2 || $user_level==4):
            $where = array(
                            $this->_sales_order.'.status' => 2,
                            $this->_sales_order.'.account_id' => $account_id
                           );
        else:
            $where = array(
                            $this->_sales_order.'.status' => 2,
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
                            $this->_sales_order.'.status' => 2,
                            $this->_sales_order.'.account_id' => $account_id
                           );
        else:
            $where = array(
                            $this->_sales_order.'.status' => 2,
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
                        'status' => 3
                     );
        $this->db->where($where);
        $this->db->update($this->_sales_order,$data);
        
        return $this->db->affected_rows();
    }
    
    
    public function getSalesOrderInformation($sales_order_id)
    {
        $where = array(
                        $this->_sales_order.'.sales_order_id' => $sales_order_id
                       );
        
        $this->db->where($where);
        $this->db->select($this->_sales_order.'.*,'.$this->_customers.'.customer_name,'.$this->_customers.'.contact_no,'.
                          $this->_customers.'.tin,'.$this->_sales_invoice.'.invoice_no,'.$this->_sales_invoice.'.invoice_date,'.
                          $this->_sales_invoice.'.message');
        $this->db->from($this->_sales_order);
        $this->db->join($this->_customers,$this->_customers.'.customers_id='.$this->_sales_order.'.customers_id','LEFT');
        $this->db->join($this->_sales_invoice,$this->_sales_invoice.'.sales_order_id='.$this->_sales_order.'.sales_order_id','LEFT');
        $response = $this->db->get();
        
        return $response;
    }
    
    public function getRegProducts($sales_order_id)
    {
        $where = array(
                        $this->_reg_products.'.sales_order_id' => $sales_order_id
                       );
        
        $this->db->where($where);
        $this->db->select($this->_reg_products.'.*,'.$this->_products.'.product_name,'.$this->_products.'.description');
        $this->db->from($this->_reg_products);
        $this->db->join($this->_products,$this->_products.'.product_id='.$this->_reg_products.'.product_id','LEFT');
        $response = $this->db->get();
        
        return $response;
    }
    
    public function checkDeliveryRecord($sales_order_id)
    {
        $where = array(
                        'sales_order_id' => $sales_order_id
                       );
        $this->db->where($where);
        $response = $this->db->get($this->_delivery_request);
        
        return $response->num_rows();
    }
}


?>