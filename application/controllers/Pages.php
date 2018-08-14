<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once('Base.php');

class Pages extends Base
{
	protected $_account_id = '';
	protected $_user_level = '';
	
	function __construct()
	{
	  parent::__construct();
	  $this->_account_id = $this->session->userdata('account_id');
	  $this->_user_level = $this->session->userdata('user_level');
	}

	public function index()
	{
		if(!$this->session->userdata('logged_in'))
		{
		  $this->login();
		}
		else if($this->session->userdata('user_level')!=3)
		{
			$this->home();
		}
		else
		{
			redirect('pages/inventory');
		}
	}
	
	public function home()
	{
		$this->load->model('SalesOrderModel');
			
		$data['active_module'] = 'transaction';
		$data['main_crubs'] = 'Transaction Record';
		$data['sub_crubs'] = 'Sales Order / Main List';
		$data['active_tab'] = 'sales_order';
		$data['header_title'] = 'Transaction Management';
		
		$search = $this->input->post('search');
		$daterange = $this->input->post('date-range-picker');
		$level = $this->input->post('level');
		
		$set_session = $this->set_session($search,$daterange,$level);
		
		$this->session->set_userdata($set_session);
		
		$data['segment'] = $this->uri->segment(3);
		$pages_type = $this->uri->segment(2);
		
		if($daterange!=''):
			$explode = explode(' - ',$daterange);
			$start = date('Y-m-d',strtotime($explode[0]));
			$end = date('Y-m-d',strtotime($explode[1]));
			else:
			$start=date('Y-m-d',strtotime(date('Y-m-d'). ' - 30days'));
			$end=date('Y-m-d');
		endif;
		
		$data['start'] = date('m/d/Y',strtotime(date('Y-m-d'). ' - 30days'));
		$data['end'] = date('m/d/Y');
		
		$data['page_type'] = $pages_type;
		
		$num_rows = $this->SalesOrderModel->countSalesOrder($search,$level,$start,$end,$this->_account_id,$this->_user_level);
		
		$data['so_num_rows'] = $num_rows;
		
		$pages = $this->pagination('sales_order','pages/index',$num_rows,'DESC',$search,$level,$start,$end,$this->_account_id,$this->_user_level);
		
		$data['html'] = $this->soHtmlBody($pages['result']);
		$data['links'] = $pages['links'];
		
		$this->render('pages/sales_order',$data);
	}
	
	private function set_session($search,$daterange,$level)
	{
		if($search!='' || $daterange!='' || $level!='')
		{
			$set_session = array(
									'search' => $search,
									'daterange' => $daterange,
									'level' => $level
								 );
		}
		else if($this->session->userdata('search') || $this->session->userdata('daterange') || $this->session->userdata('level'))
		{
			$search = $this->session->userdata('search');
			$daterange = $this->session->userdata('daterange');
			$level = $this->session->userdata('level');
			
			$set_session = array(
								'search' => $search,
								'daterange' => $daterange,
								'level' => $level
							 );
		}
		else
		{
			$set_session = array(
								'search' => '',
								'daterange' => '',
								'level' => ''
							 );
		}
		
		return $set_session;
	}
	
	public function register($pages='',$responsed=array())
	{
	  
		if(!$this->session->userdata('logged_in'))
		{
		  $this->login();
		}
		else
		{
			$data['active_module'] = 'transaction';
			$data['main_crubs'] = 'Account';
			$data['sub_crubs'] = 'New User Registration';
			$data['active_tab'] = 'sales_order';
			$data['header_title'] = '<i class="fa fa-users"></i> New User Registration';
			
			if(isset($responsed['result'])):
				$data['result'] = $responsed['result'];
				$data['message'] = $responsed['message'];
			endif;
			
			$this->render('pages/new_user',$data);
		}
	}
	
	public function newUser()
	{
		$this->load->model('AuthModel');
		$name = $this->input->post('account_name');
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$email = $this->input->post('email');
		$position = $this->input->post('position');
		$user_level = $this->input->post('user_level');
		$c_password = $this->input->post('c_password');
		$c_number = $this->input->post('c_number');
  
		$container = array(
						  'Confirm Password' => $c_password,
						  'Password' => $password,
						  'Username' => $username,
						  'User Level' => $user_level,
						  'Email' => $email,
						  'Position' => $position,
						  'Contact Number' => $c_number,
						  'Name' => $name
						);
  
		$message = $this->checkFields($container);
  
		if($message['result']=='success')
		{
			$checkUsername = $this->AuthModel->checkUsername($username);
			if($checkUsername==0)
			{
				if($password==$c_password)
				{
					$password = $this->encrypt->encode($password);
					$response = $this->AuthModel->createAccount($name,$username,$password,$user_level,$email,$position,$c_number);
					if($response==1) {
					  $data['result'] = 'success';
					  $data['message'] = 'Account was successully created.';
					} else {
					  $data['result'] = 'fail';
					  $data['message'] = 'Account creation failed.';
					}
				}
				else
				{
					$data['result'] = 'fail';
					$data['message'] = 'Password and Confirm Password does not matched.';
				}
			}
			else
			{
				$data['result'] = 'fail';
				$data['message'] = 'Username is already used.';
			}
		}
		else
		{
			$data['result'] = $message['result'];
			$data['message'] = $message['message'];
		}
  
		$this->register('',$data);
	}
	
	private function soHtmlBody($result)
	{
		
		$html = '';
		if($result!=false)
		{
			$iter=0;
			foreach($result as $records)
			{
				$html .= '<tr>';
				$html .= '<td>'.$records->sales_order_id.'</td>';
				$html .= '<td align="center"><a href="'.site_url('salesOrder/information/'.$records->sales_order_id).'" title="View Details"><i class="fa fa-eye"></i></a></td>';
				$html .= '<td>'.$records->sales_order_no.'</td>';
				$html .= '<td>'.$records->psr_name.'</td>';
				$html .= '<td>'.$records->customer_name.'</td>';
				$html .= '<td>'.date('F d, Y',strtotime($records->so_date)).'</td>';
				$html .= '<td>'.date('F d, Y',strtotime($records->delivery_date)).'</td>';
				$html .= '<td>'.date('F d, Y',strtotime($records->created_date)).'</td>';
				$html .= '<td><span class="label label-md '.($records->status==9?'label-success':'label-warning').'">'.$this->requestStatus($records->status).'</span></td>';
				$html .= '<td><button class="btn btn-xs btn-primary show-comments" data-value="'.$records->sales_order_id.'"><i class="ace-icon fa fa-comments bigger-120"></i></button></td>';
				$html .= '</tr>';
			}
		}
		else
		{
			$html .= '<tr>';
			$html .= '<td align="center" colspan="10">No Data Found!</td>';
			$html .= '</tr>';
		}
		
		return $html;
	}
	
	public function invoice()
	{
		if(!$this->session->userdata('logged_in'))
		{
		  $this->login();
		}
		else
		{
			$this->load->model('InvoiceModel');
			
			$data['active_module'] = 'transaction';
			$data['active_tab'] = 'invoice';
			$data['main_crubs'] = 'Transaction Record';
			$data['sub_crubs'] = 'Invoice';
			$data['header_title'] = 'Transaction Management';
			
			$search = $this->input->post('search');
			$data['segment'] = $this->uri->segment(3);
			$pages_type = $this->uri->segment(2);
			
			$data['page_type'] = $pages_type;
			
			$num_rows = $this->InvoiceModel->countSalesOrder($search,$this->_account_id,$this->_user_level);
			
			$data['si_num_rows'] = $num_rows;
			
			$pages = $this->paginationInvoice('sales_order','pages/invoice',$num_rows,'DESC',$search,$this->_account_id,$this->_user_level);
			
			$data['html'] = $this->invoiceHtmlBody($pages['result']);
			$data['links'] = $pages['links'];
			
			$this->render('pages/sales_order',$data);
		}
	}
	
	private function invoiceHtmlBody($result)
	{
		$html = '';
		if($result!=false)
		{
			$iter=0;
			foreach($result as $records)
			{
				$html .= '<tr>';
				$html .= '<td>'.$records->sales_order_id.'</td>';
				$html .= '<td align="center"><a href="'.site_url('salesOrder/information/'.$records->sales_order_id).'" title="View Details"><i class="fa fa-eye"></i></a></td>';
				$html .= '<td>'.$records->sales_order_no.'</td>';
				$html .= '<td>'.$records->psr_name.'</td>';
				$html .= '<td>'.$records->customer_name.'</td>';
				$html .= '<td>'.date('F d, Y',strtotime($records->so_date)).'</td>';
				$html .= '<td>'.date('F d, Y',strtotime($records->delivery_date)).'</td>';
				$html .= '<td>'.date('F d, Y',strtotime($records->created_date)).'</td>';
				$html .= '<td><span class="label label-md  '.($records->status==9?'label-success':'label-warning').'"">'.$this->requestStatus($records->status).'</span></td>';
				
				if($this->session->userdata('user_level')==1||$this->session->userdata('user_level')==0):
					$html .= '<td>';
					$html .= '<div class="hidden-sm hidden-xs btn-group">';
					$html .= '   <a href="'.site_url('salesOrder/information/'.$records->sales_order_id.'/request').'" class="btn btn-xs btn-warning">';
					$html .= '     <i class="ace-icon fa fa-flag bigger-120"></i>';
					$html .= '   </a>';
					$html .= '</div>';
					$html .= '</td>';
				endif;
				$html .= '</tr>';
			}
		}
		else
		{
			$html .= '<tr>';
			$html .= '<td align="center" colspan="10">No Data Found!</td>';
			$html .= '</tr>';
		}
		
		return $html;
	}
	
	public function inventory()
	{
		if(!$this->session->userdata('logged_in'))
		{
		  $this->login();
		}
		else
		{
			$this->load->model('InventoryModel');
			
			$data['active_module'] = 'transaction';
			$data['active_tab'] = 'inventory';
			$data['main_crubs'] = 'Transaction Record';
			$data['sub_crubs'] = 'Inventory Transfer';
			$data['header_title'] = 'Transaction Management';
			
			$search = $this->input->post('search');
			$data['segment'] = $this->uri->segment(3);
			$pages_type = $this->uri->segment(2);
			
			$data['page_type'] = $pages_type;
			
			$num_rows = $this->InventoryModel->countSalesOrder($search,$this->_account_id,$this->_user_level);
			
			$data['request_count'] = $num_rows;
			
			$pages = $this->paginationInventory('sales_order','pages/inventory',$num_rows,'DESC',$search,$this->_account_id,$this->_user_level);
			
			$data['html'] = $this->inventoryHtmlBody($pages['result']);
			$data['links'] = $pages['links'];
			
			$this->render('pages/sales_order',$data);
		}
	}
	
	private function inventoryHtmlBody($result)
	{
		$html = '';
		if($result!=false)
		{
			$iter=0;
			foreach($result as $records)
			{
				$html .= '<tr>';
				$html .= '<td>'.$records->sales_order_id.'</td>';
				$html .= '<td align="center"><a href="'.site_url('invoice/information/'.$records->sales_order_id.'/view').'" title="View Details"><i class="fa fa-eye"></i></a></td>';
				$html .= '<td>'.$records->invoice_no.'</td>';
				$html .= '<td>'.$records->psr_name.'</td>';
				$html .= '<td>'.$records->customer_name.'</td>';
				$html .= '<td>'.date('F d, Y',strtotime($records->invoice_date)).'</td>';
				$html .= '<td>'.date('F d, Y',strtotime($records->delivery_date)).'</td>';
				$html .= '<td>'.date('F d, Y',strtotime($records->created_date)).'</td>';
				$html .= '<td><span class="label label-md  '.($records->status==9?'label-success':'label-warning').'"">'.$this->requestStatus($records->status).'</span></td>';
				if($this->session->userdata('user_level')==3 || $this->session->userdata('user_level')==0):
				$html .= '<td>';
				$html .= '<div class="hidden-sm hidden-xs btn-group">';
				$html .= '   <a class="btn btn-xs btn-warning request-invoice" href="'.site_url('invoice/information/'.$records->sales_order_id.'/inventory').'" data-value="'.$records->sales_order_id.'">';
				$html .= '     <i class="ace-icon fa fa-flag bigger-120"></i>';
				$html .= '   </a>';
				$html .= '</div>';
				$html .= '</td>';
				endif;
				$html .= '</tr>';
			}
		}
		else
		{
			$html .= '<tr>';
			$html .= '<td align="center" colspan="10">No Data Found!</td>';
			$html .= '</tr>';
		}
		
		return $html;
	}
	
	public function delivery()
	{
		if(!$this->session->userdata('logged_in'))
		{
		  $this->login();
		}
		else
		{
			$this->load->model('DeliveryModel');
			
			$data['active_module'] = 'transaction';
			$data['main_crubs'] = 'Transaction Record';
			$data['sub_crubs'] = 'Delivery';
			$data['active_tab'] = 'sales_order';
			$data['header_title'] = 'Transaction Management';
			
			$search = $this->input->post('search');
			$data['segment'] = $this->uri->segment(3);
			$pages_type = $this->uri->segment(2);
			
			$data['page_type'] = $pages_type;
			
			$num_rows = $this->DeliveryModel->countSalesOrder($search,$this->_account_id,$this->_user_level);
			
			$data['delivery_counts'] = $num_rows;
			
			$pages = $this->paginationDelivery('sales_order','pages/delivery',$num_rows,'DESC',$search,$this->_account_id,$this->_user_level);
			
			$data['html'] = $this->deliveryHtmlBody($pages['result']);
			$data['links'] = $pages['links'];
			
			$this->render('pages/sales_order',$data);
		}
	}
	
	private function deliveryHtmlBody($result)
	{
		$html = '';
		if($result!=false)
		{
			$iter=0;
			foreach($result as $records)
			{
				$html .= '<tr>';
				$html .= '<td>'.$records->sales_order_id.'</td>';
				$html .= '<td align="center"><a href="'.site_url('delivery/information/'.$records->sales_order_id.'/inventory').'" title="View Details"><i class="fa fa-eye"></i></a></td>';
				$html .= '<td>'.$records->invoice_no.'</td>';
				$html .= '<td>'.$records->psr_name.'</td>';
				$html .= '<td>'.$records->customer_name.'</td>';
				$html .= '<td>'.date('F d, Y',strtotime($records->invoice_date)).'</td>';
				$html .= '<td>'.date('F d, Y',strtotime($records->delivery_date)).'</td>';
				$html .= '<td>'.date('F d, Y',strtotime($records->created_date)).'</td>';
				$html .= '<td><span class="label label-md  '.($records->status==9?'label-success':'label-warning').'"">'.$this->requestStatus($records->status).'</span></td>';
				if($this->session->userdata('user_level')==2 || $this->session->userdata('user_level')==4 || $this->session->userdata('user_level')==0):
				$html .= '<td>';
				$html .= '<div class="hidden-sm hidden-xs btn-group">';
				$html .= '   <a class="btn btn-xs btn-warning request-invoice" href="'.site_url('delivery/information/'.$records->sales_order_id.'/request').'" data-value="'.$records->sales_order_id.'">';
				$html .= '     <i class="ace-icon fa fa-flag bigger-120"></i>';
				$html .= '   </a>';
				$html .= '</div>';
				$html .= '</td>';
				endif;
				$html .= '</tr>';
			}
		}
		else
		{
			$html .= '<tr>';
			$html .= '<td align="center" colspan="10">No Data Found!</td>';
			$html .= '</tr>';
		}
		
		return $html;
	}
	
	public function collection()
	{
		if(!$this->session->userdata('logged_in'))
		{
		  $this->login();
		}
		else
		{
			$this->load->model('CollectionModel');
			
			$data['active_module'] = 'transaction';
			$data['main_crubs'] = 'Transaction Record';
			$data['sub_crubs'] = 'Collection';
			$data['active_tab'] = 'collection';
			$data['header_title'] = 'Transaction Management';
			
			$search = $this->input->post('search');
			$data['segment'] = $this->uri->segment(3);
			$pages_type = $this->uri->segment(2);
			
			$data['page_type'] = $pages_type;
			
			$num_rows = $this->CollectionModel->countSalesOrder($search,$this->_account_id,$this->_user_level);
			
			$data['collect_count'] = $num_rows;
			
			$pages = $this->paginationCollection('sales_order','pages/collection',$num_rows,'DESC',$search,$this->_account_id,$this->_user_level);
			
			$data['html'] = $this->collectionHtmlBody($pages['result']);
			$data['links'] = $pages['links'];
			
			$this->render('pages/sales_order',$data);
		}
	}
	
	private function collectionHtmlBody($result)
	{
		$html = '';
		if($result!=false)
		{
			$iter=0;
			foreach($result as $records)
			{
				$html .= '<tr>';
				$html .= '<td>'.$records->sales_order_id.'</td>';
				$html .= '<td align="center"><a href="'.site_url('collection/information/'.$records->sales_order_id.'/view').'" title="View Details"><i class="fa fa-eye"></i></a></td>';
				$html .= '<td>'.$records->invoice_no.'</td>';
				$html .= '<td>'.$records->psr_name.'</td>';
				$html .= '<td>'.$records->customer_name.'</td>';
				$html .= '<td>'.date('F d, Y',strtotime($records->invoice_date)).'</td>';
				$html .= '<td>'.date('F d, Y',strtotime($records->delivery_date)).'</td>';
				$html .= '<td>'.date('F d, Y',strtotime($records->created_date)).'</td>';
				$html .= '<td><span class="label label-md  '.($records->status==9?'label-success':'label-warning').'"">'.$this->requestStatus($records->status).'</span></td>';
				if($this->session->userdata('user_level')==2 || $this->session->userdata('user_level')==4 || $this->session->userdata('user_level')==0):
				$html .= '<td>';
				$html .= '<div class="hidden-sm hidden-xs btn-group">';
				$html .= '   <a class="btn btn-xs btn-warning request-invoice" href="'.site_url('collection/information/'.$records->sales_order_id.'/request').'" data-value="'.$records->sales_order_id.'">';
				$html .= '     <i class="ace-icon fa fa-flag bigger-120"></i>';
				$html .= '   </a>';
				$html .= '</div>';
				$html .= '</td>';
				endif;
				$html .= '</tr>';
			}
		}
		else
		{
			$html .= '<tr>';
			$html .= '<td align="center" colspan="10">No Data Found!</td>';
			$html .= '</tr>';
		}
		
		return $html;
	}
	
	public function check()
	{
		if(!$this->session->userdata('logged_in'))
		{
		  $this->login();
		}
		else
		{
			$this->load->model('CheckModel');
			
			$data['active_module'] = 'transaction';
			$data['active_tab'] = 'check';
			$data['main_crubs'] = 'Transaction Record';
			$data['sub_crubs'] = 'Check';
			$data['header_title'] = 'Transaction Management';
			
			$search = $this->input->post('search');
			$data['segment'] = $this->uri->segment(3);
			$pages_type = $this->uri->segment(2);
			
			$data['page_type'] = $pages_type;
			
			$num_rows = $this->CheckModel->countSalesOrder($search,$this->_account_id,$this->_user_level);
			
			$data['check_counts'] = $num_rows;
			
			$pages = $this->paginationCheck('sales_order','pages/check',$num_rows,'DESC',$search,$this->_account_id,$this->_user_level);
			
			$data['html'] = $this->checkHtmlBody($pages['result']);
			$data['links'] = $pages['links'];
			
			$this->render('pages/sales_order',$data);
		}
	}
	
	private function checkHtmlBody($result)
	{
		$html = '';
		if($result!=false)
		{
			$iter=0;
			foreach($result as $records)
			{
				$html .= '<tr>';
				$html .= '<td>'.$records->sales_order_id.'</td>';
				$html .= '<td align="center"><a href="'.site_url('check/information/'.$records->sales_order_id.'/view').'" title="View Details"><i class="fa fa-eye"></i></a></td>';
				$html .= '<td>'.$records->invoice_no.'</td>';
				$html .= '<td>'.$records->psr_name.'</td>';
				$html .= '<td>'.$records->customer_name.'</td>';
				$html .= '<td>'.date('F d, Y',strtotime($records->invoice_date)).'</td>';
				$html .= '<td>'.date('F d, Y',strtotime($records->delivery_date)).'</td>';
				$html .= '<td>'.date('F d, Y',strtotime($records->created_date)).'</td>';
				$html .= '<td><span class="label label-md  '.($records->status==9?'label-success':'label-warning').'"">'.$this->requestStatus($records->status).'</span></td>';
				$html .= '<td>';
				$html .= '<div class="hidden-sm hidden-xs btn-group">';
				$html .= '   <a class="btn btn-xs btn-warning request-invoice" href="'.site_url('check/information/'.$records->sales_order_id.'/request').'" data-value="'.$records->sales_order_id.'">';
				$html .= '     <i class="ace-icon fa fa-flag bigger-120"></i>';
				$html .= '   </a>';
				$html .= '</div>';
				$html .= '</td>';
				$html .= '</tr>';
			}
		}
		else
		{
			$html .= '<tr>';
			$html .= '<td align="center" colspan="10">No Data Found!</td>';
			$html .= '</tr>';
		}
		
		return $html;
	}
	
	public function remittance()
	{
		if(!$this->session->userdata('logged_in'))
		{
		  $this->login();
		}
		else
		{
			$this->load->model('RemittanceModel');
			
			$data['active_module'] = 'transaction';
			$data['active_tab'] = 'remittance';
			$data['main_crubs'] = 'Transaction Record';
			$data['sub_crubs'] = 'Remittance';
			$data['header_title'] = 'Transaction Management';
			
			$search = $this->input->post('search');
			$data['segment'] = $this->uri->segment(3);
			$pages_type = $this->uri->segment(2);
			
			$data['page_type'] = $pages_type;
			
			$num_rows = $this->RemittanceModel->countSalesOrder($search,$this->_account_id,$this->_user_level);
			
			$data['remittance_count'] = $num_rows;
			
			$pages = $this->paginationRemittance('sales_order','pages/check',$num_rows,'DESC',$search,$this->_account_id,$this->_user_level);
			
			$data['html'] = $this->remittanceHtmlBody($pages['result']);
			$data['links'] = $pages['links'];
			
			$this->render('pages/sales_order',$data);
		}
	}
	
	private function remittanceHtmlBody($result)
	{
		$html = '';
		if($result!=false)
		{
			$iter=0;
			foreach($result as $records)
			{
				$html .= '<tr>';
				$html .= '<td>'.$records->sales_order_id.'</td>';
				$html .= '<td align="center"><a href="'.site_url('remittance/information/'.$records->sales_order_id.'/view').'" title="View Details"><i class="fa fa-eye"></i></a></td>';
				$html .= '<td>'.$records->invoice_no.'</td>';
				$html .= '<td>'.$records->psr_name.'</td>';
				$html .= '<td>'.$records->customer_name.'</td>';
				$html .= '<td>'.date('F d, Y',strtotime($records->invoice_date)).'</td>';
				$html .= '<td>'.date('F d, Y',strtotime($records->delivery_date)).'</td>';
				$html .= '<td>'.date('F d, Y',strtotime($records->created_date)).'</td>';
				$html .= '<td><span class="label label-md  '.($records->status==9?'label-success':'label-warning').'"">'.$this->requestStatus($records->status).'</span></td>';
				if($this->session->userdata('user_level')==1 || $this->session->userdata('user_level')==0):
				$html .= '<td>';
				$html .= '<div class="hidden-sm hidden-xs btn-group">';
				$html .= '   <a class="btn btn-xs btn-warning request-invoice" href="'.site_url('remittance/information/'.$records->sales_order_id.'/request').'" data-value="'.$records->sales_order_id.'">';
				$html .= '     <i class="ace-icon fa fa-flag bigger-120"></i>';
				$html .= '   </a>';
				$html .= '</div>';
				$html .= '</td>';
				endif;
				$html .= '</tr>';
			}
		}
		else
		{
			$html .= '<tr>';
			$html .= '<td align="center" colspan="10">No Data Found!</td>';
			$html .= '</tr>';
		}
		
		return $html;
	}
	
	public function validation()
	{
		if(!$this->session->userdata('logged_in'))
		{
		  $this->login();
		}
		else
		{
			$this->load->model('ValidationModel');
			
			$data['active_module'] = 'transaction';
			$data['active_tab'] = 'validation';
			$data['main_crubs'] = 'Transaction Record';
			$data['sub_crubs'] = 'Validation';
			$data['header_title'] = 'Transaction Management';
			
			$search = $this->input->post('search');
			$data['segment'] = $this->uri->segment(3);
			$pages_type = $this->uri->segment(2);
			
			$data['page_type'] = $pages_type;
			
			$num_rows = $this->ValidationModel->countSalesOrder($search,$this->_account_id,$this->_user_level);
			
			$data['validation_count'] = $num_rows;
			
			$pages = $this->paginationValidation('sales_order','pages/validation',$num_rows,'DESC',$search,$this->_account_id,$this->_user_level);
			
			$data['html'] = $this->validationHtmlBody($pages['result']);
			$data['links'] = $pages['links'];
			
			$this->render('pages/sales_order',$data);
		}
	}
	
	private function validationHtmlBody($result)
	{
		$html = '';
		if($result!=false)
		{
			$iter=0;
			foreach($result as $records)
			{
				$html .= '<tr>';
				$html .= '<td>'.$records->sales_order_id.'</td>';
				$html .= '<td align="center"><a href="'.site_url('validation/information/'.$records->sales_order_id.'/view').'" title="View Details"><i class="fa fa-eye"></i></a></td>';
				$html .= '<td>'.$records->remittance_no.'</td>';
				$html .= '<td>'.$records->psr_name.'</td>';
				$html .= '<td>'.$records->customer_name.'</td>';
				$html .= '<td>'.date('F d, Y',strtotime($records->remittance_date)).'</td>';
				$html .= '<td>'.date('F d, Y',strtotime($records->delivery_date)).'</td>';
				$html .= '<td>'.date('F d, Y',strtotime($records->created_date)).'</td>';
				$html .= '<td><span class="label label-md '.($records->status==9?'label-success':'label-warning').'">'.$this->requestStatus($records->status).'</span></td>';
				if($this->session->userdata('user_level')==1 || $this->session->userdata('user_level')==0):
				$html .= '<td>';
				$html .= '<div class="hidden-sm hidden-xs btn-group">';
				$html .= '   <a class="btn btn-xs btn-warning request-invoice" href="'.site_url('validation/information/'.$records->sales_order_id.'/request/'.$records->remit_id).'" data-value="'.$records->sales_order_id.'">';
				$html .= '     <i class="ace-icon fa fa-flag bigger-120"></i>';
				$html .= '   </a>';
				$html .= '</div>';
				$html .= '</td>';
				endif;
				$html .= '</tr>';
			}
		}
		else
		{
			$html .= '<tr>';
			$html .= '<td align="center" colspan="10">No Data Found!</td>';
			$html .= '</tr>';
		}
		
		return $html;
	}
	
	public function products()
	{
		if(!$this->session->userdata('logged_in'))
		{
		  $this->login();
		}
		else
		{
			$this->load->model('ProductsModel');
			$data['active_module'] = 'products';
			$data['main_crubs'] = 'Products Record';
			$data['sub_crubs'] = 'List';
            $data['header_title'] = 'Product Management';
			
			$search = $this->input->post('search');
			
			$num_rows = $this->ProductsModel->countSalesOrder($search);
			
			$data['record_counts'] = $num_rows;
			
			$pages = $this->paginationProducts('pages/products',$num_rows,'DESC',$search);
			
			$data['html'] = $this->productList($pages['result']);
			$data['links'] = $pages['links'];
			
			$this->render('pages/products',$data);
		}
	}
	
	private function productList($result)
	{
		$html = '';
		
		if($result!=false || $result!=0)
		{
			foreach($result as $items)
			{
				$html .= '<tr id="'.$items->product_id.'">';
				$html .= '<td class="center">';
				$html .= $items->product_id;
				$html .= '</td>';
				$html .= '<td>'.$items->material_id.'</td>';
				$html .= '<td>'.$items->brand.'</td>';
				$html .= '<td>'.$items->product_name.'</td>';
				$html .= '<td>'.$items->description.'</td>';
				$html .= '<td>'.date('F d, Y',strtotime($items->created_date)).'</td>';
				$html .= '<td width="300" align="center">';
				$html .= '<div class="hidden-sm hidden-xs btn-group">';

                $html .= '  <a href="'.site_url('products/modify/'.$items->product_id).'" class="btn btn-info">';
                $html .= '    <i class="ace-icon fa fa-pencil bigger-120"></i> Modify';
				$html .= '  </a>';

                $html .= '  <button class="btn btn-danger remove" data-value="'.$items->product_id.'">';
                $html .= '    <i class="ace-icon fa fa-trash-o bigger-120"></i> Delete';
                $html .= '  </button>';
				
                $html .= '</div>';
				$html .= '</td>';
				$html .= '</tr>';
			}
		}
		else
		{
			$html .= '<tr>';
			$html .= '<td align="center" colspan="7">No Data Found</td>';
			$html .= '</tr>';
		}
		
		return $html;
	}
	
	public function customers()
	{
		if(!$this->session->userdata('logged_in'))
		{
		  $this->login();
		}
		else
		{
			$data['active_module'] = 'customers';
			$data['main_crubs'] = 'Customers Record';
			$data['sub_crubs'] = 'List';
            $data['header_title'] = 'Customers Management';
			
			$search = $this->input->post('search');
			
			$data['html'] = $this->customersList($search);
			
			$this->render('pages/customers',$data);
		}
	}
	
	public function onhold($pages='',$responsed=array())
	{
		if(!$this->session->userdata('logged_in'))
		{
		  $this->login();
		}
		else
		{
			$this->load->model('OnholdModel');
			$data['active_module'] = 'onhold';
			$data['main_crubs'] = 'Payment On-Hold';
			$data['sub_crubs'] = 'List';
            $data['header_title'] = 'Payment On-Hold';
			
			$search = $this->input->post('search');
			
			$num_rows = $this->OnholdModel->countHoldedPayment($search,$this->_account_id,$this->_user_level);
			
			$pages = $this->paginationOnhold('onhold','pages/onhold',$num_rows,'DESC',$search,$this->_account_id,$this->_user_level);
			
			$data['options'] = $this->accountList();
			
			$data['html'] = $this->onholdHtmlBody($pages['result']);
			$data['links'] = $pages['links'];
			
			$this->render('pages/onhold',$data);
		}
	}
	
	private function onholdHtmlBody($result)
	{
		$html = '';
		if($result!=false)
		{
			$iter=0;
			foreach($result as $records)
			{
				$html .= '<tr>';
				$html .= '<td>'.$records->transaction_id.'</td>';
				$html .= '<td>'.$records->name.'</td>';
				$html .= '<td>PDC</td>';
				$html .= '<td>'.$records->amount.'</td>';
				$html .= '<td>'.$records->created_by.'</td>';
				$html .= '<td>'.date('F d, Y',strtotime($records->updated_date)).'</td>';
				$html .= '<td>'.date('F d, Y',strtotime($records->created_date)).'</td>';
				if($this->session->userdata('user_level')==0 || $this->session->userdata('user_level')==1):
				$html .= '<td align="center">';
                $html .= '    <button class="btn btn-danger btn-sm btn-delete" data-value="'.$records->transaction_id.'"><i class="fa fa-trash"></i> Delete</button>';
                $html .= '    <button class="btn btn-primary btn-sm btn-modify" data-value="'.$records->transaction_id.'"><i class="fa fa-edit"></i> Modify</button>';
                $html .= '</td>';
				endif;
				$html .= '</tr>';
			}
		}
		else
		{
			$html .= '<tr>';
			$html .= '<td align="center" colspan="10">No Data Found!</td>';
			$html .= '</tr>';
		}
		
		return $html;
	}
	
	private function customersList($search)
	{
		$this->load->model('CustomersModel');
		$html = '';
		
		$response = $this->CustomersModel->getCustomerRecords($search);
		if($response->num_rows()>0)
		{
			foreach($response->result() as $items)
			{
				$html .= '<tr id="'.$items->customers_id.'">';
				$html .= '<td class="center">';
				$html .= $items->customers_id;
				$html .= '</td>';
				$html .= '<td>'.$items->customer_name.'</td>';
				$html .= '<td>'.$items->address.'</td>';
				$html .= '<td>'.$items->contact_no.'</td>';
				$html .= '<td>'.$items->tin.'</td>';
				$html .= '<td>'.date('F d, Y',strtotime($items->created_date)).'</td>';
				$html .= '<td width="300" align="center">';
				$html .= '<div class="hidden-sm hidden-xs btn-group">';

                $html .= '  <a href="'.site_url('customer/modify/'.$items->customers_id).'" class="btn btn-info">';
                $html .= '    <i class="ace-icon fa fa-pencil bigger-120"></i> Modify';
				$html .= '  </a>';

                $html .= '  <button class="btn btn-danger remove" data-value="'.$items->customers_id.'">';
                $html .= '    <i class="ace-icon fa fa-trash-o bigger-120"></i> Delete';
                $html .= '  </button>';
				
                $html .= '</div>';
				$html .= '</td>';
				$html .= '</tr>';
			}
		}
		else
		{
			$html .= '<tr>';
			$html .= '<td align="center" colspan="7">No Data Found</td>';
			$html .= '</tr>';
		}
		
		return $html;
	}
	
	private function paginationProducts($link,$num_rows,$type_ordering='DESC',$search_string='')
	{
        $this->load->model("ProductsModel");
        $this->load->helper('text');
		$this->load->library('pagination');	
		 
		$config = array();
		$config["base_url"] = base_url() . $link;
		$config["first_url"] = base_url() . $link;
		$config["total_rows"] = $num_rows;
		
		$config["per_page"] = 6;
		$config["uri_segment"] = 3;
		
		$config['num_tag_open'] = "<li>";$config['num_tag_close'] = "</li>";
		$config['num_tag_open'] = "<li>";$config['num_tag_close'] = "</li>";
		$config['last_tag_open'] = "<li>";$config['last_tag_close'] = "</li>";
		$config['first_tag_open'] = "<li>"; $config['first_tag_close'] = "</li>";
		$config['next_tag_open'] = "<li>";$config['next_tag_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";$config['prev_tag_close'] = "</li>";
		$config['cur_tag_open'] = "<li class='active'><a href='#'>";$config['cur_tag_close'] = "</a></li>";
        
        $this->pagination->initialize($config);
		
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		
		$data["result"] = $this->ProductsModel->fetch_products($page,$config["per_page"],$search_string,$type_ordering);
        $data["links"] = $this->pagination->create_links();
        
        return $data;
    }
	
	private function pagination($tbName='',$link,$num_rows,$type_ordering='DESC',$search_string='',$level,$start,$end,$account_id,$user_level)
	{
        $this->load->model("SalesOrderModel");
        $this->load->helper('text');
		$this->load->library('pagination');	
		 
		$config = array();
		$config["base_url"] = base_url() . $link;
		$config["first_url"] = base_url() . $link;
		$config["total_rows"] = $num_rows;
		
		$config["per_page"] = 6;
		$config["uri_segment"] = 3;
		
		$config['num_tag_open'] = "<li>";$config['num_tag_close'] = "</li>";
		$config['num_tag_open'] = "<li>";$config['num_tag_close'] = "</li>";
		$config['last_tag_open'] = "<li>";$config['last_tag_close'] = "</li>";
		$config['first_tag_open'] = "<li>"; $config['first_tag_close'] = "</li>";
		$config['next_tag_open'] = "<li>";$config['next_tag_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";$config['prev_tag_close'] = "</li>";
		$config['cur_tag_open'] = "<li class='active'><a href='#'>";$config['cur_tag_close'] = "</a></li>";
        
        $this->pagination->initialize($config);
        $this->load->model('BaseModel');
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		
		$data["result"] = $this->SalesOrderModel->fetch_sales_order($tbName,$page,$config["per_page"],$search_string,$type_ordering,$level,$start,$end,$account_id,$user_level);
        $data["links"] = $this->pagination->create_links();
        
        return $data;
    }
	
	private function paginationRcollection($tbName='',$link,$num_rows,$type_ordering='DESC',$search_string='')
	{
        $this->load->model("RcollectionModel");
        $this->load->helper('text');
		$this->load->library('pagination');	
		 
		 //$this->RcollectionModel->getChildTable();
		 
		$config = array();
		$config["base_url"] = base_url() . $link;
		$config["first_url"] = base_url() . $link;
		$config["total_rows"] = $num_rows;
		
		$config["per_page"] = 6;
		$config["uri_segment"] = 3;
		
		$config['num_tag_open'] = "<li>";$config['num_tag_close'] = "</li>";
		$config['num_tag_open'] = "<li>";$config['num_tag_close'] = "</li>";
		$config['last_tag_open'] = "<li>";$config['last_tag_close'] = "</li>";
		$config['first_tag_open'] = "<li>"; $config['first_tag_close'] = "</li>";
		$config['next_tag_open'] = "<li>";$config['next_tag_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";$config['prev_tag_close'] = "</li>";
		$config['cur_tag_open'] = "<li class='active'><a href='#'>";$config['cur_tag_close'] = "</a></li>";
        
        $this->pagination->initialize($config);
        
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		
		$data["result"] = $this->RcollectionModel->fetch_collection($tbName,$page,$config["per_page"],$search_string,$type_ordering);
        $data["links"] = $this->pagination->create_links();
        
        return $data;
    }
	
	private function paginationCollection($tbName='',$link,$num_rows,$type_ordering='DESC',$search_string='',$account_id,$user_level)
	{
        $this->load->model("CollectionModel");
        $this->load->helper('text');
		$this->load->library('pagination');	
		 
		$config = array();
		$config["base_url"] = base_url() . $link;
		$config["first_url"] = base_url() . $link;
		$config["total_rows"] = $num_rows;
		
		$config["per_page"] = 6;
		$config["uri_segment"] = 3;
		
		$config['num_tag_open'] = "<li>";$config['num_tag_close'] = "</li>";
		$config['num_tag_open'] = "<li>";$config['num_tag_close'] = "</li>";
		$config['last_tag_open'] = "<li>";$config['last_tag_close'] = "</li>";
		$config['first_tag_open'] = "<li>"; $config['first_tag_close'] = "</li>";
		$config['next_tag_open'] = "<li>";$config['next_tag_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";$config['prev_tag_close'] = "</li>";
		$config['cur_tag_open'] = "<li class='active'><a href='#'>";$config['cur_tag_close'] = "</a></li>";
        
        $this->pagination->initialize($config);
        
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		
		$data["result"] = $this->CollectionModel->fetch_collection($tbName,$page,$config["per_page"],$search_string,$type_ordering,$account_id,$user_level);
        $data["links"] = $this->pagination->create_links();
        
        return $data;
    }
	
	private function paginationRemittance($tbName='',$link,$num_rows,$type_ordering='DESC',$search_string='',$account_id,$user_level)
	{
        $this->load->model("RemittanceModel");
        $this->load->helper('text');
		$this->load->library('pagination');	
		 
		$config = array();
		$config["base_url"] = base_url() . $link;
		$config["first_url"] = base_url() . $link;
		$config["total_rows"] = $num_rows;
		
		$config["per_page"] = 6;
		$config["uri_segment"] = 3;
		
		$config['num_tag_open'] = "<li>";$config['num_tag_close'] = "</li>";
		$config['num_tag_open'] = "<li>";$config['num_tag_close'] = "</li>";
		$config['last_tag_open'] = "<li>";$config['last_tag_close'] = "</li>";
		$config['first_tag_open'] = "<li>"; $config['first_tag_close'] = "</li>";
		$config['next_tag_open'] = "<li>";$config['next_tag_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";$config['prev_tag_close'] = "</li>";
		$config['cur_tag_open'] = "<li class='active'><a href='#'>";$config['cur_tag_close'] = "</a></li>";
        
        $this->pagination->initialize($config);
        
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		
		$data["result"] = $this->RemittanceModel->fetch_remittance($tbName,$page,$config["per_page"],$search_string,$type_ordering,$account_id,$user_level);
        $data["links"] = $this->pagination->create_links();
        
        return $data;
    }
	
	private function paginationOnhold($tbName='',$link,$num_rows,$type_ordering='DESC',$search_string='',$account_id,$receiver_id)
	{
        $this->load->model("OnholdModel");
        $this->load->helper('text');
		$this->load->library('pagination');	
		 
		$config = array();
		$config["base_url"] = base_url() . $link;
		$config["first_url"] = base_url() . $link;
		$config["total_rows"] = $num_rows;
		
		$config["per_page"] = 6;
		$config["uri_segment"] = 3;
		
		$config['num_tag_open'] = "<li>";$config['num_tag_close'] = "</li>";
		$config['num_tag_open'] = "<li>";$config['num_tag_close'] = "</li>";
		$config['last_tag_open'] = "<li>";$config['last_tag_close'] = "</li>";
		$config['first_tag_open'] = "<li>"; $config['first_tag_close'] = "</li>";
		$config['next_tag_open'] = "<li>";$config['next_tag_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";$config['prev_tag_close'] = "</li>";
		$config['cur_tag_open'] = "<li class='active'><a href='#'>";$config['cur_tag_close'] = "</a></li>";
        
        $this->pagination->initialize($config);
        
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		
		$data["result"] = $this->OnholdModel->fetch_payments($tbName,$page,$config["per_page"],$search_string,$type_ordering,$account_id,$receiver_id);
        $data["links"] = $this->pagination->create_links();
        
        return $data;
    }
	
	private function paginationValidation($tbName='',$link,$num_rows,$type_ordering='DESC',$search_string='',$account_id,$user_level)
	{
        $this->load->model("ValidationModel");
        $this->load->helper('text');
		$this->load->library('pagination');	
		 
		$config = array();
		$config["base_url"] = base_url() . $link;
		$config["first_url"] = base_url() . $link;
		$config["total_rows"] = $num_rows;
		
		$config["per_page"] = 6;
		$config["uri_segment"] = 3;
		
		$config['num_tag_open'] = "<li>";$config['num_tag_close'] = "</li>";
		$config['num_tag_open'] = "<li>";$config['num_tag_close'] = "</li>";
		$config['last_tag_open'] = "<li>";$config['last_tag_close'] = "</li>";
		$config['first_tag_open'] = "<li>"; $config['first_tag_close'] = "</li>";
		$config['next_tag_open'] = "<li>";$config['next_tag_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";$config['prev_tag_close'] = "</li>";
		$config['cur_tag_open'] = "<li class='active'><a href='#'>";$config['cur_tag_close'] = "</a></li>";
        
        $this->pagination->initialize($config);
        
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		
		$data["result"] = $this->ValidationModel->fetch_validation($tbName,$page,$config["per_page"],$search_string,$type_ordering,$account_id,$user_level);
        $data["links"] = $this->pagination->create_links();
        
        return $data;
    }
	
	private function paginationCheck($tbName='',$link,$num_rows,$type_ordering='DESC',$search_string='',$account_id,$user_level)
	{
        $this->load->model("CheckModel");
        $this->load->helper('text');
		$this->load->library('pagination');	
		 
		$config = array();
		$config["base_url"] = base_url() . $link;
		$config["first_url"] = base_url() . $link;
		$config["total_rows"] = $num_rows;
		
		$config["per_page"] = 6;
		$config["uri_segment"] = 3;
		
		$config['num_tag_open'] = "<li>";$config['num_tag_close'] = "</li>";
		$config['num_tag_open'] = "<li>";$config['num_tag_close'] = "</li>";
		$config['last_tag_open'] = "<li>";$config['last_tag_close'] = "</li>";
		$config['first_tag_open'] = "<li>"; $config['first_tag_close'] = "</li>";
		$config['next_tag_open'] = "<li>";$config['next_tag_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";$config['prev_tag_close'] = "</li>";
		$config['cur_tag_open'] = "<li class='active'><a href='#'>";$config['cur_tag_close'] = "</a></li>";
        
        $this->pagination->initialize($config);
        
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		
		$data["result"] = $this->CheckModel->fetch_check($tbName,$page,$config["per_page"],$search_string,$type_ordering,$account_id,$user_level);
        $data["links"] = $this->pagination->create_links();
        
        return $data;
    }
	
	private function paginationRcheck($tbName='',$link,$num_rows,$type_ordering='DESC',$search_string='')
	{
        $this->load->model("RcheckModel");
        $this->load->helper('text');
		$this->load->library('pagination');	
		 
		$config = array();
		$config["base_url"] = base_url() . $link;
		$config["first_url"] = base_url() . $link;
		$config["total_rows"] = $num_rows;
		
		$config["per_page"] = 6;
		$config["uri_segment"] = 3;
		
		$config['num_tag_open'] = "<li>";$config['num_tag_close'] = "</li>";
		$config['num_tag_open'] = "<li>";$config['num_tag_close'] = "</li>";
		$config['last_tag_open'] = "<li>";$config['last_tag_close'] = "</li>";
		$config['first_tag_open'] = "<li>"; $config['first_tag_close'] = "</li>";
		$config['next_tag_open'] = "<li>";$config['next_tag_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";$config['prev_tag_close'] = "</li>";
		$config['cur_tag_open'] = "<li class='active'><a href='#'>";$config['cur_tag_close'] = "</a></li>";
        
        $this->pagination->initialize($config);
        
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		
		$data["result"] = $this->RcheckModel->fetch_rcheck($tbName,$page,$config["per_page"],$search_string,$type_ordering);
        $data["links"] = $this->pagination->create_links();
        
        return $data;
    }
	
	private function paginationDelivery($tbName='',$link,$num_rows,$type_ordering='DESC',$search_string='',$account_id,$user_level)
	{
        $this->load->model("DeliveryModel");
        $this->load->helper('text');
		$this->load->library('pagination');	
		 
		$config = array();
		$config["base_url"] = base_url() . $link;
		$config["first_url"] = base_url() . $link;
		$config["total_rows"] = $num_rows;
		
		$config["per_page"] = 6;
		$config["uri_segment"] = 3;
		
		$config['num_tag_open'] = "<li>";$config['num_tag_close'] = "</li>";
		$config['num_tag_open'] = "<li>";$config['num_tag_close'] = "</li>";
		$config['last_tag_open'] = "<li>";$config['last_tag_close'] = "</li>";
		$config['first_tag_open'] = "<li>"; $config['first_tag_close'] = "</li>";
		$config['next_tag_open'] = "<li>";$config['next_tag_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";$config['prev_tag_close'] = "</li>";
		$config['cur_tag_open'] = "<li class='active'><a href='#'>";$config['cur_tag_close'] = "</a></li>";
        
        $this->pagination->initialize($config);
        
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		
		$data["result"] = $this->DeliveryModel->fetch_delivery($tbName,$page,$config["per_page"],$search_string,$type_ordering,$account_id,$user_level);
        $data["links"] = $this->pagination->create_links();
        
        return $data;
    }
	
	private function paginationInvoice($tbName='',$link,$num_rows,$type_ordering='DESC',$search_string='',$account_id,$user_level)
	{
        $this->load->model("SalesOrderModel");
        $this->load->helper('text');
		$this->load->library('pagination');	
		 
		$config = array();
		$config["base_url"] = base_url() . $link;
		$config["first_url"] = base_url() . $link;
		$config["total_rows"] = $num_rows;
		
		$config["per_page"] = 6;
		$config["uri_segment"] = 3;
		
		$config['num_tag_open'] = "<li>";$config['num_tag_close'] = "</li>";
		$config['num_tag_open'] = "<li>";$config['num_tag_close'] = "</li>";
		$config['last_tag_open'] = "<li>";$config['last_tag_close'] = "</li>";
		$config['first_tag_open'] = "<li>"; $config['first_tag_close'] = "</li>";
		$config['next_tag_open'] = "<li>";$config['next_tag_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";$config['prev_tag_close'] = "</li>";
		$config['cur_tag_open'] = "<li class='active'><a href='#'>";$config['cur_tag_close'] = "</a></li>";
        
        $this->pagination->initialize($config);
        
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		
		$data["result"] = $this->InvoiceModel->fetch_sales_invoice($tbName,$page,$config["per_page"],$search_string,$type_ordering,$account_id,$user_level);
        $data["links"] = $this->pagination->create_links();
        
        return $data;
    }
	
	private function paginationInventory($tbName='',$link,$num_rows,$type_ordering='DESC',$search_string='',$account_id,$user_level)
	{
        $this->load->model("SalesOrderModel");
        $this->load->helper('text');
		$this->load->library('pagination');	
		 
		$config = array();
		$config["base_url"] = base_url() . $link;
		$config["first_url"] = base_url() . $link;
		$config["total_rows"] = $num_rows;
		
		$config["per_page"] = 6;
		$config["uri_segment"] = 3;
		
		$config['num_tag_open'] = "<li>";$config['num_tag_close'] = "</li>";
		$config['num_tag_open'] = "<li>";$config['num_tag_close'] = "</li>";
		$config['last_tag_open'] = "<li>";$config['last_tag_close'] = "</li>";
		$config['first_tag_open'] = "<li>"; $config['first_tag_close'] = "</li>";
		$config['next_tag_open'] = "<li>";$config['next_tag_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";$config['prev_tag_close'] = "</li>";
		$config['cur_tag_open'] = "<li class='active'><a href='#'>";$config['cur_tag_close'] = "</a></li>";
        
        $this->pagination->initialize($config);
        
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		
		$data["result"] = $this->InventoryModel->fetch_inventory($tbName,$page,$config["per_page"],$search_string,$type_ordering,$account_id,$user_level);
        $data["links"] = $this->pagination->create_links();
        
        return $data;
    }
	
	public function invalid_access()
	{
		if(!$this->session->userdata('logged_in'))
		{
		  $this->login();
		}
		else
		{
			$data['active_module'] = 'transaction';
			$data['main_crubs'] = '';
			$data['sub_crubs'] = '';
			$data['header_title'] = '';
			
			$this->render('errors/access/access_error',$data);	
		}
	}
	
	public function create_account()
	{
		$this->load->view('templates/register');
	}
	
	private function accountList()
	{
		$this->load->model('OnholdModel');
		$response = $this->OnholdModel->getAccountList();
		$option = '';
		
		if($response->num_rows()>0)
		{
			foreach($response->result() as $items)
			{
				$option .= '<option value="'.$items->account_id.'">'.$items->name.'</option>';
			}
		}
		else
		{
			$option = '<option>No Data Found!</option>';
		}
		
		return $option;
	}
	
    public function remittancePdf()
    {
		$data['active_module'] = '';
		$data['main_crubs'] = '';
		$data['sub_crubs'] = '';
		$data['header_title'] = '';
        $this->render('pages/report/remittance_pdf_report',$data);
    }
}
