<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once('Base.php');

class Delivery extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('DeliveryModel');
        $this->load->model('NotificationModel');
    }
    
    public function insert()
    {
        $sales_order_id = $this->input->post('sales_order_id');
        $actual_delivery_date = $this->input->post('actual_delivery_date');
        $delivery_comment = $this->input->post('comment');
        $target_dir = 'uploads/delivery/';
        
        $array_container = array('Actual Delivery Date'=>$actual_delivery_date);
        
        $field_response = $this->checkFields($array_container);
        
        if($field_response['result']=='success')
        {
            $response_query = $this->DeliveryModel->checkDeliveryRecord($sales_order_id);
            
            if($response_query==0)
            {
                $image_data = $this->uploadImages($target_dir,$sales_order_id,'delivery');
                if($image_data['result']=='fail')
                {
                    $data['result'] = $image_data['result'];
                    $data['message'] = $image_data['message'];
                }
                else
                {
                    $response=$this->DeliveryModel->insert($sales_order_id,date('Y-m-d',strtotime($actual_delivery_date)),$delivery_comment,$this->session->userdata('account_id'));
                    if($response==1)
                    {
                        $this->DeliveryModel->updateSalesOrderStatus($sales_order_id);
                        $data['result'] = 'success';
                        $data['message'] = 'Delivery information was successfully saved';
                    }
                    else
                    {
                        $data['result'] = 'fail';
                        $data['message'] = 'Unable to save the delivery information.';
                    }    
                }    
            }
            else
            {
                $data['result'] = 'fail';
                $data['message'] = 'Delivery information is already saved.';
            }
            
        }
        else
        {
            $data['result'] =  $field_response['result'];
            $data['message'] = $field_response['message'];
        }
        
        echo json_encode($data);
    }
    
    private function uploadImages($target_dir,$sales_order_id,$process_type)
    {
        $this->load->model('UploadModel');
        $data['result'] = 'success';
        foreach($_FILES as $key => $file){
            $filename = $file["name"];
            
            $target_file = $target_dir . basename($filename);
            $data = $this->UploadModel->processCheck($target_file,$file,$sales_order_id,$filename,$process_type,$target_dir);
            
            if($data['result'] == 'fail')
            {
                break;
            }
        }
        
        return $data;
    }
    
    public function information()
    {
        if(!$this->session->userdata('logged_in'))
        {
            $this->login();
        }
        else
        {
            $data['active_module'] = 'transaction';
            $data['header_title'] = 'Invoice Information';
			$data['main_crubs'] = 'Delivery Records';
			$data['sub_crubs'] = 'Information';
            
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
                
                $data['message'] = $response['message'];
                $data['attachment'] = $response['attachment'];
                    
                $data['customer_name'] = $response['customer_name'];
                $data['shipto'] = $response['shipto'];
                $data['contact_no'] = $response['contact_no'];
                $data['tin'] = $response['tin'];
                $data['items'] = $response['items'];
                $data['total'] = $response['total'];
                
                $this->render('pages/request/delivery_view',$data);
            }
            else
            {
                redirect();   
            }
        }
    }
    
    private function viewInfo($sales_order_id)
    {
        $data = array();
        
        $response = $this->DeliveryModel->getSalesOrderInformation($sales_order_id);
        
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
            
            $data['message'] = ($row->message==''? 'No Message':$row->message);
            
            $data['customer_name'] = $row->customer_name;
            $data['shipto'] = $row->ship_to;
            $data['contact_no'] = $row->contact_no;
            $data['tin'] = $row->tin;
            
            $data['attachment'] = $this->setAttachment($sales_order_id,'invoice');
            
            $response = $this->DeliveryModel->getRegProducts($sales_order_id);
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
}


?>