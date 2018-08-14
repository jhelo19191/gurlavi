<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once('Base.php');

class Collection extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('CollectionModel');
        $this->load->model('BaseModel');
    }
    
    public function insert()
    {
        $collection_number = $this->input->post('cr_no');
        $sales_order_id = $this->uri->segment(3);
        $collection_date = $this->input->post('collect_date');
        $comments = $this->input->post('comments');
        $target_dir = 'uploads/collection/';
        
        $array_container = array("Collection Number"=>$collection_number,"Collection Date"=>$collection_date);
        
        $data['comments'] = $comments;
        
        $response = $this->checkFields($array_container);
        
        if($response['result']=='success')
        {
            $payment_count = $this->CollectionModel->countPayments($sales_order_id);
            if($payment_count>0)
            {
                $response_rows = $this->CollectionModel->checkSalesOrderId($sales_order_id);
                if($response_rows==0)
                {
                    $num_rows = $this->CollectionModel->checkCollectionNumber($collection_number);
                    if($num_rows==0)
                    {
                        $response = $this->CollectionModel->insertCollection($sales_order_id,$collection_number,date('Y-m-d',strtotime($collection_date)),$comments,$this->session->userdata('account_id'));
                        if($response==1)
                        {
                            $data['cr_no'] = $collection_number;
                            $data['collect_date'] = $collection_date;
                            $this->BaseModel->updateStatus($sales_order_id,0,4,1);
                            $this->CollectionModel->updateSalesOrderStatus($sales_order_id);
                            $data['result'] = 'success';
                            $data['message'] = 'Record saved successfully.';
                        }
                        else
                        {
                            $data['result'] = 'fail';
                            $data['message'] = 'Unable to save the records.';
                        }
                        
                    } 
                }
                else
                {
                    $response = $this->CollectionModel->updateCollection($sales_order_id,$collection_number,date('Y-m-d',strtotime($collection_date)),$comments);
                    //if($response==1)
                    //{
                        $this->CollectionModel->updateSalesOrderStatus($sales_order_id);
                        $this->BaseModel->updateStatus($sales_order_id,0,4,1);
                        $data['cr_no'] = $collection_number;
                        $data['collect_date'] = $collection_date;
                        $data['result'] = 'success';
                        $data['message'] = 'Record updated successfully.';
                    //}
                    //else
                    //{
                    //    $data['result'] = 'fail';
                    //    $data['message'] = 'Unable to save the records.';
                    //}
                }   
            }
            else
            {
                $data['result'] = 'fail';
                $data['message'] = 'Please add payment first before submitting the form.';
            }  
        }
        else
        {
            $data['result'] = $response['result'];
            $data['message'] = $response['message'];
        }
        
        $this->information('',$data);
    }
    
    public function information($pages='',$responsed=array())
    {
        if(!$this->session->userdata('logged_in'))
        {
            $this->login();
        }
        else
        {
            $data['active_module'] = 'transaction';
            $data['header_title'] = 'Invoice Information';
			$data['main_crubs'] = 'Collection Record';
			$data['sub_crubs'] = 'Information';
            
            $sales_order_id = $this->uri->segment(3);
            $stage_type = $this->uri->segment(4);
            
            $payment_id = $this->uri->segment(5);
            $payment_type = $this->uri->segment(6);
            
            $response = $this->viewInfo($sales_order_id);
            
            $this->updatePaymentCommentFlag($payment_id,$payment_type);
            
            if(count($response)>0)
            {
                $response_collection = $this->CollectionModel->getCollectionInfo($sales_order_id);
                
                if($response_collection->num_rows()>0):
                    $row = $response_collection->row();
                    $data['cr_no'] = $row->cr_no;
                    $data['collect_date'] = $row->collect_date;
                    $data['comments'] = $row->message;
                endif;
                
                $data['psr_name'] = $response['psr_name'];
                $data['approved_date'] = $response['approved_date'];
                $data['delivery_date'] = $response['delivery_date'];
                $data['actual_delivery_date'] = $response['actual_delivery_date'];
                $data['so_date'] = $response['so_date'];
                $data['sales_order_no'] = $response['sales_order_no'];
                $data['status'] = $response['status'];
                
                $data['sales_order_id'] = $sales_order_id;
                $data['stage_type'] = $stage_type;
                $data['invoice_number'] = $response['invoice_number'];
                $data['invoice_date'] = $response['invoice_date'];
                $data['status_no'] = $response['status_no'];
                        
                $data['customer_name'] = $response['customer_name'];
                $data['shipto'] = $response['shipto'];
                $data['contact_no'] = $response['contact_no'];
                $data['tin'] = $response['tin'];
                $data['items'] = $response['items'];
                $data['total'] = $response['total'];
                
                $response_table = $this->paymentRecord($sales_order_id);
                $data['payment_list'] = $response_table['html'];
                $data['total_amount'] = $response_table['total'];
                
                if(isset($responsed['result'])):
                    $data['result'] = $responsed['result'];
                    $data['message'] = $responsed['message'];
                    $data['comments'] = $responsed['comments'];
                endif;
                
                
                $this->render('pages/request/collection_view',$data);
            }
            else
            {
                redirect();   
            }
        }
    }
    
    private function updatePaymentCommentFlag($payment_id,$payment_type)
    {
        if($payment_id!=''&&is_numeric($payment_id)&&$payment_type!=''&&is_numeric($payment_type)):
            $this->CollectionModel->updatePaymentCommentFlag($payment_id,$payment_type);
        endif;
    }
    
    private function paymentRecord($sales_order_id)
    {
        $html = '';
        $active_page = $this->uri->segment(4);
        $data = array();
        $iter=1;
        $total_reject = 0;
        $total_cleared = 0;
        $total = 0;
        $cash = $this->sortCashRecord($sales_order_id);
        $credit = $this->sortCreditCardRecord($sales_order_id);
        $pdc = $this->sortPdcRecord($sales_order_id);
        $mobile_banking = $this->sortMobileBankingRecord($sales_order_id);
        
        $merge = array_merge($cash,$credit);
        $table_data = array_merge($merge,$pdc);
        $table_data = array_merge($table_data,$mobile_banking);
        
        if(count($table_data)>0)
        {
            foreach($table_data as $records)
            {
                $image_path = $records['image_path'];
                
                if($records['flag']!=5):
                    $total += $records['amount'];
                else:
                    $total_reject++;
                endif;
                
                if($records['flag']==4):
                    $total_cleared++;
                endif;
                
                $html .='<tr id="'.$records['created_date'].'"'.($records['flag']==5? ' style="background-color: #d62e2ec4;color:white;"' : '').'>';
                $html .='    <td>';
                $html .=        $iter;
                $html .='    </td>';
                $html .='    <td>';
                $html .=        $records['payment_code'];
                $html .='    </td>';
                $html .='    <td>';
                $html .=        $records['payment_type'];
                $html .='    </td>';
                $html .='    <td>';
                $html .=        number_format($records['amount']);
                $html .='     </td>';
                //$html .='     <td>';
                //$html .='        <a href="'.site_url($image_path).'" target="_blank" data-value="'.site_url($image_path).'">';
                //$html .='            <i class="fa fa-image"></i> View Image';
                //$html .='        </a>';
                //$html .='    </td>';
                $html .='    <td>';
                $html .=        date('m-d-Y',strtotime($records['payment_date']));
                $html .='    </td>';
                $html .='    <td class="status">';
                $html .=        $this->paymentStatus($records['flag']);
                $html .='    </td>';
                $html .='    <td style="text-align: center;">';
                $html .='        <a  href="#" class="btn btn-info show-view" data-holder="'.$records['payment_id'].'" data-action="view" data-value="'.$records['payment_type_id'].'_'.$records['created_date'].'_'.$sales_order_id.'" title="View More Details"><i class="fa fa-eye"></i></a>';
                if($records['flag']==0 && $active_page=='request' || $records['flag']==5 && $active_page=='request'):
                $html .='        <a  href="#" data-action="update" class="btn btn-warning show-update" data-holder="'.$records['payment_id'].'" data-value="'.$records['payment_type_id'].'_'.$records['created_date'].'_'.$sales_order_id.'" title="Modify"><i class="fa fa-edit"></i></a>';
                $html .='        <a  href="#" data-action="delete" class="btn btn-danger show-delete" data-value="'.$records['payment_type_id'].'_'.$records['created_date'].'_'.$sales_order_id.'" data-value="delete" title="Delete"><i class="fa fa-trash"></i></a>';
                endif;
                $html .='    </td>';
                
                $html .='</tr>';
                
                $iter++;
            }
        }
        else
        {
            $html .='<tr>';
            $html .='    <td colspan="7">';
            $html .=        'No Data Found!';
            $html .='    </td>';
            $html .='</tr>';
        }
        
        $data['html'] = $html;
        $data['total'] = $total;
        $data['total_records'] = count($table_data);
        $data['total_rejects'] = $total_reject;
        $data['total_cleared'] = $total_cleared;
        
        return $data;
    }
    
    private function sortCashRecord($sales_order_id)
    {
        $table_data = array();
        $response = $this->CollectionModel->getCashRecord($sales_order_id);
        if($response->num_rows()>0):
            foreach($response->result() as $cash)
            {
                $table_data[] = array(
                                        'payment_type' => ($cash->transaction_type=='cash'? 'Cash' : 'Cash Deposit'),
                                        'payment_type_id' => ($cash->transaction_type=='cash'? 1 : 4),
                                        'amount' => $cash->amount,
                                        'payment_id' => $cash->cash_id,
                                        'payment_date' => $cash->payment_date,
                                        'payment_code' => 'N/A',
                                        'flag' => $cash->flag,
                                        'image_path' => $cash->image_path,
                                        'created_date' => strtotime($cash->created_date)
                                    );
            }
        endif;
        
        return $table_data;
    }
    
    private function sortCreditCardRecord($sales_order_id)
    {
        $table_data = array();
        $response = $this->CollectionModel->getCreditCardRecord($sales_order_id);
        if($response->num_rows()>0):
            foreach($response->result() as $cash)
            {
                $table_data[] = array(
                                        'payment_type' => 'Credit Card',
                                        'payment_type_id' => 3,
                                        'amount' => $cash->credit_card_amount,
                                        'payment_id' => $cash->card_id,
                                        'payment_date' => $cash->settlement_date,
                                        'payment_code' => $cash->approval_code,
                                        'flag' => $cash->flag,
                                        'image_path' => $cash->image_path,
                                        'created_date' => strtotime($cash->created_date)
                                    );
            }
        endif;
        
        return $table_data;
    }
    
    private function sortPdcRecord($sales_order_id)
    {
        $table_data = array();
        $response = $this->CollectionModel->getPdcRecord($sales_order_id);
        if($response->num_rows()>0):
            foreach($response->result() as $cash)
            {
                $table_data[] = array(
                                        'payment_type' => ($cash->transaction_type=='pdc'? 'PDC' : 'PDC Deposit'),
                                        'payment_type_id' => ($cash->transaction_type=='pdc'? 2 : 5),
                                        'payment_id' => $cash->pdc_id,
                                        'amount' => $cash->amount,
                                        'payment_date' => $cash->cheque_date,
                                        'payment_code' => $cash->check_no,
                                        'flag' => $cash->flag,
                                        'image_path' => $cash->image_path,
                                        'created_date' => strtotime($cash->created_date)
                                    );
            }
        endif;
        
        return $table_data;
    }
    
    private function sortMobileBankingRecord($sales_order_id)
    {
        $table_data = array();
        $response = $this->CollectionModel->getMobileBankingRecord($sales_order_id);
        if($response->num_rows()>0):
            foreach($response->result() as $cash)
            {
                $table_data[] = array(
                                        'payment_type' => 'Mobile Banking',
                                        'payment_type_id' => 6,
                                        'payment_id' => $cash->bank_id,
                                        'amount' => $cash->amount,
                                        'payment_date' => $cash->created_date,
                                        'flag' => $cash->flag,
                                        'image_path' => $cash->image_path,
                                        'created_date' => strtotime($cash->created_date)
                                    );
            }
        endif;
        
        return $table_data;
    }
    
    private function viewInfo($sales_order_id)
    {
        $data = array();
        
        $response = $this->CollectionModel->getSalesOrderInformation($sales_order_id);
        
        if($response->num_rows()>0)
        {
            $html='';
            $total = 0;
            $iter=1;
            $row = $response->row();
            $data['psr_name'] = $row->psr_name;
            $data['approved_date'] = $row->approved_date;
            $data['delivery_date'] = $row->delivery_date;
            $data['so_date'] = $row->so_date;
            $data['sales_order_no'] = $row->sales_order_no;
            $data['invoice_number'] = $row->invoice_no;
            $data['invoice_date'] = $row->invoice_date;
            $data['actual_delivery_date'] = $row->actual_delivery_date;
            
            $data['status'] = $this->requestStatus($row->status);
            $data['status_no'] = $row->status;
            
            $data['customer_name'] = $row->customer_name;
            $data['shipto'] = $row->ship_to;
            $data['contact_no'] = $row->contact_no;
            $data['tin'] = $row->tin;
            
            $response = $this->CollectionModel->getRegProducts($sales_order_id);
            if($response->num_rows()>0)
            {
                foreach($response->result() as $items)
                {
                    $html .= '<tr>';
                    $html .= '<td>'.$iter.'</td>';
                    $html .= '<td>'.$items->product_name.'</td>';
                    $html .= '<td>'.$items->description.'</td>';
                    $html .= '<td>'.$items->quantity.'</td>';
                    $html .= '<td>'.$items->unit.'</td>';
                    $html .= '<td>PHP '.number_format($items->price).'</td>';
                    $html .= '<td>PHP '.number_format(($items->quantity * $items->price)).'</td>';
                    $html .= '</tr>';
                    
                    $total = $total + ($items->quantity * $items->price);
                    $iter++;
                }
            }
            else
            {
                    $html .= '<tr>';
                    $html .= '<td colspan="6">No Data Found!</td>';
                    $html .= '</tr>';
            }
            
            $data['total'] = $total;
            $data['items'] = $html;
        }
        
        return $data;
    }
    
    public function payment()
    {
        $payment_type = $this->input->post('payment_type');
        $terms = $this->input->post('terms');
        $sales_order_id = $this->input->post('sales_order_id');
        $comments = $this->input->post('comment');
        
        $data = array();
        
        if(count($_FILES)>0)
        {
            switch($payment_type)
            {
                case '1' :
                        $cash_amount = $this->input->post('cash_amount');
                        $payment_date = $this->input->post('payment_date');
                        $target_dir = 'uploads/cash/';
                        
                        $data = $this->cashInsertPayment($sales_order_id,$cash_amount,$payment_date,$comments,$target_dir,'cash');
                        
                        break;
                case '2' :
                        $bank_name = $this->input->post('bank_name');
                        $branch = $this->input->post('branch');
                        $account_name = $this->input->post('account_name');
                        $account_number = $this->input->post('account_number');
                        $check_date = $this->input->post('check_date');
                        $check_number = $this->input->post('check_number');
                        $check_amount = $this->input->post('check_amount');
                        $target_dir = 'uploads/pdc/';
                        
                        $data = $this->pdcInsertPayment($sales_order_id,$bank_name,$branch,$account_name,$account_number,
                                                    $check_date,$check_number,$check_amount,$terms,$comments,$target_dir,'pdc');
                        break;
                case '3' :
                        $settlement = $this->input->post('settlement');
                        $card_no = $this->input->post('card_no');
                        $bank_name = $this->input->post('bank_name');
                        $approval_code = $this->input->post('approval_code');
                        $batch_no = $this->input->post('batch_no');
                        $credit_card_amount = $this->input->post('credit_card_amount');
                        $target_dir = 'uploads/credit_card/';
                        
                        $data = $this->creditCardInsertPayment($sales_order_id,$settlement,$card_no,$bank_name,
                                             $approval_code,$batch_no,$credit_card_amount,$comments,$terms,$target_dir);
                        
                        break;
                case '4' :
                        $receipt_no = $this->input->post('receipt_no');
                        $account_receiver = $this->input->post('account_receiver');
                        $cash_amount = $this->input->post('cash_amount');
                        $payment_date = $this->input->post('payment_date');
                        $target_dir = 'uploads/cash/';
                        
                        $data = $this->cashInsertPayment($sales_order_id,$cash_amount,$payment_date,$comments,$target_dir,'cash_deposit');
                        
                        if($data['result'] == 'success'):
                            $this->CollectionModel->insertCashDeposit($data['insert_id'],$receipt_no,$account_receiver);
                        endif;
                        
                        break;
            
                case '5' :
                        $bank_name = $this->input->post('bank_name');
                        $branch = $this->input->post('branch');
                        $account_name = $this->input->post('account_name');
                        $account_number = $this->input->post('account_number');
                        $check_date = $this->input->post('check_date');
                        $check_number = $this->input->post('check_number');
                        $check_amount = $this->input->post('check_amount');
                        $target_dir = 'uploads/pdc/';
                        
                        $data = $this->pdcInsertPayment($sales_order_id,$bank_name,$branch,$account_name,$account_number,
                                                    $check_date,$check_number,$check_amount,$terms,$comments,$target_dir,'pdc_deposit');
                        break;
            
                case '6' :
                        $banking_ref_no = $this->input->post('banking_ref_no');
                        $money_type = $this->input->post('money_type');
                        $from_account = $this->input->post('from_account');
                        $bt_amount = $this->input->post('bt_amount');
                        $destination_account = $this->input->post('destination_account');
                        
                        $target_dir = 'uploads/banking/';
                        
                        $data = $this->bankingInsertPayment($sales_order_id,$banking_ref_no,$money_type,$from_account,
                                                            $bt_amount,$destination_account,$target_dir,$comments);
                        break;
            }    
        }
        else
        {
            $data['result'] = 'fail';
            $data['message'] = 'Please upload the proof of payment.';
        }
        
        echo json_encode($data);
    }
    
    private function creditCardInsertPayment($sales_order_id,$settlement,$card_no,$bank_name,
                                             $approval_code,$batch_no,$credit_card_amount,$comments,$terms,$target_dir)
    {
        $container_array=array('Bank Name'=>$bank_name,'Settlement Date'=>$settlement,'Card Number'=>$card_no,
                                               'Approval Code'=>$approval_code,'Batch Number'=>$batch_no,
                                               'Amount'=>$credit_card_amount);
                        
        $response = $this->checkFields($container_array);
        
        if($response['result'] == 'success')
        {
            $response = $this->CollectionModel->checkBatchNumber($batch_no);
            if($response==0)
            {
                $image_response = $this->uploadImages($target_dir);
                if($image_response['result']=='success')
                {
                    $response_queryy = $this->CollectionModel->insertCredit($sales_order_id,date('Y-m-d',strtotime($settlement)),
                                                                            $card_no,$bank_name,$approval_code,$batch_no,$credit_card_amount,
                                                                            $terms,$image_response['image_path']);
                    if($response_queryy!='' || $response_queryy!=0)
                    {
                        if($comments!=''):
                            $this->CollectionModel->insertPaymentComments($response_queryy,3,$comments,$this->session->userdata('account_id'),$this->session->userdata('account_id'));
                        endif;
                        
                        $data['result'] = 'success';
                        $data['message'] = 'Payment information successfully saved.';
                    }
                    else
                    {
                        $data['result'] = 'fail';
                        $data['message'] = 'Unable to save the payment information.';
                    }    
                }
                else
                {
                    $data['result'] = $image_response['result'];
                    $data['message'] = $image_response['message'];
                }
                
            }
            else
            {
                $data['result'] = 'fail';
                $data['message'] = 'Cheque Number is already used.';
            }
        }
        else
        {
            $data['result'] = $response['result'];
            $data['message'] = $response['message'];
        }
        
        return $data;
    }
    
    private function cashInsertPayment($sales_order_id,$cash_amount,$payment_date,$comments,$target_dir,$transaction_type)
    {
        $container_array=array('Cash Amount'=>$cash_amount,'Payment Date'=>$payment_date);
        
        $response = $this->checkFields($container_array);
        
        if($response['result'] == 'success')
        {
            $image_response = $this->uploadImages($target_dir);
            if($image_response['result']=='success')
            {
                $response_queryy = $this->CollectionModel->insertCash($sales_order_id,$cash_amount,date('Y-m-d',strtotime($payment_date)),$image_response['image_path'],$transaction_type);
                if(!empty($response_queryy))
                {
                    if($comments!=''):
                        $this->CollectionModel->insertPaymentComments($response_queryy,1,$comments,$this->session->userdata('account_id'),$this->session->userdata('account_id'));
                    endif;
                    
                    $data['insert_id'] = $response_queryy;
                    $data['result'] = 'success';
                    $data['message'] = 'Payment information successfully saved.';
                }
                else
                {
                    $data['result'] = 'fail';
                    $data['message'] = 'Unable to save the payment information.';
                }    
            }
            else
            {
                $data['result'] = $image_response['result'];
                $data['message'] = $image_response['message'];
            }
            
        }
        else
        {
            $data['result'] = $response['result'];
            $data['message'] = $response['message'];
        }
        
        return $data;
    }
    
    private function bankingInsertPayment($sales_order_id,$reference_no,$money_type,$from_account,
                                                        $amount,$destination_account,$target_dir,$comments)
    {
        $container_array=array('Reference Number'=>$reference_no,'Money Type'=>$money_type,'Account Name'=>$from_account,
                                               'Amount'=>$amount,'Destination Account'=>$destination_account);
                        
        $response = $this->checkFields($container_array);
        
        if($response['result'] == 'success')
        {
            $response = $this->CollectionModel->checkBankingRefNo($reference_no);
            if($response==0)
            {
                $image_response = $this->uploadImages($target_dir);
                if($image_response['result']=='success')
                {
                    $response_queryy = $this->CollectionModel->insertBankingInfo($sales_order_id,$reference_no,$money_type,$from_account,
                                                                                 $amount,$destination_account,$image_response['image_path']);
                    if(!empty($response_queryy))
                    {
                        if($comments!=''):
                            $this->CollectionModel->insertPaymentComments($response_queryy,6,$comments,$this->session->userdata('account_id'),$this->session->userdata('account_id'));
                        endif;
                        
                        $data['result'] = 'success';
                        $data['message'] = 'Payment information successfully saved.';
                    }
                    else
                    {
                        $data['result'] = 'fail';
                        $data['message'] = 'Unable to save the payment information.';
                    }    
                }
                else
                {
                    $data['result'] = $image_response['result'];
                    $data['message'] = $image_response['message'];
                }
                
            }
            else
            {
                $data['result'] = 'fail';
                $data['message'] = 'Reference Number is already used.';
            }
        }
        else
        {
            $data['result'] = $response['result'];
            $data['message'] = $response['message'];
        }
        
        return $data;
    }
    
    private function pdcInsertPayment($sales_order_id,$bank_name,$branch,$account_name,$account_number,$check_date,$check_number,
                                      $check_amount,$terms,$comments,$target_dir,$transaction_type)
    {
        $container_array=array('Bank Name'=>$bank_name,'Branch'=>$branch,'Account Name'=>$account_name,
                                               'Account Number'=>$account_number,'Cheque Date'=>$check_date,
                                               'Cheque Number'=>$check_number,'Cheque Amount'=>$check_amount,'Terms'=>$terms);
                        
        $response = $this->checkFields($container_array);
        
        if($response['result'] == 'success')
        {
            $response = $this->CollectionModel->checkChequeNumber($check_number);
            if($response==0)
            {
                $image_response = $this->uploadImages($target_dir);
                if($image_response['result']=='success')
                {
                    $response_queryy = $this->CollectionModel->insert($sales_order_id,$bank_name,$branch,
                                                   $account_name,$account_number,date('Y-m-d',strtotime($check_date)),
                                                   $check_number,$check_amount,$terms,$image_response['image_path'],$transaction_type);
                    if(!empty($response_queryy))
                    {
                        if($comments!=''):
                            $this->CollectionModel->insertPaymentComments($response_queryy,2,$comments,$this->session->userdata('account_id'),$this->session->userdata('account_id'));
                        endif;
                        
                        $data['result'] = 'success';
                        $data['message'] = 'Payment information successfully saved.';
                    }
                    else
                    {
                        $data['result'] = 'fail';
                        $data['message'] = 'Unable to save the payment information.';
                    }    
                }
                else
                {
                    $data['result'] = $image_response['result'];
                    $data['message'] = $image_response['message'];
                }
                
            }
            else
            {
                $data['result'] = 'fail';
                $data['message'] = 'Cheque Number is already used.';
            }
        }
        else
        {
            $data['result'] = $response['result'];
            $data['message'] = $response['message'];
        }
        
        return $data;
    }
    
    public function update()
    {
        $payment_type = $this->input->post('payment_type');
        $sales_order_id = $this->input->post('sales_order_id');
        $comments = $this->input->post('comment');
        $payment_id = $this->input->post('pid');
        
        $data = array();
        
            switch($payment_type)
            {
                case '1' :
                        $cash_amount = $this->input->post('cash_amount');
                        $payment_date = $this->input->post('payment_date');
                        $cash_id = $this->input->post('cash_id');
                        $target_dir = 'uploads/cash/';
                        
                        $data = $this->updateCashPayment($cash_id,$cash_amount,$payment_date,$target_dir,$comments,$payment_id);
                        
                        break;
                case '2' :
                        $bank_name = $this->input->post('bank_name');
                        $branch = $this->input->post('branch');
                        $account_name = $this->input->post('account_name');
                        $account_number = $this->input->post('account_number');
                        $check_date = $this->input->post('check_date');
                        $check_number = $this->input->post('check_number');
                        $check_amount = $this->input->post('check_amount');
                        $pdc_id = $this->input->post('pdc_id');
                        $target_dir = 'uploads/pdc/';
                        
                        $data = $this->updatePdcPayment($pdc_id,$bank_name,$branch,$account_name,$account_number,$check_date,
                                                        $check_number,$check_amount,$comments,$payment_id,$target_dir);
                        
                        break;
                case '3' :
                        $settlement = $this->input->post('settlement');
                        $card_no = $this->input->post('card_no');
                        $bank_name = $this->input->post('bank_name');
                        $approval_code = $this->input->post('approval_code');
                        $batch_no = $this->input->post('batch_no');
                        $credit_card_amount = $this->input->post('credit_card_amount');
                        $card_id = $this->input->post('card_id');
                        $target_dir = 'uploads/credit_card/';
                        
                        $container_array=array('Bank Name'=>$bank_name,'Settlement Date'=>$settlement,'Card Number'=>$card_no,
                                               'Approval Code'=>$approval_code,'Batch Number'=>$batch_no,
                                               'Amount'=>$credit_card_amount);
                        
                        $response = $this->checkFields($container_array);
                        
                        if($response['result'] == 'success')
                        {
                            $response = $this->CollectionModel->checkUpdateBatchNo($card_id,$batch_no);
                            if($response==0)
                            {
                                $image_row = $this->CollectionModel->getCreditInfoByID($card_id);
                                if(count($_FILES)>0):
                                    $image_response = $this->uploadImages($target_dir);
                                    if($image_response['result']=='success'):
                                        unlink($image_row->image_path);
                                    endif;
                                else:
                                    $image_response['image_path'] = '';
                                endif;
                                
                                $response_queryy = $this->CollectionModel->updateCreditInfo($card_id,date('Y-m-d',strtotime($settlement)),$card_no,
                                                                                            $bank_name,$approval_code,$batch_no,
                                                                                            $credit_card_amount,$image_response['image_path'],3);
                                
                                if($response_queryy==1)
                                {
                                    if($comments!=''):
                                        $this->CollectionModel->insertPaymentComments($payment_id,3,$comments,$this->session->userdata('account_id'),$this->session->userdata('account_id'));
                                    endif;
                                
                                    $data['result'] = 'success';
                                    $data['message'] = 'Payment information updated successfully.';
                                }
                                else
                                {
                                    $data['result'] = 'fail';
                                    $data['message'] = 'Payment information is on update.';
                                }    
                            }
                            else
                            {
                                $data['result'] = 'fail';
                                $data['message'] = 'Batch Number is already used.';
                            }
                        }
                        else
                        {
                            $data['result'] = $response['result'];
                            $data['message'] = $response['message'];
                        }
                        
                        break;
                case '4' :
                            $receipt_no = $this->input->post('receipt_no');
                            $account_receiver = $this->input->post('account_receiver');
                            $cash_amount = $this->input->post('cash_amount');
                            $payment_date = $this->input->post('payment_date');
                            $cash_id = $this->input->post('cash_id');
                            $target_dir = 'uploads/cash/';
                            
                            $data = $this->updateCashPayment($cash_id,$cash_amount,$payment_date,$target_dir,$comments,$payment_id);
                            
                            $check_num_rows = $this->CollectionModel->checkUpdateCashReceiptNo($cash_id,$receipt_no);
                            
                            if($check_num_rows==1)
                            {
                                $data['result'] = 'fail';
                                $data['message'] = 'Receipt Number is already registered.';
                            }
                            else
                            {
                                $response = $this->CollectionModel->updateCashDeposit($cash_id,$receipt_no,$account_receiver);
                                if($response==1 || $data['result']=='success')
                                {
                                    $data['result'] = 'success';
                                    $data['message'] = 'Record updated successfully.';
                                }
                                else
                                {
                                    $data['result'] = 'fail';
                                    $data['message'] = 'Record is on update.';
                                }
                                
                            }
                            
                            break;
                case '5' :
                        $bank_name = $this->input->post('bank_name');
                        $branch = $this->input->post('branch');
                        $account_name = $this->input->post('account_name');
                        $account_number = $this->input->post('account_number');
                        $check_date = $this->input->post('check_date');
                        $check_number = $this->input->post('check_number');
                        $check_amount = $this->input->post('check_amount');
                        $pdc_id = $this->input->post('pdc_id');
                        $target_dir = 'uploads/pdc/';
                        
                        $data = $this->updatePdcPayment($pdc_id,$bank_name,$branch,$account_name,$account_number,$check_date,
                                                        $check_number,$check_amount,$comments,$payment_id,$target_dir);
                        
                        break;
                case '6' :
                        $bank_id = $this->input->post('bank_id');
                        $reference_no = $this->input->post('banking_ref_no');
                        $money_type = $this->input->post('money_type');
                        $from_account = $this->input->post('from_account');
                        $amount = $this->input->post('bt_amount');
                        $destination_account = $this->input->post('destination_account');
                        
                        $target_dir = 'uploads/banking/';
                        
                        $data = $this->bankingUpdatePayment($bank_id,$reference_no,$money_type,$from_account,
                                                                $amount,$destination_account,$target_dir,$comments,$payment_id);
                        break;
            }
            
        
        echo json_encode($data);
    }
    
    private function bankingUpdatePayment($bank_id,$reference_no,$money_type,$from_account,
                                            $amount,$destination_account,$target_dir,$comments,$payment_id)
    {
        $container_array=array('Reference Number'=>$reference_no,'Money Type'=>$money_type,'Account Name'=>$from_account,
                                               'Amount'=>$amount,'Destination Account'=>$destination_account);
                        
        $response = $this->checkFields($container_array);
        
        if($response['result'] == 'success')
        {
            $response = $this->CollectionModel->checkBankingRefNoUpdate($bank_id,$reference_no);
            if($response==0)
            {
                $image_row = $this->CollectionModel->getBankingInfo($bank_id);
                
                if(count($_FILES)>0):
                    $image_response = $this->uploadImages($target_dir);
                    if($image_response['result']=='success'):
                        if(is_file($image_row->image_path)):
                            unlink($image_row->image_path);
                        endif;
                        
                    endif;
                else:
                    $image_response['image_path'] = $image_row->image_path;
                endif;
                
                $response_queryy = $this->CollectionModel->updateBankingInfo($bank_id,$reference_no,$money_type,$from_account,
                                                        $amount,$destination_account,$image_response['image_path'],3);
                
                if($response_queryy==1)
                {
                    if($comments!=''):
                        $this->CollectionModel->insertPaymentComments($payment_id,6,$comments,$this->session->userdata('account_id'),$this->session->userdata('account_id'));
                    endif;
                    
                    $data['result'] = 'success';
                    $data['message'] = 'Payment information updated successfully.';
                }
                else
                {
                    $data['result'] = 'fail';
                    $data['message'] = 'Payment information is on update.';
                }   
                
            }
            else
            {
                $data['result'] = 'fail';
                $data['message'] = 'Reference Number is already used.';
            }
        }
        else
        {
            $data['result'] = $response['result'];
            $data['message'] = $response['message'];
        }
        
        return $data;
    }
    
    private function updatePdcPayment($pdc_id,$bank_name,$branch,$account_name,$account_number,$check_date,
                                        $check_number,$check_amount,$comments,$payment_id,$target_dir)
    {
        $container_array=array('Bank Name'=>$bank_name,'Branch'=>$branch,'Account Name'=>$account_name,
                                               'Account Number'=>$account_number,'Cheque Date'=>$check_date,
                                               'Cheque Number'=>$check_number,'Cheque Amount'=>$check_amount);
        
        $response = $this->checkFields($container_array);
        
        if($response['result'] == 'success')
        {
            $response = $this->CollectionModel->checkUpdateCheckNo($pdc_id,$check_number);
            if($response==0)
            {
                    $image_row = $this->CollectionModel->getPdcInfoByID($pdc_id);
                    if(count($_FILES)>0):
                        $image_response = $this->uploadImages($target_dir);
                        if($image_response['result']=='success'):
                            unlink($image_row->image_path);
                        endif;
                    else:
                        $image_response['image_path'] = '';
                    endif;
                    
                    $response_queryy = $this->CollectionModel->updatePdcInfo($pdc_id,$bank_name,$branch,
                                                   $account_name,$account_number,date('Y-m-d',strtotime($check_date)),
                                                   $check_number,$check_amount,$image_response['image_path'],3);
                    
                    if($response_queryy==1)
                    {
                        if($comments!=''):
                            $this->CollectionModel->insertPaymentComments($payment_id,2,$comments,$this->session->userdata('account_id'),$this->session->userdata('account_id'));
                        endif;
                        
                        $data['result'] = 'success';
                        $data['message'] = 'Payment information updated successfully.';
                    }
                    else
                    {
                        $data['result'] = 'fail';
                        $data['message'] = 'Payment information is on update.';
                    }    
                
            }
            else
            {
                $data['result'] = 'fail';
                $data['message'] = 'Cheque Number is already used.';
            }
        }
        else
        {
            $data['result'] = $response['result'];
            $data['message'] = $response['message'];
        }
        
        return $data;
    }
    
    private function updateCashPayment($cash_id,$cash_amount,$payment_date,$target_dir,$comments,$payment_id)
    {
        $container_array=array('Cash Amount'=>$cash_amount,'Payment Date'=>$payment_date);
                        
        $response = $this->checkFields($container_array);
        
        if($response['result'] == 'success')
        {
            $image_row = $this->CollectionModel->getCashInfoByID($cash_id);
            if(count($_FILES)>0):
                $image_response = $this->uploadImages($target_dir);
                if($image_response['result']=='success'):
                    if(!unlink($image_row->image_path)): return false; endif;
                endif;
            else:
                $image_response['image_path'] = '';
            endif;
             
            $response_queryy = $this->CollectionModel->updateCashInfo($cash_id,$cash_amount,date('Y-m-d',strtotime($payment_date)),$image_response['image_path'],3);
            
            if($response_queryy==1)
            {
                if($comments!=''):
                    $this->CollectionModel->insertPaymentComments($payment_id,1,$comments,$this->session->userdata('account_id'),$this->session->userdata('account_id'));
                endif;
                
                $data['result'] = 'success';
                $data['message'] = 'Payment information updated successfully.';
            }
            else
            {
                $data['result'] = 'fail';
                $data['message'] = 'Payment information is on update.';
            }    
                
        }
        else
        {
            $data['result'] = $response['result'];
            $data['message'] = $response['message'];
        }
        
        return $data;
    }
    
    public function remove()
    {
        $this->load->model('GenerateModel');
        $sales_order_id = $this->input->post('tid');
        $created_date = $this->input->post('dtc');
        $payment_type = $this->input->post('pty');
        $created_date = date('Y-m-d H:i:s',$created_date);
        $response_payment = $this->CollectionModel->getPaymentRecordInfo($sales_order_id,$created_date,$payment_type);
        
        if($response_payment->num_rows()>0)
        {
            $date = '';
            $row = $response_payment->row();
            switch($payment_type)
            {
                case '1' :
                $payment_id = $row->cash_id;
                $date = strtotime($row->payment_date);
                break;
                case '2' :
                $payment_id = $row->pdc_id;
                $date = strtotime($row->cheque_date);
                break;
                case '3' :
                $payment_id = $row->card_id;
                $date = strtotime($row->settlement_date);
                break;
                case '4' :
                $payment_id = $row->cash_id;
                $date = strtotime($row->payment_date);
                break;
                case '5' :
                $payment_id = $row->pdc_id;
                $date = strtotime($row->cheque_date);
                break;
                case '6' :
                $payment_id = $row->bank_id;
                $date = strtotime($row->created_date);
                break;
            }
            
            if(is_file($row->image_path)):
                unlink($row->image_path);
            endif;
            
            //if()
            //{
                $response = $this->CollectionModel->removeTablePayment($payment_id,$payment_type);
                    
                if($response==1)
                {
                    $data['result'] = 'success';
                    $data['message'] = 'Record deleted successfully';
                }
                else
                {
                    $data['result'] = 'fail';
                    $data['message'] = 'Record is already deleted.';
                }
            //}
            //else
            //{
            //    $data['result'] = 'fail';
            //    $data['message'] = 'Unable to remove the image. Removing failed';
            //}
        }
        else
        {
            $data['result'] = 'fail';
            $data['message'] = 'Retreiving failed. There is no record.';
        }
        
        echo json_encode($data);
    }
    
    private function uploadImages($target_dir)
    {
        $this->load->model('UploadModel');
        $data['result'] = 'success';
        foreach($_FILES as $key => $file){
            $filename = $file["name"];
            
            $target_file = $target_dir . basename($filename);
            $data = $this->UploadModel->uploadPaymentImage($target_file,$file,$filename,$target_dir);
            
            if($data['result'] == 'fail')
            {
                break;
            }
        }
        
        return $data;
    }
}


?>