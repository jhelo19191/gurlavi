<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once("Base.php");

class Products extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('ProductsModel');
    }
    
    public function index($pages='',$array=array())
    {
        if(!$this->session->userdata('logged_in'))
        {
            $this->login();
        }
        else
        {
            $data['active_module'] = 'products';
            $data['header_title'] = 'Register New Product';
			$data['main_crubs'] = 'Products';
			$data['sub_crubs'] = 'Register';
            
            if(isset($array['result'])):
                $data['result'] = $array['result'];
                $data['message'] = $array['message'];
            endif;
            
            $this->render('pages/products/index',$data);
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
            $data['active_module'] = 'products';
            $data['header_title'] = 'Update Product';
			$data['main_crubs'] = 'Products';
			$data['sub_crubs'] = 'Update';
            
            $segment = $this->uri->segment(3);
            $row = $this->ProductsModel->getProductInfo($segment);
            if(count($row)==1)
            {
                $data['activity'] = 'update';
                $data['segment'] = $segment;
                $data['product_name'] = $row->product_name;
                $data['description'] = $row->description;
                
                if(isset($array['result'])):
                    $data['result'] = $array['result'];
                    $data['message'] = $array['message'];
                endif;
                
                $this->render('pages/products/index',$data);    
            }
            else
            {
                redirect();
            }
            
        }
    }
    
    public function update()
    {
        $product_id = $this->input->post('product_id');
        $product_name = $this->input->post('product_name');
        $description = $this->input->post('description');
        
        $array_container = array('Product Name'=>$product_name);
        
        $response = $this->checkFields($array_container);
        
        if($response['result']=='success')
        {
            $num_rows = $this->ProductsModel->checkUpdateRecord($product_name,$product_id);
            if($num_rows==0)
            {
                $response = $this->ProductsModel->update($product_id,$product_name,$description);
                if($response==1)
                {
                    $data['result'] = 'success';
                    $data['message'] = 'Product updated successfully.';
                }
                else
                {
                    $data['result'] = 'dail';
                    $data['message'] = 'Product information is on update.';
                }    
            }
            else
            {
                $data['result'] = 'fail';
                $data['message'] = 'Product name is already used.';
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
        $product_name = $this->input->post('product_name');
        $description = $this->input->post('description');
        
        $array_container = array('Product Name'=>$product_name);
        
        $response = $this->checkFields($array_container);
        
        if($response['result']=='success')
        {
            $num_rows = $this->ProductsModel->checkProductName($product_name);
            if($num_rows==0)
            {
                $response = $this->ProductsModel->insert($product_name,$description);
                if($response==1)
                {
                    $data['result'] = 'success';
                    $data['message'] = 'Product added successfully.';
                }
                else
                {
                    $data['result'] = 'dail';
                    $data['message'] = 'Product adding failed.';
                }    
            }
            else
            {
                $data['result'] = 'fail';
                $data['message'] = 'Product Name is already used.';
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
           $product_id = $this->input->post('pdi');
           if(is_numeric($product_id))
           {
                $response = $this->ProductsModel->remove($product_id);
                if($response==1)
                {
                    $data['result'] = 'success';
                    $data['message'] = 'Product was successfully removed.';
                }
                else
                {
                    $data['result'] = 'fail';
                    $data['message'] = 'Product is already deleted.';
                }
           }
           else
           {
                $data['result'] = 'fail';
                $data['message'] = 'Invalid Product Information.';
           }
        }
        
        echo json_encode($data);
    }
    
    public function showProductList()
    {
        $data = array();
        $response = $this->ProductsModel->productList();
        if($response->num_rows()>0)
        {
            foreach($response->result() as $items)
            {
                $data[]  = array(
                                    'product_id' => $items->product_id,
                                    'product_name' => $items->product_name
                                 );
            }
        }
        else
        {
            $data[]  = array(
                                'product_id' => '',
                                'product_name' => ''
                             );
        }
        
        echo json_encode($data);
    }
    
    public  function checkProducts()
    {
        $product_id = $this->input->post('pid');
        
        if(is_numeric($product_id)&&$product_id!=0)
        {
            $product_num_rows = $this->ProductsModel->checkProductRecord($product_id);
            if($product_num_rows==1)
            {
                $data['result'] = 'success';
                $data['message'] = 'Product added successfully.';
            }
            else
            {
                $data['result'] = 'fail';
                $data['message'] = 'Product is not registered.';
            }    
        }
        else
        {
            $data['result'] = 'fail';
            $data['message'] = 'Invalid Product';
        }
        
        echo json_encode($data);
        
    }
}


?>