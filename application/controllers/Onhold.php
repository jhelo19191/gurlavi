<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once('Pages.php');

class Onhold extends Pages
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('OnholdModel');
    }
    
    public function notification()
    {
        $activity = $this->input->post('activity');
        $transaction_id = $this->input->post('tid');
        $receiver_name = $this->input->post('receiver_name');
        $payment_type = $this->input->post('payment_type');
        $amount = $this->input->post('amount');
        $created_by = $this->input->post('created_by');
        
        switch($activity)
        {
            case 'add' : $data = $this->insert($receiver_name,$payment_type,$amount,$created_by); break;
            case 'modify' : $data = $this->modify($transaction_id,$receiver_name,$payment_type,$amount,$created_by); break;
            case 'delete' : $data = $this->delete($transaction_id); break;
            default: $data = $this->getOnHoldInfo($transaction_id); break;
        }
        
        echo json_encode($data);
    }
    
    private function insert($receiver_name,$payment_type,$amount,$created_by)
    {
        $array_container = array(
                                    'Receiver Name' => $receiver_name,
                                    'Payment Type' => $payment_type,
                                    'Amount' => $amount
                                 );
        
        $response = $this->checkFields($array_container);
        
        if($response['result']=='success')
        {
            $response = $this->OnholdModel->insert($receiver_name,$payment_type,$amount,$created_by);
            if($response==1)
            {
                $data['result'] = 'success';
                $data['message'] = 'Record added successfully.';
            }
            else
            {
                $data['result'] = 'fail';
                $data['message'] = 'Unable to save the record.';
            }
        }
        else
        {
            $data['result'] = $response['result'];
            $data['message'] = $response['message'];
        }
        
        return $data;
    }
    
    private function modify($transaction_id,$receiver_name,$payment_type,$amount,$created_by)
    {
        $array_container = array(
                                    'Receiver Name' => $receiver_name,
                                    'Payment Type' => $payment_type,
                                    'Amount' => $amount,
                                    'Created By' => $created_by
                                 );
        
        $response = $this->checkFields($array_container);
        
        if($response['result']=='success')
        {
            $response = $this->OnholdModel->update($transaction_id,$receiver_name,$payment_type,$amount,$created_by);
            if($response==1)
            {
                $data['result'] = 'success';
                $data['message'] = 'Record updated successfully.';
            }
            else
            {
                $data['result'] = 'fail';
                $data['message'] = 'Record is on update.';
            }
        }
        else
        {
            $data['result'] = $response['result'];
            $data['message'] = $response['message'];
        }
        
        return $data;
    }
    
    private function getOnHoldInfo($transaction_id)
    {
        $response = $this->OnholdModel->getOnHoldDetails($transaction_id);
        
        if($response->num_rows()>0)
        {
            $row = $response->row();
            $data = array(
                            'receiver_id' => $row->receiver_id,
                            'payment_type' => $row->payment_type,
                            'amount' => $row->amount,
                            'created_by' => $row->created_by
                          );
        }
        else
        {
            $data = array(
                            'receiver_id' => '',
                            'payment_type' => '',
                            'amount' => '',
                            'created_by' => ''
                          );
        }
        
        return $data;
    }
    
    private function delete($transaction_id)
    {
        $array_container = array(
                                    'Identifier' => $transaction_id
                                 );
        
        $response = $this->checkFields($array_container);
        
        if($response['result']=='success')
        {
            $response = $this->OnholdModel->remove($transaction_id);
            if($response==1)
            {
                $data['result'] = 'success';
                $data['message'] = 'Record deleted successfully.';
            }
            else
            {
                $data['result'] = 'fail';
                $data['message'] = 'Unable to remove the record.';
            }
        }
        else
        {
            $data['result'] = $response['result'];
            $data['message'] = $response['message'];
        }
        
        return $data;
    }
}


?>