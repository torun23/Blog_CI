<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Forms extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Forms');
    }

    public function delete($id)
    {
        // Attempt to delete the form
        $deleted = $this->Forms->deleteForm($id);

        // Check if deletion was successful
        if ($deleted) {
            echo "success";
        } else {
            echo "error";
        }
    }
}
