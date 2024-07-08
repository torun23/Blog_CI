<?php
class Users extends CI_Controller{
//signup user
    public function register(){
$data['title'] = 'Sign Up';
$this->form_validation->set_rules('name', 'Name', 'required');
$this->form_validation->set_rules('username', 'Username', 'required|callback_check_username_exists');
$this->form_validation->set_rules('email', 'Email', 'required|callback_check_email_exists');
$this->form_validation->set_rules('password', 'Password', 'required');
$this->form_validation->set_rules('password2', 'Confirm Passsword', 'matches[password]');
if($this->form_validation->run() === FALSE){ 
    $this->load->view('templates/header');
    $this->load->view('users/register', $data);
    $this->load->view('templates/footer');
}
else{
    $enc_password = md5($this->input->post('password'));
    $this->user_model->register($enc_password);
    // $this->user_model->register();


    $this->session->set_flashdata('user_registered','You are now registered and can log in');
    redirect('posts');
}

}
//login user

public function login (){
    $data['title'] = 'Sign In';
    $this->form_validation->set_rules('username', 'Username', 'required');
    $this->form_validation->set_rules('password', 'Password', 'required');
    if($this->form_validation->run() === FALSE){ 
        $this->load->view('templates/header');
        $this->load->view('users/login', $data);
        $this->load->view('templates/footer');
    }
    else{
//get user_name
$username = $this->input->post('username');
//get and encrypt the password
$password = md5($this->input->post('password'));

$user_id = $this->user_model->login($username, $password);

if($user_id){
$user_data = array(
'user_id' => $user_id,
'username'=> $username,
'logged_in'=> true
);

    $this->session->set_flashdata('user_loggedin','You are now logged in');
redirect('posts');
}
else{
    $this->session->set_flashdata('login_failed','Login is invalid');
    redirect('users/login');
}
    }
    
    }

// check if username exists
function check_username_exists($username){
    $this->form_validation->set_message('check_username_exists','The username is taken.Please choose a different one');
 if($this->user_model->check_username_exists($username)){
    return true;
 }
else{
    return false; 
}
}

 function check_email_exists($email){
    $this->form_validation->set_message('check_email_exists','The email is taken.Please choose a different one');
 if($this->user_model->check_email_exists($email)){
    return true;
 }
else{
    return false; 
}
}

}


?>
