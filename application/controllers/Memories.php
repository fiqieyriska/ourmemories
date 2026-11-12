
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Memories extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper(array('url', 'form'));
        $this->load->model('Memory_model');
    }

    /**
     * Halaman daftar kenangan.
     */
    public function index()
    {
        $user_id = (int) $this->session->userdata('user_id');

        if (!$user_id) {
            redirect('login');
            return;
        }

        $space = $this->Memory_model->get_user_space($user_id);

        if (!$space) {
            show_error('Ruang kenangan tidak ditemukan.', 403);
            return;
        }

        $data['title'] = 'Memory Journal | Our Memories';
        $data['space'] = $space;
        $data['memories'] = $this->Memory_model
            ->get_memories($space->id);

        $this->load->view('layouts/header', $data);
        $this->load->view('layouts/navbar');
        $this->load->view('memories/index', $data);
        $this->load->view('layouts/footer');
    }

    /**
     * Form tambah kenangan.
     */
    public function create()
    {
        $user_id = (int) $this->session->userdata('user_id');

        if (!$user_id) {
            redirect('login');
            return;
        }

        $space = $this->Memory_model->get_user_space($user_id);

        if (!$space) {
            show_error('Ruang kenangan tidak ditemukan.', 403);
            return;
        }

        $data = array(
            'title' => 'Tambah Kenangan | Our Memories',
            'space' => $space,
            'error' => ''
        );

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules(
                'title',
                'Judul kenangan',
                'required|trim|max_length[150]'
            );

            $this->form_validation->set_rules(
                'memory_date',
                'Tanggal kenangan',
                'required|callback_valid_memory_date'
            );

            $this->form_validation->set_rules(
                'story',
                'Cerita',
                'required|trim'
            );

            if ($this->form_validation->run()) {
                $memory_data = array(
                    'space_id'   => (int) $space->id,
                    'created_by' => $user_id,
                    'title'      => trim(
                        $this->input->post('title', TRUE)
                    ),
                    'story'      => trim(
                        $this->input->post('story', TRUE)
                    ),
                    'memory_date' => $this->input->post(
                        'memory_date',
                        TRUE
                    )
                );

                $memory_id = $this->Memory_model
                    ->create_memory($memory_data);

                if ($memory_id) {
                    $this->session->set_flashdata(
                        'success',
                        'Kenangan berhasil disimpan.'
                    );

                    redirect('memories');
                    return;
                }

                $data['error'] =
                    'Kenangan gagal disimpan. Silakan coba kembali.';
            }
        }

        $this->load->view('layouts/header', $data);
        $this->load->view('layouts/navbar');
        $this->load->view('memories/create', $data);
        $this->load->view('layouts/footer');
    }

    /**
     * Validasi tanggal format YYYY-MM-DD.
     */
    public function valid_memory_date($date)
    {
        $parsed_date = DateTime::createFromFormat('!Y-m-d', $date);

        if (
            !$parsed_date ||
            $parsed_date->format('Y-m-d') !== $date
        ) {
            $this->form_validation->set_message(
                'valid_memory_date',
                'Tanggal kenangan tidak valid.'
            );

            return FALSE;
        }

        return TRUE;
    }
        
    public function delete($id = 0)
    {
        if (!$this->session->userdata('user_id')) {
            redirect('login');
            return;
        }

        // Hanya izinkan permintaan POST
        if ($this->input->method(TRUE) !== 'POST') {
            show_error('Metode permintaan tidak diizinkan.', 405);
            return;
        }

        $user_id = $this->session->userdata('user_id');
        $space = $this->Memory_model->get_user_space($user_id);

        if (!$space) {
            show_error('Memory space tidak ditemukan.', 404);
            return;
        }

        $memory = $this->Memory_model->get_memory($id, $space->id);

        if (!$memory) {
            show_404();
            return;
        }

        if ($this->Memory_model->delete_memory($id, $space->id)) {
            $this->session->set_flashdata(
                'success',
                'Kenangan berhasil dihapus.'
            );
        } else {
            $this->session->set_flashdata(
                'error',
                'Kenangan gagal dihapus.'
            );
        }

        redirect('memories');
    }

    public function detail($id = 0)
    {
        $user_id = (int) $this->session->userdata('user_id');

        if (!$user_id) {
            redirect('login');
            return;
        }

        $space = $this->Memory_model->get_user_space($user_id);

        if (!$space) {
            show_error('Ruang kenangan tidak ditemukan.', 403);
            return;
        }

        $memory = $this->Memory_model->get_memory(
            (int) $id,
            (int) $space->id
        );

        if (!$memory) {
            show_404();
            return;
        }

        $data['title'] = $memory->title . ' | Our Memories';
        $data['space'] = $space;
        $data['memory'] = $memory;

        $this->load->view('layouts/header', $data);
        $this->load->view('layouts/navbar');
        $this->load->view('memories/detail', $data);
        $this->load->view('layouts/footer');
    }

}
