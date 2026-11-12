
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
    }

    public function index()
    {
        if (!$this->session->userdata('user_id')) { redirect('login');
            return;
        }

        $data['title'] = 'Our Memories';

        $this->load->view('layouts/header', $data);
        $this->load->view('layouts/navbar');
        $this->load->view('home/index');
        $this->load->view('layouts/footer');
    }
}
