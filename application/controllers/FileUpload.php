<?php defined('BASEPATH') OR exit('No direct script access allowed');

class FileUpload extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('FileUploadModel');
    }
    
    
    public function image()
    {
        
        foreach($_FILES as $key => $file){
            $filename = $file["name"];
             
            $target_file = $target_dir . basename($filename);
            $data = $this->processCheck($target_file,$file,$sales_order_id,$filename);
            $data_w_filename = array_merge($data,array('filename'=>$filename));
            $message_container[] = $data_w_filename;
        } 
    }
    
    private function processCheck($target_file,$file,$sales_order_id,$filename,$process_type)
    {
        $flag = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    
        $data = $this->checkImageFile($file);
        if($data['result']=='fail'):
            $flag = 0;
        endif;
        
        //$data = $this->checkFileExist($target_file);
        //if($data['result']=='fail'):
        //    $flag = 0;
        //endif;
        
        $data = $this->checkFileSize($file);
        if($data['result']=='fail'):
            $flag = 0;
        endif;
        
        $data = $this->checkFileFormat($imageFileType);
        if($data['result']=='fail'):
            $flag = 0;
        endif;
        
        if($flag==1):
            $data = $this->checkUploadStatus($flag,$file,$target_file,$sales_order_id,$process_type);
        endif;
        
        return $data;
    }
    
    private function checkImageFile($file)
    {
        $data['result'] = 'success';
        $check = getimagesize($file["tmp_name"]);
        if($check !== false) {
            $data['result'] = 'success';
            $data['message'] = "File is an image - " . $check["mime"] . ".";
        } else {
            $data['result'] = 'fail';
            $data['message'] = 'File is not an image.';
        }
            
        return $data;
    }
    
    private function checkFileExist($target_file)
    {
        $data['result'] = 'success';
        if (file_exists($target_file)) {
            $data['result'] = 'fail';
            $data['message'] = 'Sorry, file already exists.';
        }
    }
    
    private function checkFileSize($file)
    {
        $data['result'] = 'success';
        if ($file["size"] > 500000) {
            $data['result'] = 'fail';
            $data['message'] = 'Sorry, your file is too large.';
        }
        
        return $data;
    }
    
    private function checkFileFormat($imageFileType)
    {
        $data['result'] = 'success';
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            $data['result'] = 'fail';
            $data['message'] = 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.';
        }
        
        return $data;
    }
    
    private function checkUploadStatus($flag,$file,$target_file,$sales_order_id,$process_type)
    {
        if ($flag == 0) {
            $data['result'] = 'fail';
            $data['message'] = "Sorry, your file was not uploaded.";
        } else {
            $target_file = "uploads/".$sales_order_id.$file['name'];
            
            if (move_uploaded_file($file["tmp_name"], $target_file)) {
                $response = $this->insert($sales_order_id,$process_type,$target_file);
                $data['result'] = 'success';
                $data['message'] = "The file ". basename( $file["name"]). " has been uploaded.";
            } else {
                $data['result'] = 'fail';
                $data['message'] = "Sorry, there was an error uploading your file. Filename: ".$file["name"];
            }
        }
        
        return $data;
    }
    
    private function insert($sales_order_id,$process_type,$image_path)
    {
        $response = $this->FileUploadModel->insert($sales_order_id,$process_type,$image_path);
        
        return $response;
    }
}


?>