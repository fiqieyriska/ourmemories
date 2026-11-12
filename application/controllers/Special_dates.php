
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Special_dates extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Special_dates_model');
        $this->load->model('Memory_model');

        $this->load->library('session');
        $this->load->library('form_validation');

        $this->load->helper(array('url', 'form'));
    }

    /**
     * Memastikan pengguna login dan memiliki memory space.
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
     * Halaman daftar tanggal penting.
     */
    public function index()
    {
        $space = $this->get_current_space();

        if (!$space) {
            return;
        }

        $dates = $this->Special_dates_model->get_dates($space->id);

        $today = new DateTime('today');

        foreach ($dates as $date) {
            $event_date = new DateTime($date->event_date);

            if ((int) $date->is_recurring === 1) {
                // Cari kejadian berikutnya berdasarkan bulan dan tanggal.
                $month_day = $event_date->format('m-d');
                $next_date = DateTime::createFromFormat(
                    '!Y-m-d',
                    $today->format('Y') . '-' . $month_day
                );

                // Tangani tanggal 29 Februari pada tahun non-kabisat.
                if (!$next_date || $next_date->format('m-d') !== $month_day) {
                    $next_date = new DateTime(
                        $today->format('Y') . '-03-01'
                    );
                }

                if ($next_date < $today) {
                    $next_year = (int) $today->format('Y') + 1;

                    $next_date = DateTime::createFromFormat(
                        '!Y-m-d',
                        $next_year . '-' . $month_day
                    );

                    if (!$next_date || $next_date->format('m-d') !== $month_day) {
                        $next_date = new DateTime($next_year . '-03-01');
                    }
                }

                $date->next_event_date = $next_date->format('Y-m-d');
            } else {
                $date->next_event_date = $date->event_date;
            }

            $next_event = new DateTime($date->next_event_date);
            $date->days_remaining = (int) $today->diff($next_event)->format('%r%a');
        }

        // Tampilkan acara yang paling dekat terlebih dahulu.
        usort($dates, function ($a, $b) {
            return strcmp($a->next_event_date, $b->next_event_date);
        });

        $data['title'] = 'Special Dates';
        $data['space'] = $space;
        $data['special_dates'] = $dates;

        $this->load->view('layouts/header', $data);
        $this->load->view('layouts/navbar', $data);
        $this->load->view('special_dates/index', $data);
        $this->load->view('layouts/footer', $data);
    }

    /**
     * Menambahkan tanggal penting.
     */
    public function create()
    {
        $space = $this->get_current_space();

        if (!$space) {
            return;
        }

        if ($this->input->method(TRUE) !== 'POST') {
            redirect('special-dates');
            return;
        }

        $this->form_validation->set_rules(
            'title',
            'Nama acara',
            'required|trim|max_length[150]'
        );

        $this->form_validation->set_rules(
            'event_date',
            'Tanggal acara',
            'required|callback_valid_event_date'
        );

        $this->form_validation->set_rules(
            'category',
            'Kategori',
            'required|in_list[Anniversary,Birthday,First Date,Other]'
        );

        $this->form_validation->set_rules(
            'description',
            'Catatan',
            'trim|max_length[5000]'
        );

        $this->form_validation->set_rules(
            'is_recurring',
            'Pengulangan tahunan',
            'required|in_list[0,1]'
        );

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata(
                'error',
                strip_tags(validation_errors())
            );

            redirect('special-dates');
            return;
        }

        $now = date('Y-m-d H:i:s');

        $data = array(
            'space_id' => $space->id,
            'created_by' => $this->session->userdata('user_id'),
            'title' => trim($this->input->post('title', TRUE)),
            'event_date' => $this->input->post('event_date', TRUE),
            'category' => $this->input->post('category', TRUE),
            'description' => trim(
                (string) $this->input->post('description', TRUE)
            ),
            'is_recurring' => (int) $this->input->post('is_recurring'),
            'created_at' => $now,
            'updated_at' => $now
        );

        if ($this->Special_dates_model->create_date($data)) {
            $this->session->set_flashdata(
                'success',
                'Tanggal penting berhasil ditambahkan.'
            );
        } else {
            log_message('error', 'Gagal menyimpan special date.');

            $this->session->set_flashdata(
                'error',
                'Tanggal penting gagal disimpan.'
            );
        }

        redirect('special-dates');
    }

    /**
     * Memperbarui tanggal penting.
     */
    public function edit($id = 0)
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
        $event = $this->Special_dates_model->get_date($id, $space->id);

        if (!$event) {
            show_404();
            return;
        }

        $this->form_validation->set_rules(
            'title',
            'Nama acara',
            'required|trim|max_length[150]'
        );

        $this->form_validation->set_rules(
            'event_date',
            'Tanggal acara',
            'required|callback_valid_event_date'
        );

        $this->form_validation->set_rules(
            'category',
            'Kategori',
            'required|in_list[Anniversary,Birthday,First Date,Other]'
        );

        $this->form_validation->set_rules(
            'description',
            'Catatan',
            'trim|max_length[5000]'
        );

        $this->form_validation->set_rules(
            'is_recurring',
            'Pengulangan tahunan',
            'required|in_list[0,1]'
        );

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata(
                'error',
                strip_tags(validation_errors())
            );

            redirect('special-dates');
            return;
        }

        $data = array(
            'title' => trim($this->input->post('title', TRUE)),
            'event_date' => $this->input->post('event_date', TRUE),
            'category' => $this->input->post('category', TRUE),
            'description' => trim(
                (string) $this->input->post('description', TRUE)
            ),
            'is_recurring' => (int) $this->input->post('is_recurring'),
            'updated_at' => date('Y-m-d H:i:s')
        );

        if ($this->Special_dates_model->update_date(
            $id,
            $space->id,
            $data
        )) {
            $this->session->set_flashdata(
                'success',
                'Tanggal penting berhasil diperbarui.'
            );
        } else {
            $this->session->set_flashdata(
                'error',
                'Tanggal penting gagal diperbarui.'
            );
        }

        redirect('special-dates');
    }

    /**
     * Menghapus tanggal penting.
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

        $event = $this->Special_dates_model->get_date($id, $space->id);

        if (!$event) {
            show_404();
            return;
        }

        if ($this->Special_dates_model->delete_date($id, $space->id)) {
            $this->session->set_flashdata(
                'success',
                'Tanggal penting berhasil dihapus.'
            );
        } else {
            $this->session->set_flashdata(
                'error',
                'Tanggal penting gagal dihapus.'
            );
        }

        redirect('special-dates');
    }

    /**
     * Validasi tanggal dengan format YYYY-MM-DD.
     */
    public function valid_event_date($date)
    {
        $parsed = DateTime::createFromFormat('!Y-m-d', $date);

        if (!$parsed || $parsed->format('Y-m-d') !== $date) {
            $this->form_validation->set_message(
                'valid_event_date',
                'Tanggal acara tidak valid.'
            );

            return FALSE;
        }

        return TRUE;
    }
}
