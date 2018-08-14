<?php defined('BASEPATH') OR exit('No direct script access allowed');

class BaseModel extends CI_Model
{
    protected $_cash_info = 'cash_info';
    protected $_pdc_info = 'pdc_info';
    protected $_credit_card_info = 'credit_card_info';
    protected $_banking = 'banking';
    
    public function countActivePayments($sales_order_id,$status='',$flag='',$sFlag='',$payment_type='')
    {
        if($payment_type=='pdc')
        {
            $count_cash_reject = 0;
            $count_card_reject = 0;
            $count_pdc_reject = $this->countCollectionPDcPayment($sales_order_id,$status,$flag,$sFlag);
            $count_mobile_banking = 0;
        }
        else
        {
            $count_cash_reject = $this->countCollectionCashPayment($sales_order_id,$status,$flag,$sFlag);
            $count_card_reject = $this->countCollectionCreditCardPayment($sales_order_id,$status,$flag,$sFlag);
            $count_pdc_reject = $this->countCollectionPDcPayment($sales_order_id,$status,$flag,$sFlag);
            $count_mobile_banking = $this->countBankingPayment($sales_order_id,$status,$flag,$sFlag);
        }
        
        
        return $count_card_reject + $count_cash_reject + $count_pdc_reject + $count_mobile_banking;
    }
    
    
    public function countCollectionCashPayment($sales_order_id,$status,$flag,$sFlag)
    {
        $where = array(
                        'sales_order_id' => $sales_order_id,
                        'status' => $status
                       );
        
        $this->db->where($where);
        $this->db->where('(`flag` LIKE \'%'.$flag.'%\' OR `flag` LIKE \'%'.$sFlag.'%\')', NULL, FALSE);
        
        $response = $this->db->get($this->_cash_info);
        
        return $response->num_rows();
    }
    
    public function countCollectionPDcPayment($sales_order_id,$status,$flag,$sFlag)
    {
        $where = array(
                        'sales_order_id' => $sales_order_id,
                        'status' => $status
                       );
        
        $this->db->where($where);
        $this->db->where('(`flag` LIKE \'%'.$flag.'\' OR `flag` LIKE \'%'.$sFlag.'\')', NULL, FALSE);
        
        $response = $this->db->get($this->_pdc_info);
        
        return $response->num_rows();
    }
    
    public function countBankingPayment($sales_order_id,$status,$flag,$sFlag)
    {
        $where = array(
                        'sales_order_id' => $sales_order_id,
                        'status' => $status
                       );
        
        $this->db->where($where);
        $this->db->where('(`flag` LIKE \'%'.$flag.'\' OR `flag` LIKE \'%'.$sFlag.'\')', NULL, FALSE);
        
        $response = $this->db->get($this->_banking);
        
        return $response->num_rows();
    }
    
    public function countCollectionCreditCardPayment($sales_order_id,$status,$flag,$sFlag)
    {
        $where = array(
                        'sales_order_id' => $sales_order_id,
                        'status' => $status
                       );
        
        $this->db->where($where);
        $this->db->where('(`flag` LIKE \'%'.$flag.'\' OR `flag` LIKE \'%'.$sFlag.'\')', NULL, FALSE);
        
        $response = $this->db->get($this->_credit_card_info);
        
        return $response->num_rows();
    }
    
    public function updateStatus($sales_order_id,$flag,$status,$rFlag)
    {
        $cash_response = $this->updateCashStatus($sales_order_id,$flag,$status,$rFlag);
        $pdc_response = $this->updatePdcStatus($sales_order_id,$flag,$status,$rFlag);
        $credit_card_response = $this->updateCreditCardStatus($sales_order_id,$flag,$status,$rFlag);
        $mobile_banking = $this->updateBankingStatus($sales_order_id,$flag,$status,$rFlag);
    }
    
    private function updateCashStatus($sales_order_id,$flag,$status,$rFlag)
    {
        $where = array(
                        'sales_order_id' => $sales_order_id,
                        'flag' => $flag
                       );
        $data = array(
                        'flag' => $rFlag,
                        'status' => $status
                      );
        $this->db->where($where);
        $this->db->update($this->_cash_info,$data);
        
        return $this->db->affected_rows();
    }
    
    private function updateBankingStatus($sales_order_id,$flag,$status,$rFlag)
    {
        $where = array(
                        'sales_order_id' => $sales_order_id,
                        'flag' => $flag
                       );
        $data = array(
                        'flag' => $rFlag,
                        'status' => $status
                      );
        $this->db->where($where);
        $this->db->update($this->_banking,$data);
        
        return $this->db->affected_rows();
    }
    
    private function updatePdcStatus($sales_order_id,$flag,$status,$rFlag)
    {
        $where = array(
                        'sales_order_id' => $sales_order_id,
                        'flag' => $flag
                       );
        $data = array(
                        'flag' => $rFlag,
                        'status' => $status
                      );
        $this->db->where($where);
        $this->db->update($this->_pdc_info,$data);
        
        return $this->db->affected_rows();
    }
    
    private function updateCreditCardStatus($sales_order_id,$flag,$status,$rFlag)
    {
        $where = array(
                        'sales_order_id' => $sales_order_id,
                        'flag' => $flag
                       );
        $data = array(
                        'flag' => $rFlag,
                        'status' => $status
                      );
        $this->db->where($where);
        $this->db->update($this->_credit_card_info,$data);
        
        return $this->db->affected_rows();
    }
}


?>