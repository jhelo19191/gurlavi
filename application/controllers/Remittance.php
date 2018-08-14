<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once('Base.php');

class Remittance extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('RemittanceModel');
        $this->load->model('BaseModel');
    }
    
    public function insert()
    {
        $remittance_no = $this->input->post('remittance_number');
        $remittance_date = $this->input->post('remittance_date');
        $comments = $this->input->post('comments');
        $approved_by = $this->session->userdata('account_id');
        $sales_order_id = $this->uri->segment(3);
        
        $target_dir = 'uploads/remittance/';
        
        $data['remittance_no'] = $remittance_no;
        $data['remittance_date'] = $remittance_date;
        $data['comments'] = $comments;
        
        $array_container = array('Remittance Number'=>$remittance_no,'Remittance Date'=>$remittance_date);
        
        $response = $this->checkFields($array_container);
        
        if($response['result']=='success')
        {
            $checkRimittanceNo = $this->RemittanceModel->checkRemittanceNo($sales_order_id,$remittance_no);
            
            if($checkRimittanceNo==0)
            {
                $image_data = $this->uploadImages($target_dir,$sales_order_id,'remittance');
                if($image_data['result']=='fail')
                {
                    $data['result'] = $image_data['result'];
                    $data['message'] = $image_data['message'];
                }
                else
                {
                    //$checkSalesOrder = $this->RemittanceModel->checkRemittanceSalesOrder($sales_order_id);
                    //if($checkSalesOrder==0)
                    //{
                        $insert_id = $this->RemittanceModel->insert($sales_order_id,$remittance_no,date('Y-m-d',strtotime($remittance_date)),$comments,$approved_by);
                        if($insert_id!='')
                        {
                            $count_pdc = $this->RemittanceModel->checkPDC($sales_order_id);
                            if($count_pdc==0)
                            {
                                $status = 9;
                            }
                            else
                            {
                                $status = 6;
                            }
                            
                            $this->BaseModel->updateStatus($sales_order_id,2,$status,3);
                            $this->RemittanceModel->updateSalesOrderStatus($sales_order_id,$status);
                            $this->RemittanceModel->updatePdcRemit($sales_order_id,$insert_id);
                            $this->RemittanceModel->updateCashRemit($sales_order_id,$insert_id);
                            $this->RemittanceModel->updateCreditCardRemit($sales_order_id,$insert_id);
                            $data['result'] = 'success';
                            $data['message'] = 'Record saved successfully.';
                        }
                        else
                        {
                            $data['result'] = 'fail';
                            $data['message'] = 'Record unable to saved.';
                        }     
                                
                    //}
                    //else
                    //{
                    //    $this->BaseModel->updateStatus($sales_order_id,2,6,3);
                    //    $this->RemittanceModel->update($sales_order_id,$remittance_no,date('Y-m-d',strtotime($remittance_date)),$comments);
                    //        $data['result'] = 'success';
                    //        $data['message'] = 'Record updated successfully.';
                    //}
                }
                
            }
            else
            {
                $data['result'] = 'fail';
                $data['message'] = 'Remittance Number is already used.';
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
            $data['header_title'] = 'Remittance Information';
			$data['main_crubs'] = 'Remittance Record';
			$data['sub_crubs'] = 'Information';
            
            $sales_order_id = $this->uri->segment(3);
            $stage_type = $this->uri->segment(4);
            
            $response = $this->viewInfo($sales_order_id);
            
            if(count($response)>0)
            {
                $response_collection = $this->RemittanceModel->getRemittance($sales_order_id);
                
                if($response_collection->num_rows()>0):
                    $row = $response_collection->row();
                    $data['remittance_no'] = $row->remittance_no;
                    $data['remittance_date'] = $row->remittance_date;
                    $data['comments'] = $row->message;
                endif;
                
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
                
                $data['attachment'] = $response['attachment'];
                
                $response_table = $this->paymentRecord($sales_order_id);
                $data['payment_list'] = $response_table['html'];
                $data['total_amount'] = $response_table['total'];
                
                if(isset($responsed['result'])):
                    $data['result'] = $responsed['result'];
                    $data['message'] = $responsed['message'];
                    $data['remittance_no'] = $responsed['remittance_no'];
                    $data['remittance_date'] = $responsed['remittance_date'];
                    $data['comments'] = $responsed['comments'];
                endif;
                
                $this->render('pages/request/remittance_view',$data);
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
        //$mobile_banking = $this->sortMobileBankingRecord($sales_order_id);
        
        $merge = array_merge($cash,$credit);
        $table_data = array_merge($merge,$pdc);
        //$table_data = array_merge($table_data,$mobile_banking);
        
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
                
                if($records['flag']==2 || $records['flag']==3):
                   
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
                $html .='    </td>';
                
                $html .='</tr>';
                
                $iter++;
                
                endif;
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
        $response = $this->RemittanceModel->getCashRecord($sales_order_id);
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
        $response = $this->RemittanceModel->getCreditCardRecord($sales_order_id);
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
        $response = $this->RemittanceModel->getPdcRecord($sales_order_id);
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
    
    private function viewInfo($sales_order_id)
    {
        $data = array();
        
        $response = $this->RemittanceModel->getSalesOrderInformation($sales_order_id);
        
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
            
            $data['attachment'] = $this->setAttachment($sales_order_id,'remittance');
            
            $response = $this->RemittanceModel->getRegProducts($sales_order_id);
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
    
    private function sortMobileBankingRecord($sales_order_id)
    {
        $table_data = array();
        $response = $this->RemittanceModel->getMobileBankingRecord($sales_order_id);
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
    
    
    private function uploadImages($target_dir,$sales_order_id,$process_type)
    {
        
        $target_file = $target_dir . basename($_FILES["files_uploads"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["files_uploads"]["tmp_name"]);
            if($check !== false) {
                $data['result'] = 'fail';
                $data['message'] = "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                $data['result'] = 'fail';
                $data['message'] = "File is not an image.";
                $uploadOk = 0;
            }
        }
        
        // Check file size
        if ($_FILES["files_uploads"]["size"] > 500000) {
            $data['result'] = 'fail';
            $data['message'] = "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            $data['result'] = 'fail';
            $data['message'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
        
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $data['result'] = 'fail';
            $data['message'] = "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            
            $target_file = $target_dir.date('YmdHis').'.'.$imageFileType;
            
            if (move_uploaded_file($_FILES["files_uploads"]["tmp_name"], $target_file)) {
                
                $response = $this->insertImage($sales_order_id,$process_type,$target_file);
                $data['result'] = 'success';
                $data['message'] = "The file ". basename( $_FILES["files_uploads"]["name"]). " has been uploaded.";
            } else {
                $data['result'] = 'fail';
                $data['message'] = "Sorry, there was an error uploading your file.";
            }
        }
        
        sleep(1);
        return $data;
    }
    
    private function insertImage($sales_order_id,$process_type,$image_path)
    {
        $this->load->model('FileUploadModel');
        $response = $this->FileUploadModel->insert($sales_order_id,$process_type,$image_path);
        
        return $response;
    }
}


?>