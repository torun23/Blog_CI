<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Graph_c3js extends CI_Controller
{

    public function chart()
    {
        $this->load->view('graph.php');
    }

    public function chart_new()
    {
        $this->load->view('new_c3js.php');
    }
    
}