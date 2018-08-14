<?php defined('BASEPATH') OR exit('No direct script access allowed');

class UploadModel extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('FileUploadModel');
    }
    
    public function processCheck($target_file,$file,$sales_order_id,$filename,$process_type,$target_dir)
    {
        $flag = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    
        $data = $this->checkImageFile($file);
        
        if($data['result']=='fail'):
            $flag = 0;
        
            return $data;
        endif;
        
        $data = $this->checkFileSize($file);
        
        if($data['result']=='fail'):
            $flag = 0;
        
            return $data;
        endif;
        
        $data = $this->checkFileFormat($imageFileType);
        
        if($data['result']=='fail'):
            $flag = 0;
        
            return $data;
        endif;
        
        if($flag==1):
            $data = $this->checkUploadStatus($flag,$file,$target_file,$sales_order_id,$process_type,$target_dir);
        
            return $data;
        endif;
    }
    
    private function checkImageFile($file)
    {
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
        if (file_exists($target_file)) {
            $data['result'] = 'fail';
            $data['message'] = 'Sorry, file already exists.';
        }
    }
    
    private function checkFileSize($file)
    {
        if ($file["size"] > 500000) {
            $data['result'] = 'fail';
            $data['message'] = 'Sorry, your file is too large.';
        } else {
            $data['result'] = 'success';
            $data['message'] = 'Image File size: ' . $file["size"];
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
    
    private function checkUploadStatus($flag,$file,$target_file,$sales_order_id,$process_type,$target_dir)
    {
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        if ($flag == 0) {
            $data['result'] = 'fail';
            $data['message'] = "Sorry, your file was not uploaded.";
        } else {
            $target_file = $target_dir.date('YmdHis').'.'.$ext;
            
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
    
    public function uploadPaymentImage($target_file,$file,$filename,$target_dir)
    {
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        
        $target_file = $target_dir.date('YmdHis').'.'.$ext;
        
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            $data['result'] = 'success';
            $data['message'] = "The file ". basename( $file["name"]). " has been uploaded.";
            $data['image_path'] = $target_file;
        } else {
            $data['result'] = 'fail';
            $data['message'] = "Sorry, there was an error uploading your file. Filename: ".$file["name"];
        }
    
        return $data;
    }
    
    public function uploadImages($sales_order_id,$process_type,$file,$target_file,$target_dir,$i)
    {
        $ext = pathinfo($file['files']['name'][$i], PATHINFO_EXTENSION);
        
        $target_file = $target_dir.date('YmdHis').'.'.$ext;
        
        if (move_uploaded_file($file['files']["tmp_name"][$i], $target_file)) {
            $response = $this->insert($sales_order_id,$process_type,$target_file);
            $data['result'] = 'success';
            $data['message'] = "The file ". basename( $file['files']["name"][$i]). " has been uploaded.";
            $data['image_path'] = $target_file;
        } else {
            $data['result'] = 'fail';
            $data['message'] = "Sorry, there was an error uploading your file. Filename: ".$file["name"][$i];
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