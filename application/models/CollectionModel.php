<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once('BaseModel.php');

class CollectionModel extends BaseModel
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
    protected $_delivery_request = 'delivery_request';
    protected $_cash_deposit = 'cash_deposit';
    protected $_banking = 'banking';
    protected $_num_rows = 0;
    
    public function fetch_collection($tbName,$start,$limit,$search,$type_ordering,$account_id,$user_level)
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
                $num_rows = $this->checkSalesOrderStatus($row->sales_order_id);
                if($num_rows==1)
                {
                    $data[] = $row;
                }
                else
                {
                    $count = $this->countActivePayments($row->sales_order_id,3,0,5);
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
                $num_rows = $this->checkSalesOrderStatus($row->sales_order_id);
                if($num_rows==1)
                {
                    $iter+=1;
                }
                else
                {
                    $count = $this->countActivePayments($row->sales_order_id,3);
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
                        'status' => 4
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
                          $this->_delivery_request.'.actual_delivery_date');
        $this->db->from($this->_sales_order);
        $this->db->join($this->_customers,$this->_customers.'.customers_id='.$this->_sales_order.'.customers_id','LEFT');
        $this->db->join($this->_sales_invoice,$this->_sales_invoice.'.sales_order_id='.$this->_sales_order.'.sales_order_id','LEFT');
        $this->db->join($this->_delivery_request,$this->_delivery_request.'.sales_order_id='.$this->_sales_order.'.sales_order_id','LEFT');
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
                           $check_date,$check_number,$check_amount,$terms,$image_path,$transaction_type)
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
                        'status' => 3,
                        'image_path' => $image_path,
                        'transaction_type' => $transaction_type
                      );
        $this->db->insert($this->_pdc_info,$data);
        
        return $this->db->insert_id();
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
    
    public function getMobileBankingRecord($sales_order_id)
    {
        $where = array(
                        'sales_order_id' => $sales_order_id
                       );
        $this->db->where($where);
        $response = $this->db->get($this->_banking);
        
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
                           $check_date,$check_number,$check_amount,$image_path,$status)
    {
        $data = array(
                        'bank_name' => $bank_name,
                        'branch' => $branch,
                        'account_name' => $account_name,
                        'account_no' => $account_number,
                        'cheque_date' => $check_date,
                        'check_no' => $check_number,
                        'amount' => $check_amount,
                        'flag' => 0,
                        'status' => 3
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
    
    public function insertCash($sales_order_id,$cash_amount,$payment_date,$image_path,$transaction_type)
    {
        $data = array(
                        'sales_order_id' => $sales_order_id,
                        'amount' => $cash_amount,
                        'payment_date' => $payment_date,
                        'image_path' => $image_path,
                        'transaction_type' => $transaction_type,
                        'status' => 3
                      );
        $this->db->insert($this->_cash_info,$data);
        
        return $this->db->insert_id();
    }
    
    public function updateCashDeposit($cash_id,$receipt_no,$account_receiver)
    {
        $where = array(
                        'cash_id' => $cash_id
                       );
        $data = array(
                        'receipt_no' => $receipt_no,
                        'account_receiver' => $account_receiver
                      );
        $this->db->where($where);
        $this->db->update($this->_cash_deposit,$data);
        
        return $this->db->affected_rows();
    }
    
    public function insertCashDeposit($cash_id,$receipt_no,$account_receiver)
    {
        $data = array(
                        'cash_id' => $cash_id,
                        'receipt_no' => $receipt_no,
                        'account_receiver' => $account_receiver
                      );
        $this->db->insert($this->_cash_deposit,$data);
        
        return $this->db->affected_rows();
    }
    
    public function checkCashDeposit($receipt_no)
    {
        $where = array(
                        'receipt_no' => $receipt_no
                       );
        $this->db->where($where);
        $response = $this->db->get($this->_cash_deposit);
        
        return $response->num_rows();
    }
    
    public function checkUpdateCashReceiptNo($cash_id,$receipt_no)
    {
        $where = array(
                        'cash_id !=' => $cash_id,
                        'receipt_no' => $receipt_no
                       );
        $this->db->where($where);
        $response = $this->db->get($this->_cash_deposit);
        
        return $response->num_rows();
    }
    
    public function updateCashInfo($cash_id,$cash_amount,$payment_date,$image_path,$status)
    {
        $data = array(
                        'amount' => $cash_amount,
                        'payment_date' => $payment_date,
                        'flag' => 0,
                        'status' => 3
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
                        'status' => 3,
                        'image_path' => $image_path
                      );
        $this->db->insert($this->_credit_card_info,$data);
        
        return $this->db->insert_id();
    }
    
    public function updateCreditInfo($card_id,$settlement,$card_no,$bank_name,$approval_code,
                                 $batch_no,$credit_card_amount,$image_path,$status)
    {
        $data = array(
                        'settlement_date' => $settlement,
                        'card_no' => $card_no,
                        'bank_name' => $bank_name,
                        'approval_code' => $approval_code,
                        'batch_no' => $batch_no,
                        'credit_card_amount' => $credit_card_amount,
                        'flag' => 0,
                        'status' => $status
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
            case '4' : $table = $this->db->get($this->_cash_info); break;
            case '5' : $table = $this->db->get($this->_pdc_info); break;
            case '6' : $table = $this->db->get($this->_banking); break;
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
            break;case '4' :
                
            $where = array(
                            'cash_id' => $payment_id
                           );
            $this->db->where($where);
            $table = $this->db->delete($this->_cash_info);
            break;
            case '5' :
                
            $where = array(
                            'pdc_id' => $payment_id
                           );
            $this->db->where($where);
            $table = $this->db->delete($this->_pdc_info);
            break;
            case '6' :
                
            $where = array(
                            'bank_id' => $payment_id
                           );
            $this->db->where($where);
            $table = $this->db->delete($this->_banking);
            break;
        }
        
        return $this->db->affected_rows();
    }
    
    public function insertCollection($sales_order_id,$collection_no,$collection_date,$message,$account_id)
    {
        $data = array(
                        'sales_order_id' => $sales_order_id,
                        'cr_no' => $collection_no,
                        'collect_date' => $collection_date,
                        //'status' => 3,
                        'message' => $message,
                        'approved_by' => $account_id
                      );
        
        $this->db->insert($this->_collection,$data);
        
        return $this->db->affected_rows();
    }
    
    public function updateCollection($sales_order_id,$collection_no,$collection_date,$message)
    {
        $data = array(
                        'sales_order_id' => $sales_order_id,
                        'cr_no' => $collection_no,
                        'collect_date' => $collection_date,
                        'message' => $message
                      );
        
        $where = array(
                        'sales_order_id' => $sales_order_id
                       );
        
        $this->db->where($where);
        $this->db->update($this->_collection,$data);
        
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
    
    public function getCollectionInfo($sales_order_id)
    {
        $where =array(
                        'sales_order_id' => $sales_order_id
                      );
        $this->db->where($where);
        $response = $this->db->get($this->_collection);
        
        return $response;
    }
    
    public function countPayments($sales_order_id)
    {
        $recount_cash = $this->countCashRecords($sales_order_id);
        $recount_pdc = $this->countPdcRecords($sales_order_id);
        $recount_card = $this->countCreaditRecords($sales_order_id);
        
        return $recount_cash + $recount_pdc + $recount_card;
    }
    
    private function countCashRecords($sales_order_id)
    {
        $where = array(
                        'sales_order_id' => $sales_order_id
                       );
        $this->db->where($where);
        $response = $this->db->get($this->_cash_info);
        
        return $response->num_rows();
    }
    
    private function countPdcRecords($sales_order_id)
    {
        $where = array(
                        'sales_order_id' => $sales_order_id
                       );
        $this->db->where($where);
        $response = $this->db->get($this->_pdc_info);
        
        return $response->num_rows();
    }
    
    private function countCreaditRecords($sales_order_id)
    {
        $where = array(
                        'sales_order_id' => $sales_order_id
                       );
        $this->db->where($where);
        $response = $this->db->get($this->_credit_card_info);
        
        return $response->num_rows();
    }
    
    private function checkSalesOrderStatus($sales_order_id)
    {
        $where = array(
                        'status' => 3,
                        'sales_order_id' => $sales_order_id
                       );
        $this->db->where($where);
        $result = $this->db->get($this->_sales_order);
        
        return $result->num_rows();
    }
    
    public function updatePaymentCommentFlag($payment_id,$payment_type)
    {
        $where = array(
                        'payment_id' => $payment_id,
                        'payment_type' => $payment_type
                       );
        $data = array(
                        'flag' => 1
                      );
        $this->db->where($where);
        $this->db->update($this->_comments,$data);
        
        return $this->db->affected_rows();
    }
    
    public function checkBankingRefNo($banking_ref_no)
    {
        $where = array(
                        'reference_no' => $banking_ref_no
                       );
        $this->db->where($where);
        $response = $this->db->get($this->_banking);
        
        return $response->num_rows();
    }
    
    public function insertBankingInfo($sales_order_id,$reference_no,$money_type,$from_account,$amount,$destination_account,$image_path)
    {
        $data = array(
                        'sales_order_id' => $sales_order_id,
                        'reference_no' => $reference_no,
                        'money_type' => $money_type,
                        'from_account' => $from_account,
                        'amount' => $amount,
                        'destination_account' => $destination_account,
                        'image_path' => $image_path,
                        'status' => 3
                      );
        $this->db->insert($this->_banking,$data);
        
        return $this->db->insert_id();
    }
    
    public function updateBankingInfo($bank_id,$reference_no,$money_type,$from_account,$amount,
                                      $destination_account,$image_path,$status)
    {
        $where  = array(
                            'bank_id' => $bank_id
                        );
        $data = array(
                        'reference_no' => $reference_no,
                        'money_type' => $money_type,
                        'from_account' => $from_account,
                        'amount' => $amount,
                        'destination_account' => $destination_account,
                        'image_path' => $image_path,
                        'status' => $status,
                        'flag' => 0
                      );
        $this->db->where($where);
        $this->db->update($this->_banking,$data);
        
        return $this->db->affected_rows();
    }
    
    public function checkBankingRefNoUpdate($bank_id,$reference_no)
    {
        $where = array(
                        'bank_id !=' => $bank_id,
                        'reference_no' => $reference_no
                       );
        $this->db->where($where);
        $response = $this->db->get($this->_banking);
        
        return $response->num_rows();
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