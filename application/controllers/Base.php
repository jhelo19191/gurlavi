<?php defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('ASIA/MANILA');
	
class Base extends CI_Controller
{
    protected $_admin_count = 0;

    function __construct()
    {
      parent::__construct();
      $this->load->model('AuthModel');
      $this->load->model('NotificationModel');
      $this->_admin_count = $this->AuthModel->checkAdministrator();
    }

    public function render($pages='',$data=array(),$template = 'templates/index')
    {
        $this->data['content'] = $this->load->view($pages,$data,true);
        
        $this->data['notification'] = $this->notification();
		
		$this->data['messages'] = $this->getMessages();
		
		$param1 = $this->uri->segment(1);
		$param2 = $this->uri->segment(2);
		
		$user_level = $this->session->userdata('user_level');
		
		$boolean = $this->parseCheckPermission($user_level,$param1,$param2);
		
		if($boolean==false):
			redirect('pages/invalid_access');
		endif;
		
        $this->load->view($template,$this->data);
    }
	
	public function paymentStatus($flag)
    {
        $segment = $this->uri->segment(4);
        switch($flag)
        {
            case '1' : $status = 'Waiting'; break;
            case '2' : $status = "Remittance"; break;
            case '3' : $status = 'Remitted'; break;
            case '4' : $status = 'Checked'; break;
            case '5' : $status = 'Rejected'; break;
            case '9' : $status = 'Cleared'; break;
            default: $status = 'Pending'; break;
        }
        
        return $status;
    }
	
	private function parseCheckPermission($user_level,$param1,$param2)
	{
		$flag = false;
		$config = $this->account_permission();
		
		$permission = $config['permission'][$user_level][$param1];
		
		if(isset($permission))
		{
			foreach($permission as $active_page)
			{
				if($active_page==$param2)
				{
					$flag = true;
					break;
				}
			}	
		}
		
		return $flag;
	}

    public function login()
    {
      $data['num_rows'] = $this->_admin_count;

      $this->load->view('templates/login',$data);
    }

    public function register()
    {
      if($this->_admin_count==0){
        $this->load->view('templates/register');
      } else {
        redirect();
      }

    }
    
    public function checkFields($array)
    {
        $data['result'] = 'success';
        
        foreach($array as $key => $value)
        {
            if($value==''):
                $data['result'] = 'fail';
                $data['message'] = $key . ' is empty.';
                break;
            endif;
        }
        
        return $data;
    }
	
	public function requestStatus($status)
	{
		$type = '';
		switch($status)
		{
			case '0' : $type = 'Requesting for invoice'; break;
			case '1' : $type = 'Requesting for items'; break;
			case '2' : $type = 'For delivery'; break;
			case '3' : $type = 'For collection'; break;
			case '4' : $type = 'Waiting to validate'; break;
			case '5' : $type = 'Requesting for remittance no.'; break;
			case '6' : $type = 'For cheque validation'; break;
			case '9' : $type = 'Cleared'; break;
			default: $type = 'Cancelled'; break;
		}
		
		return $type;
	}
    
	private function account_permission()
	{
		$config['permission'] = array(
				0 => array(
							'pages' => array(
													'index',
													'sales_order',
													'invoice',
													'inventory',
													'delivery',
													'check',
													'collection',
													'remittance',
													'register',
													'validation',
													'products',
													'customers',
													'onhold',
													'newUser',
													'invalid_access','remittancePdf'
												   ),
							'salesorder' => array(
													'register'
												  ),
							'salesOrder' => array(
													'information',
													'request',
													'insert',
													'update',
													'modify',
													'remove',
													'register'
												  ),
							'invoice' => array(
													'information',
													'request',
													'insert',
													'update',
													'modify',
													'remove'
												  ),
							'delivery' => array(
													'information',
													'request',
													'insert',
													'update',
													'modify',
													'remove'
												  ),
							'collection' => array(
													'information',
													'request',
													'insert',
													'update',
													'modify',
													'remove'
												  ),
							'check' => array(
													'information',
													'request',
													'insert',
													'update',
													'modify',
													'remove'
												  ),
							'remittance' => array(
													'information',
													'request',
													'insert',
													'update',
													'modify',
													'remove'
												  ),
							'validation' => array(
													'information',
													'request',
													'insert',
													'update',
													'modify',
													'remove'
												  ),
							'customer' => array(
													'index',
													'insert',
													'update',
													'modify',
													'remove'
												  ),
							'products' => array(
													'index',
													'insert',
													'update',
													'modify',
													'remove'
												  )
						   ),
				1 => array(
							'pages' => array(
													'index',
													'sales_order',
													'invoice',
													'inventory',
													'delivery',
													'collection',
													'check',
													'remittance',
													'validation',
													'onhold',
													'invalid_access'
												   ),
							'salesorder' => array(
													'register'
												  ),
							'salesOrder' => array(
													'information'
												  ),
							'invoice' => array(
													'information',
													'request'
												  ),
							'delivery' => array(
													'information'
												  ),
							'collection' => array(
													'information'
												  ),
							'check' => array(
													'information'
												  ),
							'remittance' => array(
													'information',
													'request',
													'insert'
												  ),
							'validation' => array(
													'information',
													'request',
													'insert'
												  )
						   ),
				2 => array(
							'pages' => array(
													'index',
													'sales_order',
													'invoice',
													'inventory',
													'delivery',
													'check',
													'remittance',
													'validation',
													'onhold',
													'invalid_access'
												   ),
							'salesOrder' => array(
													'information',
													'register'
												  ),
							'invoice' => array(
													'information'
												  ),
							'delivery' => array(
													'information'
												  ),
							'collection' => array(
													'information'
												  ),
							'check' => array(
													'information'
												  ),
							'remittance' => array(
													'information'
												  ),
							'validation' => array(
													'information'
												  )
						   ),
				3 => array(
							'pages' => array(
													'index',
													'sales_order',
													'invoice',
													'inventory',
													'collection',
													'delivery',
													'check',
													'remittance',
													'validation',
													'onhold',
													'invalid_access'
												   ),
							'invoice' => array(
													'information'
												  ),
							'delivery' => array(
													'information'
												  )
						   ),
				4 => array(
							'pages' => array(
													'index',
													'sales_order',
													'invoice',
													'inventory',
													'collection',
													'delivery',
													'check',
													'remittance',
													'validation',
													'onhold',
													'invalid_access'
												   ),
							'salesOrder' => array(
													'information',
													'request'
												  ),
							'invoice' => array(
													'information'
												  ),
							'delivery' => array(
													'information',
													'request'
												  ),
							'collection' => array(
													'information',
													'request',
													'insert'
												  ),
							'check' => array(
													'information',
													'request',
													'insert'
												  ),
							'remittance' => array(
													'information'
												  ),
							'validation' => array(
													'information'
												  )
						   )
				
									  );
		
		return $config;
	}
	
    private function notification()
    {
        $html = '';
        $response = $this->NotificationModel->getNotification();
        $data['num_rows'] = $this->NotificationModel->countNotification();
        
        if($response->num_rows()>0)
        {
            foreach($response->result() as $items)
            {
                $html .= '<li>';
                $html .= '    <a href="'.site_url('salesOrder/information/'.$items->sales_order_id).'">';
                $html .= '        <div class="clearfix">';
                $html .=    			$items->message;
                $html .= '        </div>';
                $html .= '    </a>';
                $html .= '</li>';
            }    
        }
        else
        {
                $html .= '<li>';
                $html .= ' No Data Found!';
                $html .= '</li>';
        }
        
        $data['html'] = $html;
        
        return $data;
    }
	
	private function getMessages()
	{
		$account_id = $this->session->userdata('account_id');
		$html = '';
		$data['num_rows'] = $this->NotificationModel->countActiveMessage($account_id);
		$response = $this->NotificationModel->getAllRejectMessage($account_id);
		
		if($response->num_rows()>0)
		{
			foreach($response->result() as $message)
			{
				$html .= '<li>';
				$html .= '	<a href="'.site_url('collection/information/'.$this->getSalesOrderID($message->payment_id,$message->payment_type).'/view/'.$message->payment_id.'/'.$message->payment_type).'" class="clearfix">';
				$html .= '		<img src="'.site_url().'public/assets/images/avatars/avatar4.png" class="msg-photo" alt="'.$message->name.'" />';
				$html .= '		<span class="msg-body">';
				$html .= '			<span class="msg-title">';
				$html .= '				<span class="blue">'.$message->name.'</span><br>';
				$html .= 				$message->message;
				$html .= '			</span>';
			
				$html .= '			<span class="msg-time">';
				$html .= '				<i class="ace-icon fa fa-calendar"></i>';
				$html .= '				<span>'.date('F d, Y',strtotime($message->created_date)).'</span>';
				$html .= '			</span>';
				$html .= '		</span>';
				$html .= '	</a>';
				$html .= '</li>';
			}
		}
		else
		{
			$html .= '<li>';
			$html .= ' No Data Found!';
			$html .= '</li>';
		}
		
		$data['html'] = $html;
		
		return $data;
	}
	
	private function getSalesOrderID($payment_id,$payment_type)
	{
		$this->load->model('CollectionModel');
		
		$sales_order_id = 0;
		
		switch($payment_type)
		{
			case '1' :
					$row = $this->CollectionModel->getCashInfoByID($payment_id);
					if(count($row)>0):
						$sales_order_id = $row->sales_order_id;
					endif;
					break;
			case '2' :
					$row = $this->CollectionModel->getPdcInfoByID($payment_id);
					if(count($row)>0):
						$sales_order_id = $row->sales_order_id;
					endif;
					break;
			case '3' :
					$row = $this->CollectionModel->getCreditInfoByID($payment_id);
					if(count($row)>0):
						$sales_order_id = $row->sales_order_id;
					endif;
					break;
			case '4' :
					$row = $this->CollectionModel->getCashInfoByID($payment_id);
					if(count($row)>0):
						$sales_order_id = $row->sales_order_id;
					endif;
					break;
			case '5' :
					$row = $this->CollectionModel->getPdcInfoByID($payment_id);
					if(count($row)>0):
						$sales_order_id = $row->sales_order_id;
					endif;
					break;
			case '6' :
					$row = $this->CollectionModel->getBankingInfo($payment_id);
					if(count($row)>0):
						$sales_order_id = $row->sales_order_id;
					endif;
					break;
		}
		
		return $sales_order_id;
	}
	
	public function setAttachment($sales_order_id,$process_type)
    {
        $this->load->model('ImageModel');
        
        $html = '';
        
        $response = $this->ImageModel->getImageAttachment($sales_order_id,$process_type);
        if($response->num_rows()>0)
        {
            foreach($response->result() as $value)
            {
                $html .= '<li>';
                $html .= '    <a href="'.site_url($value->image_path).'" data-rel="colorbox">';
                $html .= '        <img width="150" height="150" alt="150x150" src="'.site_url($value->image_path).'" />';
                $html .= '        <div class="text">';
                $html .= '            <div class="inner">Sample Caption on Hover</div>';
                $html .= '        </div>';
                $html .= '    </a>';
                $html .= '</li>';
            }
        }
        else
        {
            $html .= '<li>';
            $html .= '    There is no attachment.';
            $html .= '</li>';
        }
        
        return $html;
    }
}


?>
