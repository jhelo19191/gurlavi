<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once('Base.php');

class Rcheck extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('RcheckModel');
    }
    
    public function insert()
    {
        $collection_number = $this->input->post('cr_no');
        $sales_order_id = $this->uri->segment(3);
        $collection_date = $this->input->post('collect_date');
        
        $count_flag_zero = $this->RcheckModel->checkPaymentStatus($sales_order_id);
        
        if($count_flag_zero==0)
        {
            $this->RcheckModel->updateSalesOrderStatus($sales_order_id);
            $data['result'] = 'success';
            $data['message'] = 'Record successfully saved.';
        }
        else
        {
            $data['result'] = 'fail';
            $data['message'] = 'Please verify the all payments';
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
            
            $sales_order_id = $this->uri->segment(3);
            $stage_type = $this->uri->segment(4);
            
            $response = $this->viewInfo($sales_order_id);
            
            if(count($response)>0)
            {
                $data['psr_name'] = $response['psr_name'];
                $data['approved_date'] = $response['approved_date'];
                $data['delivery_date'] = $response['delivery_date'];
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
                
                $data['cr_no'] = $response['cr_no'];
                $data['collect_date'] = $response['collect_date'];
                
                $response_table = $this->paymentRecord($sales_order_id);
                $data['payment_list'] = $response_table['html'];
                $data['total_amount'] = $response_table['total'];
                
                if(isset($responsed['result'])):
                    $data['result'] = $responsed['result'];
                    $data['message'] = $responsed['message'];
                endif;
                
                $this->render('pages/request/rcheck_view',$data);
            }
            else
            {
                redirect();   
            }
        }
    }
    
    private function paymentRecord($sales_order_id)
    {
        $html = '';
        $active_page = $this->uri->segment(1);
        $data = array();
        $iter=1;
        $total_reject = 0;
        $total_cleared = 0;
        $total = 0;
        $cash = $this->sortCashRecord($sales_order_id);
        $credit = $this->sortCreditCardRecord($sales_order_id);
        $pdc = $this->sortPdcRecord($sales_order_id);
        
        $merge = array_merge($cash,$credit);
        $table_data = array_merge($merge,$pdc);
        
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
                $html .=        $records['payment_type'];
                $html .='    </td>';
                $html .='    <td>';
                $html .=        number_format($records['amount']);
                $html .='     </td>';
                $html .='     <td>';
                $html .='        <a href="'.site_url($image_path).'" target="_blank" data-value="'.site_url($image_path).'">';
                $html .='            <i class="fa fa-image"></i> View Image';
                $html .='        </a>';
                $html .='    </td>';
                $html .='    <td>';
                $html .=        date('F d, Y',strtotime($records['payment_date']));
                $html .='    </td>';
                $html .='    <td class="status">';
                $html .=        $this->paymentStatus($records['flag']);
                $html .='    </td>';
                $html .='    <td style="text-align: center;">';
                $html .='        <a  href="#" class="btn btn-info show-view" data-action="view" data-value="'.$records['payment_type_id'].'_'.$records['created_date'].'_'.$sales_order_id.'" title="View More Details"><i class="fa fa-eye"></i></a>';
                if($records['flag']==0):
                $html .='        <a  href="#" data-action="reject" class="btn btn-danger show-reject loading-confirmation" data-target="'.$sales_order_id.'" data-access="'.$records['payment_type_id'].'" data-value="'.$records['payment_id'].'" data-button="5" title="Reject"><i class="fa fa-times"></i></a>';
                $html .='        <a  href="#" data-action="accept" class="btn btn-success show-accept loading-confirmation" data-target="'.$sales_order_id.'" data-access="'.$records['payment_type_id'].'" data-value="'.$records['payment_id'].'" data-button="4" title="Accept"><i class="fa fa-check"></i></a>';
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
        $response = $this->RcheckModel->getCashRecord($sales_order_id);
        if($response->num_rows()>0):
            foreach($response->result() as $cash)
            {
                $table_data[] = array(
                                        'payment_type' => 'Cash',
                                        'payment_type_id' => 1,
                                        'amount' => $cash->amount,
                                        'payment_id' => $cash->cash_id,
                                        'payment_date' => $cash->payment_date,
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
        $response = $this->RcheckModel->getCreditCardRecord($sales_order_id);
        if($response->num_rows()>0):
            foreach($response->result() as $cash)
            {
                $table_data[] = array(
                                        'payment_type' => 'Credit Card',
                                        'payment_type_id' => 3,
                                        'amount' => $cash->credit_card_amount,
                                        'payment_id' => $cash->card_id,
                                        'payment_date' => $cash->settlement_date,
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
        $response = $this->RcheckModel->getPdcRecord($sales_order_id);
        if($response->num_rows()>0):
            foreach($response->result() as $cash)
            {
                $table_data[] = array(
                                        'payment_type' => 'PDC',
                                        'payment_type_id' => 2,
                                        'payment_id' => $cash->pdc_id,
                                        'amount' => $cash->amount,
                                        'payment_date' => $cash->cheque_date,
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
        
        $response = $this->RcheckModel->getSalesOrderInformation($sales_order_id);
        
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
            
            $data['status'] = $this->requestStatus($row->status);
            $data['status_no'] = $row->status;
            
            $data['cr_no'] = $row->cr_no;
            $data['collect_date'] = $row->collect_date;
            
            $data['customer_name'] = $row->customer_name;
            $data['shipto'] = $row->ship_to;
            $data['contact_no'] = $row->contact_no;
            $data['tin'] = $row->tin;
            
            $response = $this->RcheckModel->getRegProducts($sales_order_id);
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
    
    
    private function paymentStatus($flag)
    {
        $segment = $this->uri->segment(4);
        switch($flag)
        {
            case '1' :
                        $status = ($segment=="collection"?'Submitted':'Waiting');
                        break;
            case '2' :
                        $status = "Cleared";
                        break;
            case '3' : $status = ($flag==3&&$segment=="remittance"? "Submitted" : 'Remitted'); break;
            case '4' : $status = 'Checked'; break;
            case '5' : $status = 'Rejected'; break;
            default: $status = 'Pending'; break;
        }
        
        return $status;
    }
    
    public function rejectStatus()
    {
        $payment_id = $this->input->post('pid');
        $payment_type = $this->input->post('payment_type');
        $sales_order_id = $this->input->post('soid');
        $flag = $this->input->post('action_type');
        $comments = $this->input->post('comments');
        $sender_id = $this->session->userdata('account_id');
        
        $array_container = array('Comments' => $comments);
        
        $response = $this->checkFields($array_container);
        
        if($response['result']=='success')
        {
            $row = $this->RcheckModel->soSender($sales_order_id);
            $receiver_id = $row->account_id;
            
            $query_response = '';
            switch($payment_type)
            {
                case '1' :
                $row = $this->RcheckModel->getCashInfoByID($payment_id);
                $query_response = $this->RcheckModel->updateCashFlag($payment_id,$flag,3);
                break;
                case '2' :
                $row = $this->RcheckModel->getPdcInfoByID($payment_id);
                $query_response = $this->RcheckModel->updatePdcFlag($payment_id,$flag,3);
                break;
                case '3' :
                $row = $this->RcheckModel->getCreditInfoByID($payment_id);
                $query_response = $this->RcheckModel->updateCreditCardFlag($payment_id,$flag,3);
                break;
            }
            
            if($query_response==1)
            {
                $this->RcheckModel->insertPaymentComments($payment_id,$payment_type,$comments,$sender_id,$receiver_id);
                $data['result'] = 'success';
                $data['message'] = 'Payment has been rejected';
            }
            else
            {
                $data['result'] = 'fail';
                $data['message'] = 'Unable to update the record';
            }
        }
        else
        {
            $data['result'] = $response['result'];
            $data['message'] = $response['message'];
        }
        
        echo json_encode($data);
    }
    
    public function acceptStatus()
    {
        $payment_id = $this->input->post('pid');
        $payment_type = $this->input->post('payment_type');
        $sales_order_id = $this->input->post('soid');
        $flag = $this->input->post('action_type');
        $sender_id = $this->session->userdata('account_id');
        
        $row = $this->RcheckModel->soSender($sales_order_id);
        $receiver_id = $row->account_id;
        
        $query_response = '';
        switch($payment_type)
        {
            case '1' : $query_response = $this->RcheckModel->updateCashFlag($payment_id,$flag,0); break;
            case '2' : $query_response = $this->RcheckModel->updatePdcFlag($payment_id,$flag,0); break;
            case '3' : $query_response = $this->RcheckModel->updateCreditCardFlag($payment_id,$flag,0); break;
        }
        
        if($query_response==1)
        {
            $data['result'] = 'success';
            $data['message'] = 'Payment has been accepted';
        }
        else
        {
            $data['result'] = 'fail';
            $data['message'] = 'Payment status is on update.';
        }
        
        echo json_encode($data);
    }
}


?>