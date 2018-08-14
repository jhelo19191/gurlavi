<?php defined('BASEPATH') OR exit('No direct script access allowed');

class CustomersModel extends CI_Model
{
    protected $_customers = 'customers';
    
    public function insert($customer_name,$address,$contact_no,$tin)
    {
        $data = array(
                        'customer_name' => $customer_name,
                        'address' => $address,
                        'contact_no' => $contact_no,
                        'tin' => $tin
                      );
        $this->db->insert($this->_customers,$data);
        
        return $this->db->affected_rows();
    }
    
    public function insertCustomers($customer_name,$address,$contact_no,$owner,$status)
    {
        $data = array(
                        'customer_name' => $customer_name,
                        'address' => $address,
                        'contact_no' => $contact_no,
                        'tin' => 000000000000,
                        'owner' => $owner,
                        'status' => (trim(strtolower($status))=='active'?1:0)
                      );
        $this->db->insert($this->_customers,$data);
        
        return $this->db->affected_rows();
    }
    
    public function checkCustomersAddressName($customer_name,$address)
    {
        $where = array(
                        'customer_name' => $customer_name,
                        'address' => $address
                       );
        $this->db->where($where);
        $response = $this->db->get($this->_customers);
        
        return $response->num_rows();
    }
    
    public function update($customer_id,$customer_name,$address,$contact_no,$tin)
    {
        $where = array(
                        'customers_id' => $customer_id
                       );
        $data = array(
                        'customer_name' => $customer_name,
                        'address' => $address,
                        'contact_no' => $contact_no,
                        'tin' => $tin
                      );
        $this->db->where($where);
        $this->db->update($this->_customers,$data);
        
        return $this->db->affected_rows();
    }
    
    public function remove($product_id)
    {
        $where = array(
                        'customers_id' => $product_id
                       );
        $this->db->where($where);
        $this->db->delete($this->_customers);
        
        return $this->db->affected_rows();
    }
    
    public function getCustomerRecords($search)
    {
        $this->db->like('customer_name',$search);
        $this->db->or_like('address',$search);
        $this->db->or_like('contact_no',$search);
        $this->db->or_like('tin',$search);
        
        return $this->db->get($this->_customers);
    }
    
    public function checkCustomerTIN($tin)
    {
        $where=array(
                        'tin' => $tin
                     );
        $this->db->where($where);
        $response = $this->db->get($this->_customers);
        
        return $response->num_rows();
    }
    
    public function getCustomerInfo($product_id)
    {
        $where = array(
                        'customers_id' => $product_id
                       );
        $this->db->where($where);
        $response = $this->db->get($this->_customers);
        
        return $response->row();
    }
    
    public function checkUpdateRecord($customer_id,$tin)
    {
        $where = array(
                        'customers_id <>' => $customer_id,
                        'tin' => $tin
                       );
        $this->db->where($where);
        $response = $this->db->get($this->_customers);
        
        return $response->num_rows();
    }
    
    
    public function getCustomersList()
    {
        $response = $this->db->get($this->_customers);
        
        return $response;
    }
}


?>