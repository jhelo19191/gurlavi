<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once('BaseModel.php');

class RemittanceModel extends BaseModel
{
    protected $_collection = 'collection';
    protected $_pdc_info = 'pdc_info';
    protected $_cash_info = 'cash_info';
    protected $_credit_card_info = 'credit_card_info';
    protected $_sales_invoice = 'sales_invoice';
    protected $_sales_order = 'sales_order';
    protected $_reg_products = 'reg_products';
    protected $_products = 'products';
    protected $_customers = 'customers';
    protected $_notification = 'notification';
    protected $_comments = 'payment_comments';
    protected $_remittance = 'remittance';
    
    public function fetch_remittance($tbName,$start,$limit,$search,$type_ordering,$account_id,$user_level)
    {
        $data=array();
        
        if($user_level==2 || $user_level==4):
            $where = array(
                            $this->_sales_order.'.account_id' => $account_id
                          );
            $this->db->where($where);
        endif;
        
        $this->db->select($this->_sales_order.'.*,'.$this->_customers.'.customer_name,'.$this->_sales_invoice.'.invoice_no,'.$this->_sales_invoice.'.invoice_date');
        $this->db->from($this->_sales_order);
        $this->db->join($this->_customers,$this->_customers.'.customers_id='.$this->_sales_order.'.customers_id','LEFT');
        $this->db->join($this->_sales_invoice,$this->_sales_invoice.'.sales_order_id='.$this->_sales_order.'.sales_order_id','LEFT');
        
        $this->db->where('(`sales_order_no` LIKE \'%'.$search.'%\' OR `psr_name` LIKE \'%'.$search.'%\' OR `invoice_no` LIKE \'%'.$search.'%\' OR `customer_name` LIKE \'%'.$search.'%\')', NULL, FALSE);
        
        $this->db->order_by($this->_sales_order.'.sales_order_id', $type_ordering);
        $this->db->limit($limit,$start);
        
        $query = $this->db->get();
        
        if($query->num_rows() > 0)
        {
            foreach($query->result() as $row)
            {
                $count = $this->countActivePayments($row->sales_order_id,5,2,2);
                if($count!=0):
                    $data[] = $row;
                endif;
                
            }
            
            if(count($data)>0):
                return $data;
            else:
                return false;
            endif;
        }
        return false;
    }

    public function countSalesOrder($search,$account_id,$user_level)
    {
        $iter=0;
        
        if($user_level==2 || $user_level==4):
            $where = array(
                            $this->_sales_order.'.account_id' => $account_id
                          );
            $this->db->where($where);
        endif;
        
        $this->db->select($this->_sales_order.'.*,'.$this->_customers.'.customer_name,'.$this->_sales_invoice.'.invoice_no,'.$this->_sales_invoice.'.invoice_date');
        $this->db->from($this->_sales_order);
        $this->db->join($this->_customers,$this->_customers.'.customers_id='.$this->_sales_order.'.customers_id','LEFT');
        $this->db->join($this->_sales_invoice,$this->_sales_invoice.'.sales_order_id='.$this->_sales_order.'.sales_order_id','LEFT');
        
        $this->db->where('(`sales_order_no` LIKE \'%'.$search.'%\' OR `psr_name` LIKE \'%'.$search.'%\' OR `invoice_no` LIKE \'%'.$search.'%\' OR `customer_name` LIKE \'%'.$search.'%\')', NULL, FALSE);
        
        $query = $this->db->get();
        
        if($query->num_rows() > 0)
        {
            foreach($query->result() as $row)
            {
                $count = $this->countActivePayments($row->sales_order_id,5,2,2);
                if($count!=0):
                    $iter += 1;
                endif;
                
            }
            return $iter;
        }
        return 0;
    }
    
    public function updateSalesOrderStatus($sales_order_id,$status)
    {
        $where = array(
                        'sales_order_id' => $sales_order_id
                       );
        $data =array(
                        'status' => $status
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
                          $this->_collection.'.cr_no,'.$this->_collection.'.collect_date');
        $this->db->from($this->_sales_order);
        $this->db->join($this->_customers,$this->_customers.'.customers_id='.$this->_sales_order.'.customers_id','LEFT');
        $this->db->join($this->_sales_invoice,$this->_sales_invoice.'.sales_order_id='.$this->_sales_order.'.sales_order_id','LEFT');
        $this->db->join($this->_collection,$this->_collection.'.sales_order_id='.$this->_sales_order.'.sales_order_id','LEFT');
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
    
    public function checkChequeNumber($cheque_number)
    {
        $where = array(
                        'check_no' => $cheque_number
                       );
        $this->db->where($where);
        $response = $this->db->get($this->_pdc_info);
        
        return $response->num_rows();
    }
        
    public function getCashRecord($sales_order_id)
    {
        $where = array(
                        'sales_order_id' => $sales_order_id
                       );
        $this->db->where($where);
        $response = $this->db->get($this->_cash_info);
        
        return $response;
    }
    
    public function getPdcRecord($sales_order_id)
    {
        $where = array(
                        'sales_order_id' => $sales_order_id
                       );
        $this->db->where($where);
        $response = $this->db->get($this->_pdc_info);
        
        return $response;
    }
    
    public function getCreditCardRecord($sales_order_id)
    {
        $where = array(
                        'sales_order_id' => $sales_order_id
                       );
        $this->db->where($where);
        $response = $this->db->get($this->_credit_card_info);
        
        return $response;
    }
    
    public function getPdcInfoByID($pdc_id)
    {
        $where =array(
                        'pdc_id' => $pdc_id
                      );
        $this->db->where($where);
        $response = $this->db->get($this->_pdc_info);
        
        return $response->row();
    }
   
    
    public function checkUpdateCheckNo($pdc_id,$check_no)
    {
        $where = array(
                        'pdc_id !=' => $pdc_id,
                        'check_no' => $check_no
                       );
        $this->db->where($where);
        $response = $this->db->get($this->_pdc_info);
        
        return $response->num_rows();
    }
    
        
    public function getCashInfoByID($cash_id)
    {
        $where =array(
                        'cash_id' => $cash_id
                      );
        $this->db->where($where);
        $response = $this->db->get($this->_cash_info);
        
        return $response->row();
    }
    
    public function getCreditInfoByID($card_id)
    {
        $where =array(
                        'card_id' => $card_id
                      );
        $this->db->where($where);
        $response = $this->db->get($this->_credit_card_info);
        
        return $response->row();
    }
    
    
    public function getPaymentRecordInfo($sales_order_id,$created_date,$payment_type)
    {
        $where = array(
                        'sales_order_id' => $sales_order_id,
                        'created_date' => $created_date
                       );
        $this->db->where($where);
        $response = $this->getTablePayment($payment_type);
        
        return $response;
    }
    
    private function getTablePayment($payment_type)
    {
        $table='';
        switch($payment_type)
        {
            case '1' : $table = $this->db->get($this->_cash_info); break;
            case '2' : $table = $this->db->get($this->_pdc_info); break;
            case '3' : $table = $this->db->get($this->_credit_card_info); break;
        }
        
        return $table;
    }
    
    public function removeTablePayment($payment_id,$payment_type)
    {
        $table='';
        switch($payment_type)
        {
            case '1' :
                
            $where = array(
                            'cash_id' => $payment_id
                           );
            $this->db->where($where);
            $table = $this->db->delete($this->_cash_info);
            break;
            case '2' :
                
            $where = array(
                            'pdc_id' => $payment_id
                           );
            $this->db->where($where);
            $table = $this->db->delete($this->_pdc_info);
            break;
            case '3' :
                
            $where = array(
                            'card_id' => $payment_id
                           );
            $this->db->where($where);
            $table = $this->db->delete($this->_credit_card_info);
            break;
        }
        
        return $this->db->affected_rows();
    }
    
    public function insertCollection($sales_order_id,$collection_no,$collection_date)
    {
        $data = array(
                        'sales_order_id' => $sales_order_id,
                        'cr_no' => $collection_no,
                        'collect_date' => $collection_date
                      );
        
        $this->db->insert($this->_collection,$data);
        
        return $this->db->affected_rows();
    }
    
    public function checkCollectionNumber($collection_no)
    {
        $where = array(
                        'cr_no' => $collection_no
                      );
        $this->db->where($where);
        $response = $this->db->get($this->_collection);
        
        return $response->num_rows();
    }
    
    public function checkSalesOrderId($sales_order_id)
    {
        $where = array(
                        'sales_order_id' => $sales_order_id
                      );
        $this->db->where($where);
        $response = $this->db->get($this->_collection);
        
        return $response->num_rows();
    }
    
    public function updateCashFlag($payment_id,$flag)
    {
        $where = array(
                        'cash_id' => $payment_id
                       );
        $data = array(
                        'flag' => $flag
                      );
        $this->db->where($where);
        $this->db->update($this->_cash_info,$data);
        
        return $this->db->affected_rows();
    }
    
    public function updatePdcFlag($payment_id,$flag)
    {
        $where = array(
                        'pdc_id' => $payment_id
                       );
        $data = array(
                        'flag' => $flag
                      );
        $this->db->where($where);
        $this->db->update($this->_pdc_info,$data);
        
        return $this->db->affected_rows();
    }
    
    public function updateCreditCardFlag($payment_id,$flag)
    {
        $where = array(
                        'card_id' => $payment_id
                       );
        $data = array(
                        'flag' => $flag
                      );
        $this->db->where($where);
        $this->db->update($this->_credit_card_info,$data);
        
        return $this->db->affected_rows();
    }
    
    public function insertPaymentComments($payment_id,$payment_type,$message,$sender_id,$receiver_id)
    {
        $data = array(
                        'payment_id' => $payment_id,
                        'payment_type' => $payment_type,
                        'message' => $message,
                        'sender_id' => $sender_id,
                        'receiver_id' => $receiver_id
                      );
        $this->db->insert($this->_comments,$data);
        
        return $this->db->affected_rows();
    }
    
    public function checkRemittanceNo($sales_order_id,$remittance_no)
    {
        $where = array(
                        'remittance_no' => $remittance_no
                       );
        $this->db->where($where);
        $response = $this->db->get($this->_remittance);
       
       return $response->num_rows();
    }
    
    public function checkRemittanceSalesOrder($sales_order_id)
    {
        $where = array(
                        'sales_order_id' => $sales_order_id
                       );
        $this->db->where($where);
        $response = $this->db->get($this->_remittance);
       
       return $response->num_rows();
    }
    
    
    public function insert($sales_order_id,$remittance_no,$remittance_date,$message,$approved_by)
    {
        $data = array(
                        'sales_order_id' => $sales_order_id,
                        'remittance_no' => $remittance_no,
                        'remittance_date' => $remittance_date,
                        'message' => $message,
                        'approved_by' => $approved_by
                      );
        
        $this->db->insert($this->_remittance,$data);
        
        return $this->db->insert_id();
    }
    
    public function update($sales_order_id,$remittance_no,$remittance_date,$message)
    {
        $where = array(
                        'sales_order_id' => $sales_order_id
                       );
        $data = array(
                        'remittance_no' => $remittance_no,
                        'remittance_date' => $remittance_date,
                        'message' => $message
                      );
        
        $this->db->where($where);
        $this->db->update($this->_remittance,$data);
        
        return $this->db->affected_rows();
    }
    
    public function checkPDC($sales_order_id)
    {
        $where =array(
                        'sales_order_id' => $sales_order_id
                      );
        $this->db->where($where);
        $response = $this->db->get($this->_pdc_info);
        
        return $response->num_rows();
    }
    
    public function updateCashStatu($sales_order_id)
    {
        $where = array(
                        'sales_order_id' => $sales_order_id
                       );
        $this->db->where();
        $this->db->update($this->_cash_info,$data);
    }
    
    public function getRemittance($sales_order_id)
    {
        $where = array(
                        'sales_order_id' => $sales_order_id
                       );
        $this->db->where($where);
        $response = $this->db->get($this->_remittance);
        
        return $response;
    }
    
    public function getMobileBankingRecord($sales_order_id)
    {
        $where = array(
                        'sales_order_id' => $sales_order_id
                       );
        $this->db->where($where);
        $response = $this->db->get($this->_banking);
        
        return $response;
    }
    
    public function getBankingInfo($bank_id)
    {
        $where = array(
                        'bank_id' => $bank_id
                       );
        $this->db->where($where);
        $response = $this->db->get($this->_banking);
        
        return $response->row();
    }
    
    public function updatePdcRemit($sales_order_id,$remittance_id)
    {
        $where = array(
                        'sales_order_id' => $sales_order_id,
                        'flag' => 3
                       );
        $data = array(
                        'remitted_id' => $remittance_id
                      );
        $this->db->where($where);
        $this->db->update($this->_pdc_info,$data);
        
        return $this->db->affected_rows();
    }
    
    public function updateCashRemit($sales_order_id,$remittance_id)
    {
        $where = array(
                        'sales_order_id' => $sales_order_id,
                        'flag' => 3
                       );
        $data = array(
                        'remitted_id' => $remittance_id
                      );
        $this->db->where($where);
        $this->db->update($this->_cash_info,$data);
        
        return $this->db->affected_rows();
    }
    
    public function updateCreditCardRemit($sales_order_id,$remittance_id)
    {
        $where = array(
                        'sales_order_id' => $sales_order_id,
                        'flag' => 3
                       );
        $data = array(
                        'remitted_id' => $remittance_id
                      );
        $this->db->where($where);
        $this->db->update($this->_credit_card_info,$data);
        
        return $this->db->affected_rows();
    }
}


?>