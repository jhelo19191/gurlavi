<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{
  protected $_admin_count = 0;
  public function __construct()
  {
    parent::__construct();
    $this->load->model('AuthModel');
    $this->_admin_count = $this->AuthModel->checkAdministrator();
  }

  public function login()
  {
    $username = $this->input->post('username');
    $password = $this->input->post('password');

    $data['num_rows'] = $this->_admin_count;

    $array = array('Password' => $password, 'Username' => $username);

    $response = $this->checkLoginFields($array);

    if($response['result']=='success')
    {
        $checkUsername = $this->AuthModel->checkUsername($username);
        if($checkUsername==1)
        {
            $account_information = $this->AuthModel->getAccountInfo($username);
            $decode_password = $this->encrypt->decode($account_information->password);

            if($decode_password==$password)
            {
                $this->AuthModel->updateStatus($account_information->account_id,1);
                
                $set_session_data = array(
                                            'account_id' => $account_information->account_id,
                                            'account_name' => $account_information->name,
                                            'login_status' => $account_information->account_status,
                                            'user_level' => $account_information->user_level,
                                            'email' => $account_information->email,
                                            'position' => $account_information->position,
                                            'logged_in' => true
                                          );

                $this->session->set_userdata($set_session_data);

                redirect('pages/index');
            }
            else
            {
                $data['result'] = "fail";
                $data['message'] = "Account is not registered.";
            }
        }
        else
        {
            $data['result'] = "fail";
            $data['message'] = "Username is not registered.";
        }
    }
    else
    {
        $data['result'] = "fail";
        $data['message'] = $response['message'];
    }

    $this->load->view('templates/login',$data);
  }

  public function register()
  {
      $name = $this->input->post('account_name');
      $username = $this->input->post('username');
      $password = $this->input->post('password');
      $email = $this->input->post('email');
      $user_level = $this->input->post('user_level');
      $c_password = $this->input->post('c_password');

      $container = array(
                        'Confirm Password' => $c_password,
                        'Password' => $password,
                        'Username' => $username,
                        'User Level' => $user_level,
                        'Email' => $email,
                        'Name' => $name
                      );

      $message = $this->checkLoginFields($container);

      if($message['result']=='success')
      {
          $checkUsername = $this->AuthModel->checkUsername($username);
          if($checkUsername==0)
          {
              if($password==$c_password)
              {
                  $password = $this->encrypt->encode($password);
                  $response = $this->AuthModel->createAccount($name,$username,$password,$user_level,$email);
                  if($response==1) {
                    $data['result'] = 'success';
                    $data['message'] = 'Administrator account was successully created.';
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

      $this->load->view('templates/register',$data);
  }

  public function logout()
  {
    $this->session->sess_destroy();

    redirect();
  }

  private function checkLoginFields($array)
  {
    $data['result'] = 'success';

    foreach($array as $key=>$value)
    {
      if($value==''):
        $data['result'] = 'fail';
        $data['message'] = $key.' is empty.';

        break;
      endif;
    }

    return $data;
  }
}

?>
