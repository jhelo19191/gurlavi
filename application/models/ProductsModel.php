<?php defined('BASEPATH') OR exit('No direct script access allowed');

class ProductsModel extends CI_Model
{
    protected $_products = 'products';
    
    public function insert($product_name,$description)
    {
        $data = array(
                        'product_name' => $product_name,
                        'description' => $description
                      );
        $this->db->insert($this->_products,$data);
        
        return $this->db->affected_rows();
    }
    
    public function countSalesOrder($search)
    {
        $this->db->where('(`material_id` LIKE \'%'.$search.'%\' OR `brand` LIKE \'%'.$search.'%\' OR `product_name` LIKE \'%'.$search.'%\' OR `description` LIKE \'%'.$search.'%\')', NULL, FALSE);
        
        $query = $this->db->get($this->_products);
        
        return $query->num_rows();
    }
    
    public function fetch_products($start,$limit,$search,$type_ordering)
    {
        $this->db->limit($limit,$start);
        $this->db->order_by('product_id', $type_ordering);
        
        $this->db->where('(`material_id` LIKE \'%'.$search.'%\' OR `brand` LIKE \'%'.$search.'%\' OR `product_name` LIKE \'%'.$search.'%\' OR `description` LIKE \'%'.$search.'%\')', NULL, FALSE);
        
        $query = $this->db->get($this->_products);
        
        if($query->num_rows() > 0)
        {
            foreach($query->result() as $row)
            {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }
    
    public function update($product_id,$product_name,$description)
    {
        $where = array(
                        'product_id' => $product_id
                       );
        $data = array(
                        'product_name' => $product_name,
                        'description' => $description
                      );
        $this->db->where($where);
        $this->db->update($this->_products,$data);
        
        return $this->db->affected_rows();
    }
    
    public function remove($product_id)
    {
        $where = array(
                        'product_id' => $product_id
                       );
        $this->db->where($where);
        $this->db->delete($this->_products);
        
        return $this->db->affected_rows();
    }
    
    public function getProductRecords($search)
    {
        $this->db->like('product_name',$search);
        $this->db->or_like('description',$search);
        
        return $this->db->get($this->_products);
    }
    
    public function checkProductName($product_name)
    {
        $where=array(
                        'product_name' => $product_name
                     );
        $this->db->where($where);
        $response = $this->db->get($this->_products);
        
        return $response->num_rows();
    }
    
    public function getProductInfo($product_id)
    {
        $where = array(
                        'product_id' => $product_id
                       );
        $this->db->where($where);
        $response = $this->db->get($this->_products);
        
        return $response->row();
    }
    
    public function checkUpdateRecord($product_name,$product_id)
    {
        $where = array(
                        'product_name !=' => $product_name,
                        'product_id' => $product_id
                       );
        $this->db->where($where);
        $response = $this->db->get($this->_products);
        
        return $response->num_rows();
    }
    
    
    public function productList()
    {
        $response = $this->db->get('products');
        
        return $response;
    }
    
    public function checkProductRecord($product_id)
    {
        $where = array(
                        'product_id' => $product_id
                       );
        $this->db->where($where);
        $response = $this->db->get($this->_products);
        
        return $response->num_rows();
    }
    
    public function uploadData($material_id,$brand,$product_name,$description)
    {
        $data = array(
                        'material_id' => $material_id,
                        'brand' => $brand,
                        'product_name' => $product_name,
                        'description' => $description
                      );
        
        $this->db->insert($this->_products,$data);
        
        return $this->db->affected_rows();
    }
    
    public function truncateTable()
    {
        return $this->db->truncate($this->_products);
    }
    
    public function checkMaterialID($material_id)
    {
        $where = array(
                        'material_id' => $material_id
                       );
        $this->db->where($where);
        $response = $this->db->get($this->_products);
        
        return $response->num_rows();
    }
    
    public function product_list()
    {
        return $this->db->get($this->_products);
    }
}


?>