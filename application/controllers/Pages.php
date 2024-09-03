
<?php

class Pages extends CI_Controller
{
    
    public function view($page = 'home')
    {
        // Check if the requested page view exists
        if (!file_exists(APPPATH . 'views/pages/' . $page . '.php')) {
            show_404();
        }
        //suppose http://localhost/cg_app/pages/view/about is the url u specified
        // here 'about' is passed as the parameter to the $page which is set to home page as default
        // Prepare data to be passed to the view
        $data['title'] = ucfirst($page); // Capitalize the first letter of the page name
        // thus $data['title'] becomes About
        
        // Load views using CodeIgniter's view loader
        $this->load->view('templates/header', $data);
        $this->load->view('pages/' . $page, $data);
        $this->load->view('templates/footer');
    }
}
