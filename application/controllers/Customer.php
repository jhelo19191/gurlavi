<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once("Base.php");

class Customer extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('CustomersModel');
    }
    
    public function index($pages='',$array=array())
    {
        if(!$this->session->userdata('logged_in'))
        {
            $this->login();
        }
        else
        {
            $data['active_module'] = 'customers';
            $data['header_title'] = 'Register New Customer';
			$data['main_crubs'] = 'Customer Record';
			$data['sub_crubs'] = 'Register';
            
            if(isset($array['result'])):
                $data['result'] = $array['result'];
                $data['message'] = $array['message'];
            endif;
            
            $this->render('pages/customer/index',$data);
        }
    }
    
    public function modify($pages='',$array=array())
    {
        if(!$this->session->userdata('logged_in'))
        {
            $this->login();
        }
        else
        {
            $data['active_module'] = 'customers';
            $data['header_title'] = 'Update Customer';
			$data['main_crubs'] = 'Customer Record';
			$data['sub_crubs'] = 'Update';
            
            $segment = $this->uri->segment(3);
            $row = $this->CustomersModel->getCustomerInfo($segment);
            if(count($row)==1)
            {
                $data['activity'] = 'update';
                $data['segment'] = $segment;
                $data['customer_name'] = $row->customer_name;
                $data['address'] = $row->address;
                $data['contact_no'] = $row->contact_no;
                $data['tin'] = $row->tin;
                
                if(isset($array['result'])):
                    $data['result'] = $array['result'];
                    $data['message'] = $array['message'];
                endif;
                
                $this->render('pages/customer/index',$data);    
            }
            else
            {
                redirect();
            }
            
        }
    }
    
    public function update()
    {
        $customer_id = $this->input->post('customer_id');
        $customer_name = $this->input->post('customer_name');
        $address = $this->input->post('address');
        $contact_no = $this->input->post('contact_no');
        $tin = $this->input->post('tin');
        
        $array_container = array('Customer Name'=>$customer_name,"Address"=>$address,"Contact Number"=>$contact_no,"TIN"=>$tin);
        
        $response = $this->checkFields($array_container);
        
        if($response['result']=='success')
        {
            $num_rows = $this->CustomersModel->checkUpdateRecord($customer_id,$tin);
            if($num_rows==0)
            {
                $response = $this->CustomersModel->update($customer_id,$customer_name,$address,$contact_no,$tin);
                if($response==1)
                {
                    $data['result'] = 'success';
                    $data['message'] = 'Customer updated successfully.';
                }
                else
                {
                    $data['result'] = 'dail';
                    $data['message'] = 'Customer information is on update.';
                }    
            }
            else
            {
                $data['result'] = 'fail';
                $data['message'] = 'TIN number is already used.';
            }
            
        }
        else
        {
            $data['result'] = $response['result'];
            $data['message'] = $response['message'];
        }
        
        $this->modify('',$data);
    }
    
    public function insert()
    {
        $customer_name = $this->input->post('customer_name');
        $address = $this->input->post('address');
        $contact_no = $this->input->post('contact_no');
        $tin = $this->input->post('tin');
        
        $array_container = array('Customer Name'=>$customer_name,"Address"=>$address,"Contact Number"=>$contact_no,"TIN"=>$tin);
        
        $response = $this->checkFields($array_container);
        
        if($response['result']=='success')
        {
            if(is_numeric($tin))
            {
                $num_rows = $this->CustomersModel->checkCustomerTIN($tin);
                if($num_rows==0)
                {
                    $response = $this->CustomersModel->insert($customer_name,$address,$contact_no,$tin);
                    if($response==1)
                    {
                        $data['result'] = 'success';
                        $data['message'] = 'Customer added successfully.';
                    }
                    else
                    {
                        $data['result'] = 'fail';
                        $data['message'] = 'Customer adding failed.';
                    }    
                }
                else
                {
                    $data['result'] = 'fail';
                    $data['message'] = 'TIN Number is already used.';
                }    
            }
            else
            {
                $data['result'] = 'fail';
                $data['message'] = 'TIN Number must be numeric.';
            }
            
            
        }
        else
        {
            $data['result'] = $response['result'];
            $data['message'] = $response['message'];
        }
        
        $this->index('',$data);
    }
    
    public function remove()
    {
        if(!$this->session->userdata('logged_in'))
        {
            $data['result'] = 'fail';
            $data['message'] = 'Invalid Credential';
        }
        else
        {
           $customers_id = $this->input->post('pdi');
           if(is_numeric($customers_id))
           {
                $response = $this->CustomersModel->remove($customers_id);
                if($response==1)
                {
                    $data['result'] = 'success';
                    $data['message'] = 'Customer information was successfully removed.';
                }
                else
                {
                    $data['result'] = 'fail';
                    $data['message'] = 'Customer information already deleted.';
                }
           }
           else
           {
                $data['result'] = 'fail';
                $data['message'] = 'Invalid Customer Information.';
           }
        }
        
        echo json_encode($data);
    }
    
    
    
    public function searchName()
    {
        $data = array();
        
        $response = $this->CustomersModel->getCustomersList();
        
        if($response->num_rows()>0)
        {
            foreach($response->result() as $list)
            {
                $data[] = array(
                                    'customer_id' => $list->customers_id,
                                    'customer_name' => $list->customer_name,
                                    'contact_no' => $list->contact_no
                                );
            }
        }
        else
        {
            $data[] = array(
                                    'customer_id' => '',
                                    'customer_name' => '',
                                    'contact_no' => ''
                                );
        }
        
        echo json_encode($data);
    }
}


?>