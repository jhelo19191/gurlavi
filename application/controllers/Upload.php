<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Upload extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('excel');
        $this->load->model('ProductsModel');
        $this->load->model('CustomersModel');
    }
    
    public function products()
    {
        //Check valid upload has been uploaded
        $response = array();
        foreach($_FILES as $key => $file)
        {
            $inputFile = $file['tmp_name'];
            $extension = strtoupper(pathinfo($file['name'], PATHINFO_EXTENSION));
            if($extension == 'XLSX' || $extension == 'ODS'){
        
                //Read spreadsheeet workbook
                try {
                    $inputFileType = PHPExcel_IOFactory::identify($inputFile);
                    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                    $objPHPExcel = $objReader->load($inputFile);
                } catch(Exception $e) {
                        die($e->getMessage());
                }
                
                $objPHPExcel->setActiveSheetIndex(0);
                $sheet = $objPHPExcel->getActiveSheet();
                
                $maxCell = $sheet->getHighestRowAndColumn();
                $dataValue = $sheet->rangeToArray('A3:' . $maxCell['column'] . $maxCell['row']);
                
                foreach($dataValue as $key=>$value)
                {
                    if($value[0]!='' || $value[0]!=null):
                        $response[] = $this->uploadData($value[0],($value[1]!=''?$value[1]:'Brand not available'),$value[0],$value[2]);
                    endif;
                }
                
                
                echo "<pre>".json_encode($response,JSON_PRETTY_PRINT)."</pre>";
            }
            else{
                echo "Please upload an XLSX or ODS file";
            }
            
        }       
    }
    
    public function customers()
    {
        //Check valid upload has been uploaded
        $response = array();
        foreach($_FILES as $key => $file)
        {
            $inputFile = $file['tmp_name'];
            $extension = strtoupper(pathinfo($file['name'], PATHINFO_EXTENSION));
            if($extension == 'XLSX' || $extension == 'ODS' || $extension == 'XLS'){
        
                //Read spreadsheeet workbook
                try {
                    $inputFileType = PHPExcel_IOFactory::identify($inputFile);
                    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                    $objPHPExcel = $objReader->load($inputFile);
                } catch(Exception $e) {
                        die($e->getMessage());
                }
                
                $objPHPExcel->setActiveSheetIndex(0);
                $sheet = $objPHPExcel->getActiveSheet();
                
                $maxCell = $sheet->getHighestRowAndColumn();
                $dataValue = $sheet->rangeToArray('A6:' . $maxCell['column'] . $maxCell['row']);
                
                foreach($dataValue as $key=>$value)
                {
                    echo "<pre>".json_encode($value,JSON_PRETTY_PRINT)."</pre>";
                    if($value[0]!='' || $value[0]!=null):
                        $response[] = $this->uploadCustomersData($value[0],$value[1].($value[2]!=''? ', '.$value[2]:'').
                                                                 ($value[3]!=''? ', '.$value[3]:'').($value[4]!=''? ', '.$value[4]:''),
                                                                 ($value[5]==''?'000000000000':$value[5]),$value[6],$value[7]);
                    endif;
                }
                
                
                echo "<pre>".json_encode($response,JSON_PRETTY_PRINT)."</pre>";
            }
            else{
                echo "Please upload an XLSX or ODS or XLS file";
            }
            
        }       
    }
    
    private function uploadCustomersData($customer_name,$address,$contact_no,$owner,$status)
    {
        $num_rows = $this->CustomersModel->checkCustomersAddressName($customer_name,$address);
        
        if($num_rows==0)
        {
            $response = $this->CustomersModel->insertCustomers($customer_name,$address,$contact_no,$owner,$status);
            
            if($response==1)
            {
                $data['result'] = 'success';
                $data['message'] = 'Data uploaded successfully.';
            }
            else
            {
                $data['result'] = 'fail';
                $data['message'] = 'Unable to insert the record.';
            }   
        }
        else
        {
            $data['result'] = 'fail';
            $data['message'] = 'Product already uploaded.';
        }
        
        return $data;
    }
    
    private function uploadData($material_id,$brand,$product_name,$description)
    {
        $num_rows = $this->ProductsModel->checkMaterialID($material_id);
        
        if($num_rows==0)
        {
            $response = $this->ProductsModel->uploadData($material_id,$brand,$product_name,$description);
            
            if($response==1)
            {
                $data['result'] = 'success';
                $data['message'] = 'Data uploaded successfully.';
            }
            else
            {
                $data['result'] = 'fail';
                $data['message'] = 'Unable to insert the record.';
            }    
        }
        else
        {
            $data['result'] = 'fail';
            $data['message'] = 'Product already uploaded.';
        }
        
        
        return $data;
    }
}

?>