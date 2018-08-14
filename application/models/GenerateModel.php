<?php defined('BASEPATH') OR exit('No direct script access allowed');

class GenerateModel extends CI_Model
{
    protected $_transaction_record = 'transaction_record';
    protected $_remitted = 'remitted';
    protected $_system_accounts = 'system_accounts';
    protected $_pdc_info = 'pdc_info';
    protected $_credit_card_info = 'credit_card_info';
    protected $_cash_info = 'cash_info';
    protected $_invoice = 'invoice';
    protected $_banking = 'banking';
    
    public function getPaymentInfo($transaction_id,$payment_type)
    {
        $where = array(
                        $this->_transaction_record.'.transaction_id' => $transaction_id
                       );
        $this->db->where($where);
        $this->db->select($this->_system_accounts.'.name,'.$this->_system_accounts.'.account_id,'.
                          $this->selectColumn($payment_type).$this->_invoice.'.invoice_number,'.$this->_invoice.'.invoice_date,'.
                          $this->_transaction_record.'.so_number,'.$this->_transaction_record.'.psr_name,'.$this->_transaction_record.'.transaction_id,'.
                          $this->_remitted.'.cr_no,'.$this->_remitted.'.remitted_date,'.$this->_remitted.'.rrr_no,'.$this->_transaction_record.'.so_date',$this->selectFlag($payment_type));
        $this->db->from($this->_transaction_record);
        $this->db->join($this->_system_accounts,$this->_system_accounts.'.account_id='.$this->_transaction_record.'.account_id','LEFT');
        $this->db->join($this->_remitted,$this->_remitted.'.transaction_id='.$this->_transaction_record.'.transaction_id','LEFT');
        $this->db->join($this->_invoice,$this->_invoice.'.transaction_id='.$this->_transaction_record.'.transaction_id','LEFT');
        $this->selectTable($payment_type);
        $response = $this->db->get();
        
        return $response;
    }
    
    public function getPaymentInfoAccecpted($transaction_id,$payment_type)
    {
        $where = array(
                        $this->_transaction_record.'.transaction_id' => $transaction_id,
                        $this->selectFlag($payment_type) => 2
                       );
        $this->db->where($where);
        $this->db->select($this->_system_accounts.'.name,'.$this->_system_accounts.'.account_id,'.
                          $this->selectColumn($payment_type).$this->_invoice.'.invoice_number,'.$this->_invoice.'.invoice_date,'.
                          $this->_transaction_record.'.so_number,'.$this->_transaction_record.'.psr_name,'.$this->_transaction_record.'.transaction_id,'.
                          $this->_remitted.'.cr_no,'.$this->_remitted.'.remitted_date,'.$this->_remitted.'.rrr_no,'.$this->_transaction_record.'.so_date',$this->selectFlag($payment_type));
        $this->db->from($this->_transaction_record);
        $this->db->join($this->_system_accounts,$this->_system_accounts.'.account_id='.$this->_transaction_record.'.account_id','LEFT');
        $this->db->join($this->_remitted,$this->_remitted.'.transaction_id='.$this->_transaction_record.'.transaction_id','LEFT');
        $this->db->join($this->_invoice,$this->_invoice.'.transaction_id='.$this->_transaction_record.'.transaction_id','LEFT');
        $this->selectTable($payment_type);
        $response = $this->db->get();
        
        return $response;
    }
    
    private function selectColumn($payment_type)
    {
        $column_table='';
        
        switch($payment_type)
        {
            case '1' :
                $column_table = $this->_cash_info.".*,";
                break;
            case '2' :
                $column_table = $this->_pdc_info.".*,";
                break;
            case '3' :
                $column_table = $this->_credit_card_info.".*,";
                break;
        }
        
        return $column_table;
    }
    
    private function selectFlag($payment_type)
    {
        $flag = 0;
        switch($payment_type)
        {
            case '1' :
                $flag = $this->_cash_info.".flag";
                break;
            case '2' :
                $flag = $this->_pdc_info.".flag";
                break;
            case '3' :
                $flag = $this->_credit_card_info.".flag";
                break;
        }
        
        return $flag;
    }
    
    private function selectTable($payment_type)
    {
        $query = array();
        $fk_column = 'remitted_id';
        switch($payment_type)
        {
            case '1' :
                $query['join'] = $this->setJoinQuery($this->_cash_info,$this->_remitted,$fk_column);
                break;
            case '2' :
                $query['join'] = $this->setJoinQuery($this->_pdc_info,$this->_remitted,$fk_column);
                break;
            case '3' :
                $query['join'] = $this->setJoinQuery($this->_credit_card_info,$this->_remitted,$fk_column);
                break;
        }
        
        return $query;
    }
    
    private function setJoinQuery($prepend_table,$main_table,$fk_column)
    {
        return $this->db->join($prepend_table,$prepend_table.'.'.$fk_column.'='.$main_table.'.'.$fk_column,'LEFT');
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
            case '4' :
                $this->db->select($this->_cash_info.'.*, cash_deposit.*');
                $this->db->from($this->_cash_info);
                $this->db->join('cash_deposit','cash_deposit.cash_id='.$this->_cash_info.'.cash_id');
                $table = $this->db->get();
                break;
            case '5' : $table = $this->db->get($this->_pdc_info); break;
            case '6' : $table = $this->db->get($this->_banking); break;
        }
        
        return $table;
    }
}


?>