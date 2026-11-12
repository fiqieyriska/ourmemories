
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Love_letters extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Love_letters_model');
        $this->load->model('Memory_model');

        $this->load->library('session');
        $this->load->library('form_validation');

        $this->load->helper(array('url', 'form', 'text'));
    }

    /**
     * Memastikan pengguna sudah login dan memiliki memory space.
     */
    private function get_current_space()
    {
        if (!$this->session->userdata('user_id')) {
            redirect('login');
            return FALSE;
        }

        $user_id = $this->session->userdata('user_id');
        $space = $this->Memory_model->get_user_space($user_id);

        if (!$space) {
            show_error('Memory space tidak ditemukan.', 404);
            return FALSE;
        }

        return $space;
    }

    /**
     * Halaman daftar surat.
     */
    public function index()
    {
        $space = $this->get_current_space();

        if (!$space) {
            return;
        }

        $data['title'] = 'Love Letters';
        $data['space'] = $space;
        $data['letters'] = $this->Love_letters_model
            ->get_letters($space->id);

        $this->load->view('layouts/header', $data);
        $this->load->view('layouts/navbar', $data);
        $this->load->view('love_letters/index', $data);
        $this->load->view('layouts/footer', $data);
    }

    /**
     * Menyimpan surat baru.
     */
    public function create()
    {
        $space = $this->get_current_space();

        if (!$space) {
            return;
        }

        if ($this->input->method(TRUE) !== 'POST') {
            redirect('love-letters');
            return;
        }

        $this->form_validation->set_rules(
            'title',
            'Judul surat',
            'required|trim|max_length[150]'
        );

        $this->form_validation->set_rules(
            'content',
            'Isi surat',
            'required|trim'
        );

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata(
                'error',
                strip_tags(validation_errors())
            );

            redirect('love-letters');
            return;
        }

        $now = date('Y-m-d H:i:s');

        $data = array(
            'space_id' => $space->id,
            'author_id' => $this->session->userdata('user_id'),
            'title' => trim($this->input->post('title', TRUE)),
            'content' => trim($this->input->post('content', FALSE)),
            'status' => 'published',
            'published_at' => $now,
            'created_at' => $now,
            'updated_at' => $now
        );

        if ($this->Love_letters_model->create_letter($data)) {
            $this->session->set_flashdata(
                'success',
                'Surat cinta berhasil disimpan.'
            );
        } else {
            log_message('error', 'Gagal menyimpan surat cinta.');

            $this->session->set_flashdata(
                'error',
                'Surat gagal disimpan. Silakan coba kembali.'
            );
        }

        redirect('love-letters');
    }

    /**
     * Membaca detail surat.
     */
    public function detail($id = 0)
    {
        $space = $this->get_current_space();

        if (!$space) {
            return;
        }

        $id = (int) $id;

        if ($id < 1) {
            show_404();
            return;
        }

        $letter = $this->Love_letters_model
            ->get_letter($id, $space->id);

        if (!$letter) {
            show_404();
            return;
        }

        $data['title'] = $letter->title;
        $data['letter'] = $letter;

        $this->load->view('layouts/header', $data);
        $this->load->view('layouts/navbar', $data);
        $this->load->view('love_letters/detail', $data);
        $this->load->view('layouts/footer', $data);
    }

    /**
     * Mengedit surat.
     */
    public function edit($id = 0)
    {
        $space = $this->get_current_space();

        if (!$space) {
            return;
        }

        $id = (int) $id;

        if ($id < 1) {
            show_404();
            return;
        }

        $letter = $this->Love_letters_model
            ->get_letter($id, $space->id);

        if (!$letter) {
            show_404();
            return;
        }

        if ($this->input->method(TRUE) !== 'POST') {
            $this->session->set_flashdata(
                'error',
                'Gunakan formulir untuk mengedit surat.'
            );

            redirect('love-letters');
            return;
        }

        $this->form_validation->set_rules(
            'title',
            'Judul surat',
            'required|trim|max_length[150]'
        );

        $this->form_validation->set_rules(
            'content',
            'Isi surat',
            'required|trim'
        );

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata(
                'error',
                strip_tags(validation_errors())
            );

            redirect('love-letters/detail/' . $id);
            return;
        }

        $data = array(
            'title' => trim($this->input->post('title', TRUE)),
            'content' => trim($this->input->post('content', FALSE)),
            'updated_at' => date('Y-m-d H:i:s')
        );

        if ($this->Love_letters_model->update_letter(
            $id,
            $space->id,
            $data
        )) {
            $this->session->set_flashdata(
                'success',
                'Surat berhasil diperbarui.'
            );
        } else {
            $this->session->set_flashdata(
                'error',
                'Surat gagal diperbarui.'
            );
        }

        redirect('love-letters/detail/' . $id);
    }

    /**
     * Menghapus surat.
     */
    public function delete($id = 0)
    {
        $space = $this->get_current_space();

        if (!$space) {
            return;
        }

        if ($this->input->method(TRUE) !== 'POST') {
            show_error('Metode permintaan tidak diizinkan.', 405);
            return;
        }

        $id = (int) $id;

        if ($id < 1) {
            show_404();
            return;
        }

        $letter = $this->Love_letters_model
            ->get_letter($id, $space->id);

        if (!$letter) {
            show_404();
            return;
        }

        if ($this->Love_letters_model->delete_letter(
            $id,
            $space->id
        )) {
            $this->session->set_flashdata(
                'success',
                'Surat berhasil dihapus.'
            );
        } else {
            $this->session->set_flashdata(
                'error',
                'Surat gagal dihapus.'
            );
        }

        redirect('love-letters');
    }
}
