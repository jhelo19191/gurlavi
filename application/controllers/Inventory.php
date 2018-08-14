<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once('Base.php');

class Inventory extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('InventoryModel');
    }
    
    public function insert()
    {
        $sales_order_id = $this->input->post('sales_order_id');
        $carrier_name = $this->input->post('carrier_name');
        $position = $this->input->post('position');
        $delivery_comment = $this->input->post('comment');
        $target_dir = 'uploads/inventory/';
        
        $array_container = array('Carrier Name'=>$carrier_name,'Position'=>$position);
        
        $field_response = $this->checkFields($array_container);
        
        if($field_response['result']=='success')
        {
            $response_query = $this->InventoryModel->checkInvetoryRecord($sales_order_id);
            if($response_query==0)
            {
                if(count($_FILES)>0)
                {
                    $image_data = $this->uploadImages($target_dir,$sales_order_id,'inventory');
                    
                    if($image_data['result']=='fail')
                    {
                        $data['result'] = $image_data['result'];
                        $data['message'] = $image_data['message'];
                    }
                    else
                    {
                        $response=$this->InventoryModel->insert($sales_order_id,$carrier_name,$position,$delivery_comment,$this->session->userdata('account_id'));
                        if($response==1)
                        {
                            $this->InventoryModel->updateSalesOrderStatus($sales_order_id);
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
                    $data['message'] = 'Please upload the delivery form with sign.';
                }
                
            }
            else
            {
                $data['result'] = 'fail';
                $data['message'] = 'Request is already saved.';
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
}


?>