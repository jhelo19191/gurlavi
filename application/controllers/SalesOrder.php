<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once('Base.php');

class SalesOrder extends Base
{
  function __construct()
  {
    parent::__construct();
    $this->load->model('SalesOrderModel');
    $this->load->model('NotificationModel');
  }

  public function register()
  {
    if(!$this->session->userdata('logged_in')) {
        $this->login();
    } else {
        $data['active_module'] = 'transaction';
		$data['main_crubs'] = 'Sales Order';
		$data['sub_crubs'] = 'Register';
        $data['header_title'] = 'Register New Sales Order';
        
        $data['options_product'] = $this->productList();
        $data['options_customer'] = $this->customerList();
        
        $this->render('pages/request/sales_order',$data);
    }
  }

  public function insert()
  {
    $account_id = $this->session->userdata('account_id');
    $so_number = $this->input->post("sales_order_no");
    $invoice_number = $this->input->post("invoice_number");
    $so_date = $this->input->post("so_date");
    $approved_date = $this->input->post("approve_date");
    $comments = $this->input->post("comment");
    $psr_name = $this->input->post("psr_name");
    $customer_name = $this->input->post("customer_name");
    $delivery_date = $this->input->post("delivery_date");
    $shipto = $this->input->post("shipto");
    
    $pdc_name = $this->input->post("pdc_name");
    $product_id = $this->input->post("pdc_id");
    $pdc_quantity = $this->input->post("pdc_quantity");
    $pdc_unit = $this->input->post("pdc_unit");
    $pdc_price = $this->input->post("pdc_price");

    $array_container = array("Sales Order Number" => $so_number,"Sales Order Date"=>$so_date,
    "Approved Date"=>$approved_date,"PSR Name"=>$psr_name,"Customer Name"=>$customer_name,
    "Delivery Date"=>$delivery_date,"Ship To"=>$shipto);

    $response = $this->checkFields($array_container);
    
    $target_dir = "uploads/sales_order/";
    
    if($response['result']=='success')
    {
        $so_num_rows = $this->SalesOrderModel->checkSalesOrderNo($so_number);
        
        if($so_num_rows==0)
        {
            $response = $this->checkRegProducts($pdc_name,$pdc_quantity,$pdc_unit,$pdc_price);
            if($response['result']=='success')
            {
                if(count($_FILES)>0)
                {
                    $sales_order_id = $this->SalesOrderModel->insert($so_number,$invoice_number,$this->changeDateFormat($so_date),$this->changeDateFormat($approved_date),
                          $psr_name,$customer_name,$this->changeDateFormat($delivery_date),$account_id,$shipto,$comments);
                    if(count($response)==1)
                    {
                        
                        
                        $image_response = $this->uploadImages($target_dir,$sales_order_id,'sales_order');
                        if($image_response['result']=='fail')
                        {
                            $this->SalesOrderModel->removeSalesOrder($sales_order_id);
                            $data['result'] = $image_response['result'];
                            $data['message'] = $image_response['message'];    
                        }
                        else
                        {
                            $this->insertRegisteredProducts($sales_order_id,$product_id,$pdc_quantity,$pdc_unit,$pdc_price);
                            $this->SalesOrderModel->saveNotification($sales_order_id,$this->session->userdata('account_name').' created a sales order with the number: ' . $so_number);
                            $data['result'] = 'success';
                            $data['message'] = 'Records added successfully.';    
                        }
                        
                    }
                    else
                    {
                      $data['result'] = 'fail';
                      $data['message'] = 'Record adding failed.';
                    }    
                }
                else
                {
                    $data['result'] = 'fail';
                    $data['message'] = 'Please upload the Sales Order form';
                }
                
            }
            else
            {
                $data['result'] = $response['result'];
                $data['message'] = $response['message'];
            }    
        }
        else
        {
            $data['result'] = 'fail';
            $data['message'] = 'Sales Order Number is already registered.';
        }
        
    }
    else
    {
      $data['result'] = $response['result'];
      $data['message'] = $response['message'];
    }

    echo json_encode($data);
  }

  private function changeDateFormat($date)
  {
    $date = date('Y-m-d',strtotime($date));

    return $date;
  }

  private function checkRegProducts($pdc_name,$pdc_quantity,$pdc_unit,$pdc_price)
  {
    $data['result'] = 'success';
    if(count($pdc_name)>0) {
      for($n=0; $n<count($pdc_name); $n++) {
        if($pdc_name[$n]=='') :
          $data['result'] = 'fail';
          $data['message'] = 'Product Name is empty.';

          break;
        endif;

        if($pdc_quantity[$n]=='') :
          $data['result'] = 'fail';
          $data['message'] = 'Product Name is empty.';

          break;
        endif;

        if($pdc_unit[$n]=='') :
          $data['result'] = 'fail';
          $data['message'] = 'Product Name is empty.';

          break;
        endif;

        if($pdc_price[$n]=='') :
          $data['result'] = 'fail';
          $data['message'] = 'Product Name is empty.';

          break;
        endif;
      }
    } else {
      $data['result'] = 'fail';
      $data['message'] = 'Register a product first before submitting the record.';
    }


    return $data;
  }
  
    private function insertRegisteredProducts($sales_order_id,$product_id,$quantity,$unit,$price)
    {
        for($i=0;$i<count($product_id);$i++)
        {
            $this->SalesOrderModel->insertRegProducts($sales_order_id,$product_id[$i],$quantity[$i],$unit[$i],$price[$i]);
        }
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
            
            sleep(1);
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
            $data['main_crubs'] = 'Sales Order';
            $data['sub_crubs'] = 'Sales Order Information';
            $data['header_title'] = 'Sales Order Information';
            
            $sales_order_id = $this->uri->segment(3);
            $page_active = $this->uri->segment(4);
            
            $data['segment'] = $sales_order_id;
            $data['page_active'] = $page_active;
            
            $response = $this->viewInfo($sales_order_id);
            
            if(count($response)>0)
            {
                $this->NotificationModel->updateStatus($sales_order_id);
                
                $data['psr_name'] = $response['psr_name'];
                $data['approved_date'] = $response['approved_date'];
                $data['delivery_date'] = $response['delivery_date'];
                $data['so_date'] = $response['so_date'];
                $data['sales_order_no'] = $response['sales_order_no'];
                
                $data['message'] = $response['message'];
                $data['attachment'] = $response['attachment'];
                    
                $data['customer_name'] = $response['customer_name'];
                $data['shipto'] = $response['shipto'];
                $data['contact_no'] = $response['contact_no'];
                $data['tin'] = $response['tin'];
                $data['items'] = $response['items'];
                $data['total'] = $response['total'];
                
                $this->render('pages/request/sales_order_view',$data);
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
        
        $response = $this->SalesOrderModel->getSalesOrderInformation($sales_order_id);
        
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
            
            $data['customer_name'] = $row->customer_name;
            $data['shipto'] = $row->ship_to;
            $data['contact_no'] = $row->contact_no;
            $data['tin'] = $row->tin;
            
            $data['message'] = ($row->comments==''?'No Message':$row->comments);
            
            $data['attachment'] = $this->setAttachment($sales_order_id,'sales_order');
            
            $response = $this->SalesOrderModel->getRegProducts($sales_order_id);
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
    
    public function comments()
    {
        $sales_order_id = $this->input->post('so_id');
        $html = '';
        $html .= $this->remittanceComment($sales_order_id);
        $html .= $this->collectionComment($sales_order_id);
        $html .= $this->deliveryComment($sales_order_id);
        $html .= $this->inventoryComment($sales_order_id);
        $html .= $this->invoiceComment($sales_order_id);
        $html .= $this->soComment($sales_order_id);
        
        echo $html;
    }
    
    private function soComment($sales_order_id)
    {
        $html = '';
        $response = $this->SalesOrderModel->getSoComments($sales_order_id);
        if($response->num_rows()>0)
        {
            $row = $response->row();
            $html .= '<div class="itemdiv commentdiv">';
            $html .= '    <div class="user">';
            $html .= '        <img alt="'.$this->userAccountDetails($row->account_id).'\'s Avatar" src="'.site_url().'public/assets/images/avatars/avatar2.png">';
            $html .= '    </div>';

            $html .= '    <div class="body">';
            $html .= '        <div class="name">';
            $html .=            $this->userAccountDetails($row->account_id);
            $html .= '        </div>';

            $html .= '        <div class="time">';
            $html .= '            <i class="ace-icon fa fa-calendar"></i>';
            $html .= '            <span class="green">'.date('m-d-Y H:i A',strtotime($row->created_date)).'</span>';
            $html .= '        </div>';

            $html .= '        <div class="text">';
            $html .= '            <i class="ace-icon fa fa-quote-left"></i>';
            $html .= '            <a href="'.site_url().'collection/information/'.$sales_order_id.'/view">Sales Order</a>';
            $html .= '            <i class="ace-icon fa fa-quote-right"></i><br>';
            $html .=              $row->comments;
            $html .= '        </div>';
            $html .= '    </div>';
            $html .= '</div>';
        }
        
        return $html;
    }
    
    private function invoiceComment($sales_order_id)
    {
        $html = '';
        $response = $this->SalesOrderModel->getInvoiceComments($sales_order_id);
        if($response->num_rows()>0)
        {
            $row = $response->row();
            $html .= '<div class="itemdiv commentdiv">';
            $html .= '    <div class="user">';
            $html .= '        <img alt="'.$this->userAccountDetails($row->approved_by).'\'s Avatar" src="'.site_url().'public/assets/images/avatars/avatar2.png">';
            $html .= '    </div>';

            $html .= '    <div class="body">';
            $html .= '        <div class="name">';
            $html .= $this->userAccountDetails($row->approved_by);
            $html .= '        </div>';

            $html .= '        <div class="time">';
            $html .= '            <i class="ace-icon fa fa-calendar"></i>';
            $html .= '            <span class="green">'.date('m-d-Y  H:i A',strtotime($row->created_date)).'</span>';
            $html .= '        </div>';

            $html .= '        <div class="text">';
            $html .= '            <i class="ace-icon fa fa-quote-left"></i>';
            $html .= '            <a href="'.site_url().'invoice/information/'.$sales_order_id.'/view">Invoice</a>';
            $html .= '            <i class="ace-icon fa fa-quote-right"></i><br>';
            $html .=              $row->message;
            $html .= '        </div>';
            $html .= '    </div>';
            $html .= '</div>';
        }
        
        return $html;
    }
    
    private function inventoryComment($sales_order_id)
    {
        $html = '';
        $response = $this->SalesOrderModel->getInventoryComments($sales_order_id);
        if($response->num_rows()>0)
        {
            $row = $response->row();
            $html .= '<div class="itemdiv commentdiv">';
            $html .= '    <div class="user">';
            $html .= '        <img alt="'.$this->userAccountDetails($row->approved_by).'\'s Avatar" src="'.site_url().'public/assets/images/avatars/avatar2.png">';
            $html .= '    </div>';

            $html .= '    <div class="body">';
            $html .= '        <div class="name">';
            $html .= $this->userAccountDetails($row->approved_by);
            $html .= '        </div>';

            $html .= '        <div class="time">';
            $html .= '            <i class="ace-icon fa fa-calendar"></i>';
            $html .= '            <span class="green">'.date('m-d-Y  H:i A',strtotime($row->created_date)).'</span>';
            $html .= '        </div>';

            $html .= '        <div class="text">';
            $html .= '            <i class="ace-icon fa fa-quote-left"></i>';
            $html .= '            <a href="'.site_url().'invoice/information/'.$sales_order_id.'/view">Inventory</a>';
            $html .= '            <i class="ace-icon fa fa-quote-right"></i><br>';
            $html .=              $row->message;
            $html .= '        </div>';
            $html .= '    </div>';
            $html .= '</div>';
        }
        
        return $html;
    }
    
    private function deliveryComment($sales_order_id)
    {
        $html = '';
        $response = $this->SalesOrderModel->getDeliveryComments($sales_order_id);
        if($response->num_rows()>0)
        {
            $row = $response->row();
            $html .= '<div class="itemdiv commentdiv">';
            $html .= '    <div class="user">';
            $html .= '        <img alt="'.$this->userAccountDetails($row->approved_by).'\'s Avatar" src="'.site_url().'public/assets/images/avatars/avatar2.png">';
            $html .= '    </div>';

            $html .= '    <div class="body">';
            $html .= '        <div class="name">';
            $html .= $this->userAccountDetails($row->approved_by);
            $html .= '        </div>';

            $html .= '        <div class="time">';
            $html .= '            <i class="ace-icon fa fa-calendar"></i>';
            $html .= '            <span class="green">'.date('m-d-Y H:i A',strtotime($row->created_date)).'</span>';
            $html .= '        </div>';

            $html .= '        <div class="text">';
            $html .= '            <i class="ace-icon fa fa-quote-left"></i>';
            $html .= '            <a href="'.site_url().'delivery/information/'.$sales_order_id.'/view">Delivery</a>';
            $html .= '            <i class="ace-icon fa fa-quote-right"></i><br>';
            $html .=              $row->message;
            $html .= '        </div>';
            $html .= '    </div>';
            $html .= '</div>';
        }
        
        return $html;
    }
    
    private function collectionComment($sales_order_id)
    {
        $html = '';
        $response = $this->SalesOrderModel->getCollectionComments($sales_order_id);
        if($response->num_rows()>0)
        {
            $row = $response->row();
            $html .= '<div class="itemdiv commentdiv">';
            $html .= '    <div class="user">';
            $html .= '        <img alt="'.$this->userAccountDetails($row->approved_by).'\'s Avatar" src="'.site_url().'public/assets/images/avatars/avatar2.png">';
            $html .= '    </div>';

            $html .= '    <div class="body">';
            $html .= '        <div class="name">';
            $html .= $this->userAccountDetails($row->approved_by);
            $html .= '        </div>';

            $html .= '        <div class="time">';
            $html .= '            <i class="ace-icon fa fa-calendar"></i>';
            $html .= '            <span class="green">'.date('m-d-Y H:i A',strtotime($row->created_date)).'</span>';
            $html .= '        </div>';

            $html .= '        <div class="text">';
            $html .= '            <i class="ace-icon fa fa-quote-left"></i>';
            $html .= '            <a href="'.site_url().'collection/information/'.$sales_order_id.'/view">Collection</a>';
            $html .= '            <i class="ace-icon fa fa-quote-right"></i><br>';
            $html .=              $row->message;
            $html .= '        </div>';
            $html .= '    </div>';
            $html .= '</div>';
        }
        
        return $html;
    }
    
    private function remittanceComment($sales_order_id)
    {
        $html = '';
        $response = $this->SalesOrderModel->getRemitttanceComments($sales_order_id);
        if($response->num_rows()>0)
        {
            $row = $response->row();
            $html .= '<div class="itemdiv commentdiv">';
            $html .= '    <div class="user">';
            $html .= '        <img alt="'.$this->userAccountDetails($row->approved_by).'\'s Avatar" src="'.site_url().'public/assets/images/avatars/avatar2.png">';
            $html .= '    </div>';

            $html .= '    <div class="body">';
            $html .= '        <div class="name">';
            $html .= $this->userAccountDetails($row->approved_by);
            $html .= '        </div>';
            
            $html .= '        <div class="time">';
            $html .= '            <i class="ace-icon fa fa-calendar"></i>';
            $html .= '            <span class="green">'.date('m-d-Y H:i A',strtotime($row->created_date)).'</span>';
            $html .= '        </div>';

            $html .= '        <div class="text">';
            $html .= '            <i class="ace-icon fa fa-quote-left"></i>';
            $html .= '            <a href="'.site_url().'remittance/information/'.$sales_order_id.'/view">Remittance</a>';
            $html .= '            <i class="ace-icon fa fa-quote-right white"></i><br>';
            $html .=              $row->message;
            $html .= '        </div>';
            $html .= '    </div>';
            $html .= '</div>';
        }
        
        return $html;
    }
    
    private function userAccountDetails($account_id)
    {
        $name = '';
        
        $row = $this->SalesOrderModel->getAccountDetails($account_id);
        
        if(count($row)>0)
        {
            $name = $row->name;
        }
        else
        {
            $name = 'Unknown Name';
        }
        
        return $name;
    }
    
    private function productList()
    {
        $this->load->model('ProductsModel');
        $options = '';
        $response = $this->ProductsModel->product_list();
        
        if($response->num_rows()>0)
        {
            $options .= '<option>Please select a product. . .</option>';
            foreach($response->result() as $products)
            {
                $options .= '<option value="'.$products->product_id.'">'.$products->product_name.'</option>';
            }
        }
        else
        {
            $options .= '<option>No Data Found!</option>';
        }
        
        return $options;
    }
    
    private function customerList()
    {
        $this->load->model('CustomersModel');
        $options = '';
        $response = $this->CustomersModel->getCustomersList();
        
        if($response->num_rows()>0)
        {
            $options .= '<option value="">Select a customer. . .</option>';
            foreach($response->result() as $products)
            {
                $options .= '<option value="'.$products->customers_id.'">'.$products->customer_name.'</option>';
            }
        }
        else
        {
            $options .= '<option>No Data Found!</option>';
        }
        
        return $options;
    }
}

?>
