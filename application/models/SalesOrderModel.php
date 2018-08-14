<?php defined('BASEPATH') OR exit('No direct script access allowed');

class SalesOrderModel extends CI_Model
{
    protected $_sales_order = 'sales_order';
    protected $_reg_products = 'reg_products';
    protected $_products = 'products';
    protected $_customers = 'customers';
    protected $_notification = 'notification';
    protected $_invoice = 'sales_invoice';
    protected $_delivery_request = 'delivery_request';
    protected $_collection = 'collection';
    protected $_remittance = 'remittance';
    protected $_inventory = 'inventory';
    protected $_system_accounts = 'system_accounts';
    protected $_images = 'images';
  
    public function insert($so_number,$si_number,$so_date,$approved_date,$psr_name,
                           $customer_name,$delivery_date,$account_id,$shipto,$comments="Null")
    {
        $data = array(
            'sales_order_no' => $so_number,
            'psr_name' => $psr_name,
            'customers_id' => $customer_name,
            'si_number' => $si_number,
            'approved_date' => $approved_date,
            'delivery_date' => $delivery_date,
            'so_date' => $so_date,
            'account_id' => $account_id,
            'ship_to' => $shipto,
            'comments' => $comments
          );
  
        $this->db->insert($this->_sales_order,$data);
  
        return $this->db->insert_id();
    }
    
    public function checkSalesOrderNo($sales_order_no)
    {
        $where = array(
                        'sales_order_no' => $sales_order_no
                       );
        $this->db->where($where);
        $response = $this->db->get($this->_sales_order);
        
        return $response->num_rows();
    }
    
    public function insertRegProducts($sales_order_id,$product_id,$quantity,$unit,$price)
    {
        $data = array(
                        'sales_order_id' => $sales_order_id,
                        'product_id' => $product_id,
                        'quantity' => $quantity,
                        'unit' => $unit,
                        'price' => $price
                      );
        $this->db->insert($this->_reg_products,$data);
        
        return $this->db->affected_rows();
    }
    
    public function removeSalesOrder($sales_order_id)
    {
        $where =array(
                        'sales_order_id' => $sales_order_id
                      );
        $this->db->where($where);
        $this->db->delete($this->_sales_order);
        
        return $this->db->affected_rows();
    }
    
    public function countSalesOrder($search,$level,$dstart,$end,$account_id,$user_level)
    {
        if($dstart!=''):
            switch($level)
            {
                case '0' : $where = array(
                            $this->_sales_order.'.so_date >=' => $dstart,
                            $this->_sales_order.'.so_date <=' => $end
                           );
                        break;
                case '1' : $where = array(
                            $this->_invoice.'.invoice_date >=' => $dstart,
                            $this->_invoice.'.invoice_date <=' => $end
                           );
                        break;
                case '2' : $where = array(
                            $this->_delivery_request.'.actual_delivery_date >=' => $dstart,
                            $this->_delivery_request.'.actual_delivery_date <=' => $end
                           );
                        break;
                case '4' : $where = array(
                            $this->_collection.'.collect_date >=' => $dstart,
                            $this->_collection.'.collect_date <=' => $end
                           );
                        break;
                case '6' : $where = array(
                            $this->_remittance.'.remittance_date >=' => $dstart,
                            $this->_remittance.'.remittance_date <=' => $end
                           );
                        break;
                case '9' : $where = array(
                            $this->_sales_order.'.so_date >=' => $dstart,
                            $this->_sales_order.'.so_date <=' => $end,
                            $this->_sales_order.'.status' => 9
                           );
                        break;
                    default: $where = array(
                            $this->_sales_order.'.so_date >=' => $dstart,
                            $this->_sales_order.'.so_date <=' => $end
                           );
                        break;
            }
            
            if($user_level==4 || $user_level==3):
                $xwhere = array(
                                    $this->_sales_order.'.account_id' => $account_id
                                );
                $where = array_merge($where,$xwhere);
            endif;
            
            $this->db->where($where);
        endif;
        
        $this->db->select($this->_sales_order.'.*,'.$this->_customers.'.customer_name,'.$this->_invoice.'.invoice_no');
        $this->db->from($this->_sales_order);
        $this->db->join($this->_customers,$this->_customers.'.customers_id='.$this->_sales_order.'.customers_id','LEFT');
        
        
        $this->db->join($this->_invoice,$this->_invoice.'.sales_order_id='.$this->_sales_order.'.sales_order_id','LEFT');
        $this->db->join($this->_delivery_request,$this->_delivery_request.'.sales_order_id='.$this->_sales_order.'.sales_order_id','LEFT');
        $this->db->join($this->_collection,$this->_collection.'.sales_order_id='.$this->_sales_order.'.sales_order_id','LEFT');
        $this->db->join($this->_remittance,$this->_remittance.'.sales_order_id='.$this->_sales_order.'.sales_order_id','LEFT');
       
        
        $this->db->where('(`sales_order_no` LIKE \'%'.$search.'%\' OR `invoice_no` LIKE \'%'.$search.'%\' OR `psr_name` LIKE \'%'.$search.'%\' OR `customer_name` LIKE \'%'.$search.'%\')', NULL, FALSE);
        
        $response = $this->db->get();
        
        return $response->num_rows();
    }
    
    public function fetch_sales_order($tbName,$start,$limit,$search,$type_ordering,$level,$dstart,$end,$account_id,$user_level)
    {
        $this->db->limit($limit,$start);
        $this->db->order_by($this->_sales_order.'.sales_order_id', $type_ordering);
        
       
        if($dstart!=''):
            switch($level)
            {
                case '0' : $where = array(
                            $this->_sales_order.'.so_date >=' => $dstart,
                            $this->_sales_order.'.so_date <=' => $end
                           );
                        break;
                case '1' : $where = array(
                            $this->_invoice.'.invoice_date >=' => $dstart,
                            $this->_invoice.'.invoice_date <=' => $end
                           );
                        break;
                case '2' : $where = array(
                            $this->_delivery_request.'.actual_delivery_date >=' => $dstart,
                            $this->_delivery_request.'.actual_delivery_date <=' => $end
                           );
                        break;
                case '4' : $where = array(
                            $this->_collection.'.collect_date >=' => $dstart,
                            $this->_collection.'.collect_date <=' => $end
                           );
                        break;
                case '6' : $where = array(
                            $this->_remittance.'.remittance_date >=' => $dstart,
                            $this->_remittance.'.remittance_date <=' => $end
                           );
                        break;
                case '9' : $where = array(
                            $this->_sales_order.'.so_date >=' => $dstart,
                            $this->_sales_order.'.so_date <=' => $end,
                            $this->_sales_order.'.status' => 9
                           );
                        break;
                    default: $where = array(
                            $this->_sales_order.'.so_date >=' => $dstart,
                            $this->_sales_order.'.so_date <=' => $end
                           );
                        break;
            }
            
            if($user_level==4 || $user_level==3):
                $xwhere = array(
                                    $this->_sales_order.'.account_id' => $account_id
                                );
                $where = array_merge($where,$xwhere);
            endif;
            
            $this->db->where($where);
        endif;
        
        $this->db->select($this->_sales_order.'.*,'.$this->_customers.'.customer_name,'.$this->_invoice.'.invoice_no,'.$this->_collection.'.cr_no,'.$this->_remittance.'.remittance_no');
        $this->db->from($this->_sales_order);
        $this->db->join($this->_customers,$this->_customers.'.customers_id='.$this->_sales_order.'.customers_id','LEFT');
        
        $this->db->join($this->_invoice,$this->_invoice.'.sales_order_id='.$this->_sales_order.'.sales_order_id','LEFT');
        $this->db->join($this->_delivery_request,$this->_delivery_request.'.sales_order_id='.$this->_sales_order.'.sales_order_id','LEFT');
        $this->db->join($this->_collection,$this->_collection.'.sales_order_id='.$this->_sales_order.'.sales_order_id','LEFT');
        $this->db->join($this->_remittance,$this->_remittance.'.sales_order_id='.$this->_sales_order.'.sales_order_id','LEFT');
       
        $this->db->where('(`sales_order_no` LIKE \'%'.$search.'%\' OR `invoice_no` LIKE \'%'.$search.'%\'  OR `cr_no` LIKE \'%'.$search.'%\' OR `remittance_no` LIKE \'%'.$search.'%\'  OR `psr_name` LIKE \'%'.$search.'%\' OR `customer_name` LIKE \'%'.$search.'%\')', NULL, FALSE);
        
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
    
    public function saveNotification($sales_order_id,$message)
    {
        $data = array(
                        'sales_order_id' => $sales_order_id,
                        'message' => $message
                      );
        $this->db->insert($this->_notification,$data);
        
        return $this->db->affected_rows();
    }
    
    public function getSalesOrderInformation($sales_order_id)
    {
        $where = array(
                        $this->_sales_order.'.sales_order_id' => $sales_order_id
                       );
        
        $this->db->where($where);
        $this->db->select($this->_sales_order.'.*,'.$this->_customers.'.customer_name,'.$this->_customers.'.contact_no,'.$this->_customers.'.tin');
        $this->db->from($this->_sales_order);
        $this->db->join($this->_customers,$this->_customers.'.customers_id='.$this->_sales_order.'.customers_id','LEFT');
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
    
    public function getSoComments($sales_order_id)
    {
        $where = array(
                        'sales_order_id' => $sales_order_id
                       );
        $this->db->where($where);
        $response = $this->db->get($this->_sales_order);
        
        return $response;
    }
    
    public function getInvoiceComments($sales_order_id)
    {
        $where = array(
                        'sales_order_id' => $sales_order_id
                       );
        $this->db->where($where);
        $response = $this->db->get($this->_invoice);
        
        return $response;
    }
    
    public function getInventoryComments($sales_order_id)
    {
        $where = array(
                        'sales_order_id' => $sales_order_id
                       );
        $this->db->where($where);
        $response = $this->db->get($this->_inventory);
        
        return $response;
    }
    
    public function getDeliveryComments($sales_order_id)
    {
        $where = array(
                        'sales_order_id' => $sales_order_id
                       );
        $this->db->where($where);
        $response = $this->db->get($this->_delivery_request);
        
        return $response;
    }
    
    public function getCollectionComments($sales_order_id)
    {
        $where = array(
                        'sales_order_id' => $sales_order_id
                       );
        $this->db->where($where);
        $response = $this->db->get($this->_collection);
        
        return $response;
    }
    
    public function getRemitttanceComments($sales_order_id)
    {
        $where = array(
                        'sales_order_id' => $sales_order_id
                       );
        $this->db->where($where);
        $response = $this->db->get($this->_remittance);
        
        return $response;
    }
    
    public function getCommentImages($sales_order_id,$process_type)
    {
        $where = array(
                        'sales_order_id' => $sales_order_id,
                        'process_type' => $process_type
                       );
        $this->db->where($where);
        $response = $this->db->get($this->_images);
        
        return $response;
    }
    
    public function getAccountDetails($account_id)
    {
        $where = array(
                        'account_id' => $account_id
                       );
        $this->db->where($where);
        $response = $this->db->get($this->_system_accounts);
        
        return $response->row();
    }
}

?>
