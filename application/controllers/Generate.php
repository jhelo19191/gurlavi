<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Generate extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('GenerateModel');
        $this->load->model('FileUploadModel');
    }
    
    public function fields()
    {
        $payment_type = $this->input->post('payment_type');
        $data='';
        
        switch($payment_type)
        {
            case '1' : $data = $this->cash(); break;
            case '2' : $data = $this->pdc2(); break;
            case '3' : $data = $this->creditCard(); break;  
            case '4' : $data = $this->cash_deposit(); break;  
            case '5' : $data = $this->pdc2(); break;  
            case '6' : $data = $this->mobile_banking(); break;  
        }
        
        echo json_encode($data);
    }
    
    public function pdc()
    {
        $check_count = $this->input->post('fields_count');
        $fields = '';
        
        if($check_count>0)
        {
            for($i=1;$i<=$check_count;$i++)
            {
                $fields .= 'Cheque #'.$i.'<hr>';
                $fields .= '<div class="form-group">';
                $fields .= '    <div class="row">';
                $fields .= '        <div class="col-md-6">';
                $fields .= '            <label>Bank Name</label>';
                $fields .= '            <input class="form-control bank_name" autocomplete="off" name="bank_name[]" />';
                $fields .= '        </div>';
                $fields .= '        <div class="col-md-6">';
                $fields .= '            <label>Branch</label>';
                $fields .= '            <input class="form-control branch" autocomplete="off" name="branch[]" />';
                $fields .= '        </div>';
                $fields .= '    </div>';
                $fields .= '</div>';
                $fields .= '<div class="form-group">';
                $fields .= '    <div class="row">';
                $fields .= '        <div class="col-md-6">';
                $fields .= '            <label>Account Name</label>';
                $fields .= '            <input class="form-control account_name" autocomplete="off" name="account_name[]" />';
                $fields .= '        </div>';
                $fields .= '        <div class="col-md-6">';
                $fields .= '            <label>Account Number</label>';
                $fields .= '            <input class="form-control account_number" autocomplete="off" name="account_number[]" />';
                $fields .= '        </div>';
                $fields .= '    </div>';
                $fields .= '</div>';
                $fields .= '<div class="form-group">';
                $fields .= '    <div class="row">';
                $fields .= '        <div class="col-md-6">';
                $fields .= '            <label>Check Date</label>';
                $fields .= '            <input class="form-control date-picker check_date" autocomplete="off" placeholder="mm/dd/yyyy" name="check_date[]" />';
                $fields .= '         </div>';
                $fields .= '        <div class="col-md-6">';
                $fields .= '            <label>Check Number</label>';
                $fields .= '            <input class="form-control check_number" autocomplete="off" name="check_number[]" />';
                $fields .= '        </div>';
                $fields .= '    </div>';
                $fields .= '</div>';
                $fields .= '<div class="form-group">';
                $fields .= '    <label>Amount</label>';
                $fields .= '    <div class="input-group">';
                $fields .= '         <div class="input-group-prepend">';
                $fields .= '              <span class="input-group-text" id="basic-addon1">PHP</span>';
                $fields .= '         </div>';
                $fields .= '         <input class="form-control pdc_amount" data-value="'.$i.'" autocomplete="off" placeholder="00.00" name="check_amount[]" />';
                $fields .= '    </div>  ';
                $fields .= '</div>  ';
                $fields .= '<hr>';
            }
            $data['fields']=$fields;
        }
        else
        {
            $data['result'] = 'fail';
            $data['message'] = 'Enter the number of cheques';
        }
        
        echo json_encode($data);
    }
    
    private function mobile_banking($row=array(),$activity='')
    {
        $fields = '';
        $fields .= '<div class="row">';
        $fields .= '            <div class="col-md-6">';
        if(count($row)!=0):
        $fields .= '            <input class="form-control bank_name" type="hidden" value="'.$row->bank_id.'" autocomplete="off" name="bank_id" />';
        endif;
        $fields .= '                <div class="form-group">';
        $fields .= '                    <label>Reference No.</label>';
        if(count($row)!=0 && $activity=='view'):
        $fields .=              '<p><strong>'.$row->reference_no.'</strong></p>';    
        else:
        $fields .= '                    <input type="text" '.($activity=='modify'? 'value="'.$row->reference_no.'"' : '').' autocomplete="off" class="form-control banking_ref_no" name="banking_ref_no" />';
        endif;
        $fields .= '                </div>';
        $fields .= '            </div>';
        $fields .= '            <div class="col-md-6">';
        $fields .= '                <div class="form-group">';
        $fields .= '                    <label>Send Money Type</label>';
        if(count($row)!=0 && $activity=='view'):
        $fields .=              '<p><strong>'.$row->money_type.'</strong></p>';    
        else:
        $fields .= '                    <input type="text" '.($activity=='modify'? 'value="'.$row->money_type.'"' : '').' class="form-control money_type" name="money_type" />';
        endif;
        $fields .= '                </div>';
        $fields .= '            </div>';
        $fields .= '        </div>';
        $fields .= '        <div class="row">';
        $fields .= '            <div class="col-md-6">';
        $fields .= '                <div class="form-group">';
        $fields .= '                    <label>From Account</label>';
        if(count($row)!=0 && $activity=='view'):
        $fields .=              '<p><strong>'.$row->from_account.'</strong></p>';    
        else:
        $fields .= '                    <input type="text" '.($activity=='modify'? 'value="'.$row->from_account.'"' : '').' class="form-control from_account" name="from_account" />';
        endif;
        $fields .= '                </div>';
        $fields .= '            </div>';
        $fields .= '            <div class="col-md-6">';
        $fields .= '                <div class="form-group">';
        $fields .= '                    <label>Amount</label>';
        if(count($row)!=0 && $activity=='view'):
        $fields .=              '<p><strong>'.$row->amount.'</strong></p>';    
        else:
        $fields .= '                    <input type="text" '.($activity=='modify'? 'value="'.$row->amount.'"' : '').' class="form-control bt_amount" name="bt_amount" />';
        endif;
        $fields .= '                </div>';
        $fields .= '            </div>';
        $fields .= '        </div>';
        $fields .= '        <div class="row">';
        $fields .= '            <div class="col-md-12">';
        $fields .= '                <div class="form-group">';
        $fields .= '                    <label>Destination Account</label>';
        if(count($row)!=0 && $activity=='view'):
        $fields .=              '<p><strong>'.$row->destination_account.'</strong></p>';    
        else:
        $fields .= '                    <input type="text" '.($activity=='modify'? 'value="'.$row->destination_account.'"' : '').' class="form-control destination_account" name="destination_account" />';
        endif;
        $fields .= '                </div>';
        $fields .= '            </div>';
        $fields .= '        </div>';
        
        $data['fields'] = $fields;
        
        if(count($row)):
            $image_path = $row->image_path;
            $data['images'] = $this->getPaymentImages($image_path);    
        endif;
        
        return $data;
    }
    
    private function pdc2($row=array(),$activity='')
    {
        $fields = '';
        $fields .= '<div class="form-group">';
        $fields .= '    <div class="row">';
        $fields .= '        <div class="col-md-6">';
        if(count($row)!=0):
        $fields .= '            <input class="form-control bank_name" type="hidden" value="'.$row->pdc_id.'" autocomplete="off" name="pdc_id" />';
        endif;
        $fields .= '            <label>Bank Name</label>';
        if(count($row)!=0 && $activity=='view'):
        $fields .=              '<p><strong>'.$row->bank_name.'</strong></p>';
        else:
        $fields .= '            <input class="form-control bank_name" '.($activity=='modify'? 'value="'.$row->bank_name.'"' : '').' autocomplete="off" name="bank_name" />';
        endif;
        $fields .= '        </div>';
        $fields .= '        <div class="col-md-6">';
        $fields .= '            <label>Branch</label>';
        if(count($row)!=0 && $activity=='view'):
        $fields .=              '<p><strong>'.$row->branch.'</strong></p>';
        else:
        $fields .= '            <input class="form-control branch" '.($activity=='modify'? 'value="'.$row->branch.'"' : '').' autocomplete="off" name="branch" />';
        endif;
        $fields .= '        </div>';
        $fields .= '    </div>';
        $fields .= '</div>';
        $fields .= '<div class="form-group">';
        $fields .= '    <div class="row">';
        $fields .= '        <div class="col-md-6">';
        $fields .= '            <label>Account Name</label>';
        if(count($row)!=0 && $activity=='view'):
        $fields .=              '<p><strong>'.$row->account_name.'</strong></p>';
        else:
        $fields .= '            <input class="form-control account_name" '.($activity=='modify'? 'value="'.$row->account_name.'"' : '').' autocomplete="off" name="account_name" />';
        endif;
        $fields .= '        </div>';
        $fields .= '        <div class="col-md-6">';
        $fields .= '            <label>Account Number</label>';
        if(count($row)!=0 && $activity=='view'):
        $fields .=              '<p><strong>'.$row->account_no.'</strong></p>';
        else:
        $fields .= '            <input class="form-control account_number" '.($activity=='modify'? 'value="'.$row->account_no.'"' : '').' autocomplete="off" name="account_number" />';
        endif;
        $fields .= '        </div>';
        $fields .= '    </div>';
        $fields .= '</div>';
        $fields .= '<div class="form-group">';
        $fields .= '    <div class="row">';
        $fields .= '        <div class="col-md-6">';
        $fields .= '            <label>Check Date</label>';
        if(count($row)!=0 && $activity=='view'):
        $fields .=              '<p><strong>'.date('F d, Y',strtotime($row->cheque_date)).'</strong></p>';
        else:
        $fields .= '            <input class="form-control date-picker check_date" '.($activity=='modify'? 'value="'.date('m/d/Y',strtotime($row->cheque_date)).'"' : '').' readonly placeholder="mm/dd/yyyy" autocomplete="off" name="check_date" />';
        endif;
        $fields .= '         </div>';
        $fields .= '        <div class="col-md-6">';
        $fields .= '            <label>Check Number</label>';
        if(count($row)!=0 && $activity=='view'):
        $fields .=              '<p><strong>'.$row->check_no.'</strong></p>';
        else:
        $fields .= '            <input class="form-control check_number" '.($activity=='modify'? 'value="'.$row->check_no.'"' : '').' autocomplete="off" name="check_number" />';
        endif;
        $fields .= '        </div>';
        $fields .= '    </div>';
        $fields .= '</div>';
        $fields .= '<div class="form-group">';
        $fields .= '    <label>Amount</label>';
        if(count($row)!=0 && $activity=='view'):
        $fields .=              '<p><strong>PHP '.number_format($row->amount,2).'</strong></p>';
        else:
        $fields .= '    <div class="input-group">';
        $fields .= '         <div class="input-group-addon">';
        $fields .= '              <span class="input-group-text" id="basic-addon1">PHP</span>';
        $fields .= '         </div>';
        $fields .= '         <input class="form-control pdc_amount" '.($activity=='modify'? 'value="'.$row->amount.'"' : '').' autocomplete="off" placeholder="00.00" name="check_amount" />';
        $fields .= '    </div>  ';
        endif;
        $fields .= '</div>  ';
        
        $data['fields'] = $fields;
        
        if(count($row)):
            $image_path = $row->image_path;
            $data['images'] = $this->getPaymentImages($image_path);    
        endif;
        
        return $data;
    }
    
    private function cash($row=array(),$activity='')
    {
        $fields='';
        
        $fields .= '<div class="form-group">';
        $fields .= '    <div class="row">';
        $fields .= '        <div class="col-md-6">';
        if(count($row)!=0):
        $fields .= '            <input class="form-control" type="hidden" value="'.$row->cash_id.'" autocomplete="off" name="cash_id" />';
        endif;
        $fields .= '            <label>Amount</label>';
        if(count($row)!=0 && $activity=='view'):
        $fields .=              '<p><strong>PHP '.number_format($row->amount,2).'</strong></p>';
        else:
        $fields .= '            <input type="text" class="form-control cash-amount" '.($activity=='modify'? 'value="'.$row->amount.'"' : '').' name="cash_amount" />';
        endif;
        $fields .= '        </div>';
        $fields .= '        <div class="col-md-6">';
        $fields .= '            <label>Payment Date</label>';
        if(count($row)!=0 && $activity=='view'):
        $fields .=              '<p><strong>'.date('F d, Y',strtotime($row->payment_date)).'</strong></p>';
        else:
        $fields .= '            <input type="text" placeholder="mm/dd/yyyy" '.($activity=='modify'? 'value="'.date('m/d/Y',strtotime($row->payment_date)).'"' : '').' readonly id="payment_date" class="form-control date-picker" name="payment_date" />';    
        endif;
        $fields .= '        </div>';
        $fields .= '    </div>';
        $fields .= '</div>';
                
        $data['fields']=$fields;
        
        if(count($row)):
            $image_path = $row->image_path;
            $data['images'] = $this->getPaymentImages($image_path);    
        endif;
            
        return $data;
    }
    
    private function cash_deposit($row=array(),$activity='')
    {
        $fields='';
        $fields .= '    <div class="row">';
        $fields .= '            <div class="col-md-6">';
        $fields .= '                <div class="form-group">';
        $fields .= '                    <label>Receipt No.</label>';
        if(count($row)!=0 && $activity=='view'):
        $fields .= '                    <p><strong>'.$row->receipt_no.'</strong></p>';
        else:
        $fields .= '                    <input type="text" class="form-control receipt_no" value="'.(isset($row->receipt_no)?$row->receipt_no:'').'" name="receipt_no" />    ';
        endif;
        $fields .= '                </div>';
        $fields .= '            </div>';
        $fields .= '            <div class="col-md-6">';
        $fields .= '                <div class="form-group">';
        $fields .= '                    <label>To Account.</label>';
        if(count($row)!=0 && $activity=='view'):
        $fields .= '                    <p><strong>'.$row->account_receiver.'</strong></p>';
        else:
        $fields .= '                    <input type="text" class="form-control account_receiver" value="'.(isset($row->account_receiver)?$row->account_receiver:'').'" name="account_receiver" />    ';
        endif;
        $fields .= '                </div>';
        $fields .= '            </div>';
        $fields .= '    </div>';
        $fields .= '<div class="form-group">';
        $fields .= '    <div class="row">';
        $fields .= '        <div class="col-md-6">';
        if(count($row)!=0):
        $fields .= '            <input class="form-control" type="hidden" value="'.$row->cash_id.'" autocomplete="off" name="cash_id" />';
        endif;
        $fields .= '            <label>Amount</label>';
        if(count($row)!=0 && $activity=='view'):
        $fields .=              '<p><strong>PHP '.number_format($row->amount,2).'</strong></p>';
        else:
        $fields .= '            <input type="text" class="form-control cash-amount" '.($activity=='modify'? 'value="'.$row->amount.'"' : '').' name="cash_amount" />';
        endif;
        $fields .= '        </div>';
        $fields .= '        <div class="col-md-6">';
        $fields .= '            <label>Payment Date</label>';
        if(count($row)!=0 && $activity=='view'):
        $fields .=              '<p><strong>'.date('F d, Y',strtotime($row->payment_date)).'</strong></p>';
        else:
        $fields .= '            <input type="text" placeholder="mm/dd/yyyy" '.($activity=='modify'? 'value="'.date('m/d/Y',strtotime($row->payment_date)).'"' : '').' readonly id="payment_date" class="form-control date-picker" name="payment_date" />';    
        endif;
        $fields .= '        </div>';
        $fields .= '    </div>';
        $fields .= '</div>';
                
        $data['fields']=$fields;
        
        if(count($row)):
            $image_path = $row->image_path;
            $data['images'] = $this->getPaymentImages($image_path);    
        endif;
            
        return $data;
    }
    
    private function creditCard($row=array(),$activity='')
    {
        $fields='';
        
        $fields .= '<div class="form-group">';
        $fields .= '    <div class="row">';
        $fields .= '        <div class="col-md-6">';
        if(count($row)!=0):
        $fields .= '            <input class="form-control" type="hidden" value="'.$row->card_id.'" autocomplete="off" name="card_id" />';
        endif;
        $fields .= '            <label>Date of Settlement</label>';
        if(count($row)!=0 && $activity=='view'):
        $fields .=              '<p><strong>'.date('F d, Y',strtotime($row->settlement_date)).'</strong></p>';
        else:
        $fields .= '            <input type="text" class="form-control date-picker settlement" '.($activity=='modify'? 'value="'.date('m/d/Y',strtotime($row->settlement_date)).'"' : '').' readonly placeholder="mm/dd/yyyy" name="settlement" />';
        endif;
        $fields .= '        </div>';
        $fields .= '        <div class="col-md-6">';
        $fields .= '            <label>Card Number</label>';
        if(count($row)!=0 && $activity=='view'):
        $fields .=              '<p><strong>'.$row->card_no.'</strong></p>';
        else:
        $fields .= '            <input type="text" placeholder="Last 4 digits" '.($activity=='modify'? 'value="'.$row->card_no.'"' : '').' maxlength="4" class="form-control card_no" name="card_no" />';    
        endif;
        $fields .= '        </div>';
        $fields .= '    </div>';
        $fields .= '</div>';
        $fields .= '<div class="form-group">';
        $fields .= '    <div class="row">';
        $fields .= '        <div class="col-md-12">';
        $fields .= '            <label>Bank Name</label>';
        if(count($row)!=0 && $activity=='view'):
        $fields .=              '<p><strong>'.$row->bank_name.'</strong></p>';
        else:
        $fields .= '            <input type="text" class="form-control bank_name" '.($activity=='modify'? 'value="'.$row->bank_name.'"' : '').' name="bank_name" />';
        endif;
        $fields .= '        </div>';
        $fields .= '    </div>';
        $fields .= '</div>';
        $fields .= '<div class="form-group">';
        $fields .= '    <div class="row">';
        $fields .= '        <div class="col-md-6">';
        $fields .= '            <label>Approval Code</label>';
        if(count($row)!=0 && $activity=='view'):
        $fields .=              '<p><strong>'.$row->approval_code.'</strong></p>';
        else:
        $fields .= '            <input type="text" placeholder="xxxxxxxx" '.($activity=='modify'? 'value="'.$row->approval_code.'"' : '').' class="form-control approval_code" name="approval_code" />';
        endif;
        $fields .= '        </div>';
        $fields .= '        <div class="col-md-6">';
        $fields .= '            <label>Batch Number</label>';
        if(count($row)!=0 && $activity=='view'):
        $fields .=              '<p><strong>'.$row->batch_no.'</strong></p>';
        else:
        $fields .= '            <input type="text" '.($activity=='modify'? 'value="'.$row->batch_no.'"' : '').' class="form-control batch_no" maxlength="10" placeholder="xxxx" name="batch_no" />';
        endif;
        $fields .= '        </div>';
        $fields .= '    </div>';
        $fields .= '</div>';
        $fields .= '<div class="form-group">';
        $fields .= '    <div class="row">';
        $fields .= '        <div class="col-md-12">';
        $fields .= '            <label>Amount</label>';
        if(count($row)!=0 && $activity=='view'):
        $fields .=              '<p><strong>PHP '.number_format($row->credit_card_amount,2).'</strong></p>';
        else:
        $fields .= '                    <div class="input-group">';
        $fields .= '                        <div class="input-group-addon">';
        $fields .= '                          <span class="input-group-text" id="basic-addon1">PHP</span>';
        $fields .= '                        </div>';
        $fields .= '                        <input type="text" '.($activity=='modify'? 'value="'.$row->credit_card_amount.'"' : '').' placeholder="00.00" class="form-control credit_card_amount" name="credit_card_amount" />';
        $fields .= '                    </div>';
        endif;
        $fields .= '        </div>';
        $fields .= '    </div>';
        $fields .= '</div><hr>';
                
        $data['fields']=$fields;
        
        
        if(count($row)):
            $image_path = $row->image_path;
            $data['images'] = $this->getPaymentImages($image_path);    
        endif;
        
        return $data;
    }
    
    public function balance()
    {
        $sales_order_id = $this->input->post('tid');
        $data=array();
        $total_balance=0;
        $total = 0;
        $data['invoice_amount'] = 0;
        $data['total'] = 0;
        
        if(is_numeric($sales_order_id) && $sales_order_id!='')
        {
            $response = $this->TransactionRecordModel->getBalance($sales_order_id);
            if($response->num_rows()>0)
            {
                foreach($response->result() as $price)
                {
                    $total_balance += $price->total;
                }
                $total += $total_balance - (int) $this->payedPDCAmount($sales_order_id);
                $data['invoice_amount'] = "PHP ". number_format($total_balance,2);
                $data['total'] = $total;
            }
            else
            {
                $total = 0;
            }
            
        }
        else
        {
            $total = 0;
        }
        
        $data['total_balance'] = "PHP ". number_format($total,2);
        
        echo json_encode($data);
    }
    
    private function payedPDCAmount($sales_order_id)
    {
        $total_amount = 0;
        $response = $this->TransactionRecordModel->remittedAmount($sales_order_id);
        if($response->num_rows()>0)
        {
            foreach($response->result() as $pdc)
            {
                $total_amount += $pdc->amount;
            }
        }
        else
        {
            $total_amount = 0;   
        }
        
        return $total_amount;
    }
    
    public function tableData()
    {
        $sales_order_id = 22;//$this->input->post('sales_order_id');
        $payment_type = 2;
        $html = '';
        if(is_numeric($sales_order_id))
        {
            $response = $this->GenerateModel->getPaymentInfo($sales_order_id,$payment_type);
            if($response->num_rows()>0)
            {
                $row = $response->row();
                //$payment_type = $row->payment_type;
                $data['psr_name'] = $row->name;
                $data['invoice_no'] = $row->invoice_number;
                $data['so_number'] = $row->so_number;
                $data['payment_type'] = $payment_type;
                
                foreach($response->result() as $key=>$info)
                {
                    $html .= '<tr id="'.$info->check_no.'">';
                    $html .= '    <td>'.$info->account_no.'</td>';
                    $html .= '    <td>'.$info->account_name.'</td>';
                    $html .= '    <td>'.$info->bank_name.'</td>';
                    $html .= '    <td>'.$info->branch.'</td>';
                    $html .= '    <td>'.date('F d, Y', strtotime($info->cheque_date)).'</td>';
                    $html .= '    <td>'.$info->amount.'</td>';
                    $html .= '   <td>';
                    $html .= '        <div class="table-data-feature">';
                    $html .= '            <button class="item" data-toggle="tooltip" data-value="'.$info->remitted_id.'-'.$info->check_no.'-1" data-placement="top" title="" data-original-title="Reject">';
                    $html .= '                <i class="zmdi zmdi-close"></i>';
                    $html .= '            </button>';
                    $html .= '            <button class="item" data-toggle="tooltip" data-value="'.$info->remitted_id.'-'.$info->check_no.'-1" data-placement="top" title="" data-original-title="Accept">';
                    $html .= '                <i class="zmdi zmdi-badge-check"></i>';
                    $html .= '            </button>';
                    $html .= '        </div>';
                    $html .= '    </td>';
                    $html .= '</tr>';
                }
            }
            else
            {
                $html .= '<tr>';
                $html .= '<td colspan="4">No Data Found!</td>';
                $html .= '</tr>';
            }
        }
        else
        {
            $html .= '<tr>';
            $html .= '<td colspan="4">No Data Found!</td>';
            $html .= '</tr>';
        }
        
        $data['html'] = $html;
        
        echo json_encode($data);
    }
    
    public function updateCash()
    {
        $remit_id = $this->input->post('rid');
        
        if(is_numeric($remit_id)&&$remit_id!=0)
        {
            $response = $this->GenerateModel->getPaymentInfo($sales_order_id,$payment_type);
        }
        else
        {
            $data['result'] = 'fail';
            $data['message'] = 'Invalid Information';
        }
    }
    
    public function updatePdc()
    {
        $remit_id = $this->input->post('rid');
        
        if(is_numeric($remit_id)&&$remit_id!=0)
        {
            
        }
        else
        {
            $data['result'] = 'fail';
            $data['message'] = 'Invalid Information';
        }
    }
    
    public function updateCreditCard()
    {
        $remit_id = $this->input->post('rid');
        
        if(is_numeric($remit_id)&&$remit_id!=0)
        {
            $response = $this->GenerateModel->getPaymentInfo($sales_order_id,$payment_type);
            
        }
        else
        {
            $data['result'] = 'fail';
            $data['message'] = 'Invalid Information';
        }
    }
    
    public function view()
    {
        $payment_type = $this->input->post('payment_type');
        $dtc = $this->input->post('dtc');
        $activity = $this->input->post('activity');
        $transaction = $this->input->post('tid');
        $payment_id = $this->input->post('pid');
        
        $data = array();
        
        switch($payment_type)
        {
            case '1' : 
                        $row = $this->cashInfo($transaction,$payment_type,$dtc);
                        $response = $this->cash($row,$activity);
                        $data['fields'] = $response['fields'];
                        $data['images'] = $response['images'];
                        $data['payment_type'] = 'Cash';
                        $data['created_date'] = date('F d, Y',strtotime($row->created_date));
                        break;
            case '2' : 
                        $row = $this->cashInfo($transaction,$payment_type,$dtc);
                        $response = $this->pdc2($row,$activity);
                        $data['fields'] = $response['fields'];
                        $data['images'] = $response['images'];
                        $data['payment_type'] = 'PDC';
                        $data['created_date'] = date('F d, Y',strtotime($row->created_date));
                        break;
            case '3' : 
                        $row = $this->cashInfo($transaction,$payment_type,$dtc);
                        $response = $this->creditCard($row,$activity);
                        $data['fields'] = $response['fields'];
                        $data['images'] = $response['images'];
                        $data['payment_type'] = 'Credit Card';
                        $data['created_date'] = date('F d, Y',strtotime($row->created_date));
                        break;
            case '4' : 
                        $row = $this->cashInfo($transaction,$payment_type,$dtc);
                        $response = $this->cash_deposit($row,$activity);
                        $data['fields'] = $response['fields'];
                        $data['images'] = $response['images'];
                        $data['payment_type'] = 'Cash Deposit';
                        $data['created_date'] = date('F d, Y',strtotime($row->created_date));
                        break;
            case '5' : 
                        $row = $this->cashInfo($transaction,$payment_type,$dtc);
                        $response = $this->pdc2($row,$activity);
                        $data['fields'] = $response['fields'];
                        $data['images'] = $response['images'];
                        $data['payment_type'] = 'PDC Deposit';
                        $data['created_date'] = date('F d, Y',strtotime($row->created_date));
                        break;
            case '6' : 
                        $row = $this->cashInfo($transaction,$payment_type,$dtc);
                        $response = $this->mobile_banking($row,$activity);
                        $data['fields'] = $response['fields'];
                        $data['images'] = $response['images'];
                        $data['payment_type'] = 'Mobile Banking';
                        $data['created_date'] = date('F d, Y',strtotime($row->created_date));
                        break;
        }
        
        $data['fields'] = $data['fields'];
        $data['comments'] = $this->paymentComment($payment_id,$payment_type);
        
        echo json_encode($data);
    }
    
    private function paymentComment($payment_id,$payment_type)
    {
        $this->load->model('NotificationModel');
        $html = '';
        $response = $this->NotificationModel->paymentComment($payment_id,$payment_type);
        if($response->num_rows()>0)
        {
            $html .= '<h4><i class="fa fa-comment"></i> Comments:</h4><hr>';
            $html .= '<div style="overflow-y:scroll; height:250px;">';
            foreach($response->result() as $records)
            {
                $html .= '<div class="itemdiv commentdiv">';
                $html .= '   <div class="user">';
                $html .= '       <img alt="'.$records->name.'" src="'.site_url('public/assets/images/avatars/avatar2.png').'">';
                $html .= '    </div>';

                $html .= '    <div class="body">';
                $html .= '        <div class="'.$records->name.'">';
                $html .= '            <a href="#">'.$records->name.'</a>';
                $html .= '        </div>';

                $html .= '        <div class="time">';
                $html .= '            <i class="ace-icon fa fa-calendar"></i>';
                $html .= '            <span class="orange">'.date('F d, Y H:i A',strtotime($records->created_date)).'</span>';
                $html .= '        </div>';

                $html .= '        <div class="text">';
                $html .= '            <i class="ace-icon fa fa-quote-left"></i>';
                $html .=                $records->message;
                $html .= '        </div>';
                $html .= '    </div>';
                $html .= '</div>';
            }
            $html .= '</div>';
        }
        else
        {
            $html = '';
        }
        
        return $html;
    }
    
    public function getPaymentImages($images)
    {
        $html = '';
        $html = '<hr><h4><i class="fa fa-image"></i> Image:</h4><hr>';
        $html .= '<div class="row">';
        $html .= '<div class="col-md-4">';
        $html .= '<a href="'.site_url($images).'" target="_blank">';
        $html .= '<img src="'.site_url($images).'" class="img-thumbnail" alt="payment image" style="width:100%;">';
        $html .= '</a>';
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }
    
    private function cashInfo($sales_order_id,$payment_type,$created_date)
    {
        $row=array();
        $created_date = date('Y-m-d H:i:s',$created_date);
        
        $response = $this->GenerateModel->getPaymentRecordInfo($sales_order_id,$created_date,$payment_type);
        if($response->num_rows()>0):
            $row = $response->row();
        endif;
        
        return $row;
    }
    
    public function modify()
    {
        $payment_type = $this->input->post('payment_type');
        $dtc = $this->input->post('dtc');
        $activity = $this->input->post('activity');
        $sales_order_id = $this->input->post('tid');
        $payment_id = $this->input->post('pid');
        
        $data = array();
        $html = '';
        $html .= '<input type="hidden" value="'.$sales_order_id.'" name="tid" />';
        $html .= '<input type="hidden" value="'.$dtc.'" name="dtc" />';
        $html .= '<input type="hidden" value="'.$payment_type.'" name="payment_type" />';
        switch($payment_type)
        {
            case '1' : 
                        $row = $this->cashInfo($sales_order_id,$payment_type,$dtc);
                        $response = $this->cash($row,$activity);
                        $data['fields'] = $response['fields'];
                        $data['images'] = $response['images'];
                        $data['payment_type'] = 'Cash';
                        $data['created_date'] = date('F d, Y',strtotime($row->created_date));
                        break;
            case '2' : 
                        $row = $this->cashInfo($sales_order_id,$payment_type,$dtc);
                        $response = $this->pdc2($row,$activity);
                        $data['fields'] = $response['fields'];
                        $data['images'] = $response['images'];
                        $data['payment_type'] = 'PDC';
                        $data['created_date'] = date('F d, Y',strtotime($row->created_date));
                        break;
            case '3' : 
                        $row = $this->cashInfo($sales_order_id,$payment_type,$dtc);
                        $response = $this->creditCard($row,$activity);
                        $data['fields'] = $response['fields'];
                        $data['images'] = $response['images'];
                        $data['payment_type'] = 'Credit Card';
                        $data['created_date'] = date('F d, Y',strtotime($row->created_date));
                        break;
            case '4' : 
                        $row = $this->cashInfo($sales_order_id,$payment_type,$dtc);
                        $response = $this->cash_deposit($row,$activity);
                        $data['fields'] = $response['fields'];
                        $data['images'] = $response['images'];
                        $data['payment_type'] = 'Cash Deposit';
                        $data['created_date'] = date('F d, Y',strtotime($row->created_date));
                        break;
            case '5' : 
                        $row = $this->cashInfo($sales_order_id,$payment_type,$dtc);
                        $response = $this->pdc2($row,$activity);
                        $data['fields'] = $response['fields'];
                        $data['images'] = $response['images'];
                        $data['payment_type'] = 'PDC Deposit';
                        $data['created_date'] = date('F d, Y',strtotime($row->created_date));
                        break;
            case '6' : 
                        $row = $this->cashInfo($sales_order_id,$payment_type,$dtc);
                        $response = $this->mobile_banking($row,$activity);
                        $data['fields'] = $response['fields'];
                        $data['images'] = $response['images'];
                        $data['payment_type'] = 'Mobile Banking';
                        $data['created_date'] = date('F d, Y',strtotime($row->created_date));
                        break;
        }
        
        $data['fields'] = $html.$data['fields'];
        $data['comments'] = $this->paymentComment($payment_id,$payment_type);
        
        echo json_encode($data);
    }
    
}


?>