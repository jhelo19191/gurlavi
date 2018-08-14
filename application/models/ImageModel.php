<?php defined('BASEPATH') OR exit('No direct script access allowed');

class ImageModel extends CI_Model
{
    protected $_images = 'images';
    
    public function getImageAttachment($sales_order_id,$process_type)
    {
        $where = array(
                        'sales_order_id' => $sales_order_id,
                        'process_type' => $process_type
                       );
        $this->db->where($where);
        $response = $this->db->get($this->_images);
        
        return $response;
    }
}

?>