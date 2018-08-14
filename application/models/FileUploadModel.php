<?php defined('BASEPATH') OR exit('No direct script access allowed');

class FileUploadModel extends CI_Model
{
    protected $_images = 'images';
    
    public function insert($sales_order_id,$process_type,$image_path)
    {
        $data = array(
                        'sales_order_id' => $sales_order_id,
                        'process_type' => $process_type,
                        'image_path' => $image_path
                      );
        $this->db->insert($this->_images,$data);
        
        return $this->db->affected_rows();
    }
    
    public function getImagePath($sales_order_id,$created_date)
    {
        $where = array(
                        'sales_order_id' => $sales_order_id,
                        'created_date' => $created_date
                       );
        $this->db->where($where);
        $response = $this->db->get($this->_images);
        
        return $response->row()->image_path;
    }
    
    public function checkImage($sales_order_id,$created_date)
    {
        $where = array(
                        'sales_order_id' => $sales_order_id,
                        'created_date' => $created_date
                       );
        $this->db->where($where);
        $response = $this->db->get($this->_images);
        
        return $response->num_rows();
    }
    
    public function remove($sales_order_id,$created_date)
    {
        $where = array(
                        'sales_order_id' => $sales_order_id,
                        'created_date' => $created_date
                       );
        $this->db->where($where);
        $response = $this->db->delete($this->_images);
        
        return $response->db->affected_rows();
    }
}


?>