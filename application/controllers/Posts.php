<?php

class Posts extends CI_Controller
{

    public function index($offset = 0)
    {
        $config['base_url'] = base_url() . 'posts/index/';
        $config['total_rows'] = $this->db->count_all('posts');
        $config['per_page'] = 3;
        $config['uri_segment'] = 3;
        $config['attributes'] = array('class' => 'pagination-link');

        $this->pagination->initialize($config);

        $data['title'] = 'Latest Posts';

        $data['posts'] = $this->post_model->get_posts(false, $config['per_page'], $offset);
    
        $this->load->view('templates/header');
        $this->load->view('posts/index', $data);
        $this->load->view('templates/footer');
    }
    public function view($slug = null)
    {
        $data['post'] = $this->post_model->get_posts($slug);
        $post_id = $data['post']['id'];
        $data['comments'] = $this->comment_model->get_comments($post_id);
    
        if (empty($data['post'])) {
            show_40f4();
        }
        $data['title'] = $data['post']['title'];
 
        $this->load->view('templates/header');
        $this->load->view('posts/view', $data);
        $this->load->view('templates/footer');
    }
    public function create()
    {
        // check login
        if (!$this->session->userdata('logged_in')) {
            redirect('users/login');
        }

        $data['title'] = 'Create Post';

        $data['categories'] = $this->post_model->get_categories();

        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('body', 'Body', 'required');
    
        if ($this->form_validation->run() === false) {
            $this->load->view('templates/header');
            $this->load->view('posts/create', $data);
            $this->load->view('templates/footer');
        } else {
            $config['upload_path'] = 'assets/images/posts';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size'] = '2048';
            $config['max_width'] = '500';
            $config['max_height'] = '500';

            $this->load->library('upload', $config);
    
            if (!$this->upload->do_upload('postimage')) {
                $errors = array('error' => $this->upload->display_errors());
                $post_image = 'noimage.jpg';
            } else {
                $data = array('upload_data' => $this->upload->data());
                $post_image = $_FILES['postimage']['name'];
            }

    
            $this->post_model->create_post($post_image);

            $this->session->set_flashdata('post_created', 'Your post has been created');
            redirect('posts');
        }
    }
    public function delete($id)
    {
        // check login
        if (!$this->session->userdata('logged_in')) {
            redirect('users/login');
        }
        $this->post_model->delete_post($id);
        $this->session->set_flashdata('post_deleted', 'Your post has been deleted');

        redirect('posts');
    }
    public function edit($slug)
    {
        // check login
        if (!$this->session->userdata('logged_in')) {
            redirect('users/login');
        }
        $data['post'] = $this->post_model->get_posts($slug);
        if ($this->session->userdata('user_id') != $this->post_model->get_posts($slug)['user_id']) {
            redirect('posts');
        }
        $data['categories'] = $this->post_model->get_categories();

        if (empty($data['post'])) {
            show_404();
        }
        $data['title'] = 'Edit Post';

        $this->load->view('templates/header');
        $this->load->view('posts/edit', $data);
        $this->load->view('templates/footer');
    }

    public function update()
    {
        // check login
        if (!$this->session->userdata('logged_in')) {
            redirect('users/login');
        }
        $this->post_model->update_post();
        $this->session->set_flashdata('post_updated', 'Your post has been updated');

        redirect('posts');
    }
}
