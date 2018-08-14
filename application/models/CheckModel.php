<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once('BaseModel.php');

class CheckModel extends BaseModel
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
    
    
    
    public function fetch_check($tbName,$start,$limit,$search,$type_ordering,$account_id,$user_level)
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
                $num_rows = $this->checkStatus($row->sales_order_id,4);
                if($num_rows>0)
                {
                    $data[] = $row;
                }
                else
                {
                    $count = $this->countActivePayments($row->sales_order_id,4,1,1);
                    if($count!=0):
                        $data[] = $row;
                    endif;    
                }
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
                $num_rows = $this->checkStatus($row->sales_order_id,4);
                if($num_rows>0)
                {
                    $iter+=1;
                }
                else
                {
                    $count = $this->countActivePayments($row->sales_order_id,4,1,1);
                    if($count!=0):
                        $iter+=1;
                    endif;    
                }
                
            }
            return $iter;
        }
        return 0;
    }
    
    public function updateSalesOrderStatus($sales_order_id)
    {
        $where = array(
                        'sales_order_id' => $sales_order_id
                       );
        $data =array(
                        'status' => 5
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
                          $this->_collection.'.cr_no,'.$this->_collection.'.collect_date,'.$this->_collection.'.message');
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
    
    public function insert($sales_order_id,$bank_name,$branch,$account_name,$account_number,
                           $check_date,$check_number,$check_amount,$terms,$image_path)
    {
        $data = array(
                        'sales_order_id' => $sales_order_id,
                        'bank_name' => $bank_name,
                        'branch' => $branch,
                        'account_name' => $account_name,
                        'account_no' => $account_number,
                        'cheque_date' => $check_date,
                        'check_no' => $check_number,
                        'amount' => $check_amount,
                        'terms' => $terms,
                        'image_path' => $image_path
                      );
        $this->db->insert($this->_pdc_info,$data);
        
        return $this->db->affected_rows();
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
    
    public function updatePdcInfo($pdc_id,$bank_name,$branch,$account_name,$account_number,
                           $check_date,$check_number,$check_amount,$image_path)
    {
        $data = array(
                        'bank_name' => $bank_name,
                        'branch' => $branch,
                        'account_name' => $account_name,
                        'account_no' => $account_number,
                        'cheque_date' => $check_date,
                        'check_no' => $check_number,
                        'amount' => $check_amount
                      );
        if($image_path!=''):
            $image = array('image_path' => $image_path);
            $data = array_merge($data,$image);
        endif;
        
        $where = array(
                        'pdc_id' => $pdc_id
                       );
        
        $this->db->where($where);
        
        $this->db->update($this->_pdc_info,$data);
        
        return $this->db->affected_rows();
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
    
    public function insertCash($sales_order_id,$cash_amount,$payment_date,$image_path)
    {
        $data = array(
                        'sales_order_id' => $sales_order_id,
                        'amount' => $cash_amount,
                        'payment_date' => $payment_date,
                        'image_path' => $image_path
                      );
        $this->db->insert($this->_cash_info,$data);
        
        return $this->db->affected_rows();
    }
    
    public function updateCashInfo($cash_id,$cash_amount,$payment_date,$image_path)
    {
        $data = array(
                        'amount' => $cash_amount,
                        'payment_date' => $payment_date
                      );
        if($image_path!=''):
            $image = array('image_path' => $image_path);
            $data = array_merge($data,$image);
        endif;
        
        $where = array(
                        'cash_id' => $cash_id
                       );
        
        $this->db->where($where);
        
        $this->db->update($this->_cash_info,$data);
        
        return $this->db->affected_rows();
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
    
    public function insertCredit($sales_order_id,$settlement,$card_no,$bank_name,$approval_code,
                                 $batch_no,$credit_card_amount,$terms,$image_path)
    {
        $data = array(
                        'sales_order_id' => $sales_order_id,
                        'settlement_date' => $settlement,
                        'card_no' => $card_no,
                        'bank_name' => $bank_name,
                        'approval_code' => $approval_code,
                        'batch_no' => $batch_no,
                        'credit_card_amount' => $credit_card_amount,
                        'terms' => $terms,
                        'image_path' => $image_path
                      );
        $this->db->insert($this->_credit_card_info,$data);
        
        return $this->db->affected_rows();
    }
    
    public function updateCreditInfo($card_id,$settlement,$card_no,$bank_name,$approval_code,
                                 $batch_no,$credit_card_amount,$image_path)
    {
        $data = array(
                        'settlement_date' => $settlement,
                        'card_no' => $card_no,
                        'bank_name' => $bank_name,
                        'approval_code' => $approval_code,
                        'batch_no' => $batch_no,
                        'credit_card_amount' => $credit_card_amount
                      );
        if($image_path!=''):
            $image = array('image_path' => $image_path);
            $data = array_merge($data,$image);
        endif;
        
        $where = array(
                        'card_id' => $card_id
                       );
        
        $this->db->where($where);
        
        $this->db->update($this->_credit_card_info,$data);
        
        return $this->db->affected_rows();
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
    
    public function checkBatchNumber($batch_no)
    {
        $where =array(
                        'batch_no' => $batch_no
                      );
        $this->db->where($where);
        $response = $this->db->get($this->_credit_card_info);
        
        return $response->num_rows();
    }
    
    
    public function checkUpdateBatchNo($card_id,$batch_no)
    {
        $where = array(
                        'card_id !=' => $card_id,
                        'batch_no' => $batch_no
                       );
        $this->db->where($where);
        $response = $this->db->get($this->_credit_card_info);
        
        return $response->num_rows();
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
    
    public function updateCashFlag($payment_id,$flag,$status)
    {
        $where = array(
                        'cash_id' => $payment_id
                       );
        $data = array(
                        'flag' => $flag,
                        'status' => $status
                      );
        $this->db->where($where);
        $this->db->update($this->_cash_info,$data);
        
        return $this->db->affected_rows();
    }
    
    public function updatePdcFlag($payment_id,$flag,$status)
    {
        $where = array(
                        'pdc_id' => $payment_id
                       );
        $data = array(
                        'flag' => $flag,
                        'status' => $status
                      );
        $this->db->where($where);
        $this->db->update($this->_pdc_info,$data);
        
        return $this->db->affected_rows();
    }
    
    public function updateBankingFlag($payment_id,$flag,$status)
    {
        $where = array(
                        'bank_id' => $payment_id
                       );
        $data = array(
                        'flag' => $flag,
                        'status' => $status
                      );
        $this->db->where($where);
        $this->db->update($this->_banking,$data);
        
        return $this->db->affected_rows();
    }
    
    public function updateCreditCardFlag($payment_id,$flag,$status)
    {
        $where = array(
                        'card_id' => $payment_id
                       );
        $data = array(
                        'flag' => $flag,
                        'status' => $status
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
    
    public function soSender($sales_order_id)
    {
        $where = array(
                        'sales_order_id' => $sales_order_id
                       );
        $this->db->where($where);
        $response = $this->db->get($this->_sales_order);
        
        return $response->row();
    }
    
    public function checkPaymentStatus($sales_order_id)
    {
        $where = array(
                        'sales_order_id' => $sales_order_id,
                        'flag' => 0
                       );
        
       $cash = $this->countCashFlagZero($where);
       $pdc =  $this->countPdcFlagZero($where);
       $card = $this->countCardFlagZero($where);
       
       return $cash + $pdc + $card;
    }
    
    public function countPdcFlagZero($where)
    {
        
        $this->db->where($where);
        $response = $this->db->get($this->_pdc_info);
        
        return $response->num_rows();
    }
    
    public function countCashFlagZero($where)
    {
        
        $this->db->where($where);
        $response = $this->db->get($this->_cash_info);
        
        return $response->num_rows();
    }
    
    public function countCardFlagZero($where)
    {
        
        $this->db->where($where);
        $response = $this->db->get($this->_credit_card_info);
        
        return $response->num_rows();
    }
    
    private function checkStatus($sales_order_id,$status)
    {
        $where = array(
                        'sales_order_id' => $sales_order_id,
                        'status' => $status
                       );
        $this->db->where($where);
        $response = $this->db->get($this->_sales_order);
        
        return $response->num_rows();
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
}


?>