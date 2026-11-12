
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gallery extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Gallery_model');
        $this->load->model('Memory_model');

        $this->load->library('session');
        $this->load->library('form_validation');

        $this->load->helper(array('url', 'form'));
    }

    /**
     * Halaman utama Our Gallery.
     */
    public function index()
    {
        if (!$this->session->userdata('user_id')) {
            redirect('login');
            return;
        }

        $user_id = $this->session->userdata('user_id');
        $space = $this->Memory_model->get_user_space($user_id);

        if (!$space) {
            show_error('Memory space tidak ditemukan.', 404);
            return;
        }

        $data['title'] = 'Our Gallery';
        $data['space'] = $space;
        $data['photos'] = $this->Gallery_model->get_photos($space->id);

        $this->load->view('layouts/header', $data);
        $this->load->view('layouts/navbar', $data);
        $this->load->view('gallery/index', $data);
        $this->load->view('layouts/footer', $data);
    }

    /**
     * Upload foto ke Our Gallery.
     */
    public function create()
    {
        if (!$this->session->userdata('user_id')) {
            redirect('login');
            return;
        }

        $user_id = $this->session->userdata('user_id');
        $space = $this->Memory_model->get_user_space($user_id);

        if (!$space) {
            show_error('Memory space tidak ditemukan.', 404);
            return;
        }

        if ($this->input->method(TRUE) !== 'POST') {
            $this->session->set_flashdata(
                'error',
                'Gunakan formulir untuk mengunggah foto.'
            );
            redirect('gallery');
            return;
        }

        // Validasi formulir.
        $this->form_validation->set_rules(
            'title',
            'Judul foto',
            'required|trim|max_length[150]'
        );

        $this->form_validation->set_rules(
            'photo_date',
            'Tanggal foto',
            'required|callback_valid_photo_date'
        );

        $this->form_validation->set_rules(
            'caption',
            'Caption',
            'trim|max_length[5000]'
        );

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata(
                'error',
                strip_tags(validation_errors())
            );
            redirect('gallery');
            return;
        }

        // Pastikan foto sudah dipilih dan berhasil diterima PHP.
        if (
            !isset($_FILES['photo']) ||
            empty($_FILES['photo']['name']) ||
            !isset($_FILES['photo']['error']) ||
            $_FILES['photo']['error'] !== UPLOAD_ERR_OK
        ) {
            $upload_error = isset($_FILES['photo']['error'])
                ? $_FILES['photo']['error']
                : null;

            $error_message = 'Pilih foto yang valid untuk diunggah.';

            if ($upload_error !== null) {
                switch ($upload_error) {
                    case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                        $error_message = 'Ukuran foto melebihi batas upload server.';
                        break;

                    case UPLOAD_ERR_PARTIAL:
                        $error_message = 'Foto hanya terunggah sebagian. Silakan coba lagi.';
                        break;

                    case UPLOAD_ERR_NO_FILE:
                        $error_message = 'Silakan pilih foto terlebih dahulu.';
                        break;

                    case UPLOAD_ERR_NO_TMP_DIR:
                        $error_message = 'Folder sementara upload PHP tidak tersedia.';
                        break;

                    case UPLOAD_ERR_CANT_WRITE:
                        $error_message = 'Server gagal menulis file foto.';
                        break;

                    case UPLOAD_ERR_EXTENSION:
                        $error_message = 'Upload foto dihentikan oleh konfigurasi PHP.';
                        break;
                }
            }

            $this->session->set_flashdata('error', $error_message);
            redirect('gallery');
            return;
        }

        // Maksimal ukuran foto 5 MB.
        $max_size = 5 * 1024 * 1024;

        if ($_FILES['photo']['size'] > $max_size) {
            $this->session->set_flashdata(
                'error',
                'Ukuran foto maksimal 5 MB.'
            );
            redirect('gallery');
            return;
        }

        // Periksa MIME file berdasarkan isi sebenarnya.
        if (!function_exists('finfo_open')) {
            log_message('error', 'Ekstensi PHP fileinfo tidak tersedia.');

            $this->session->set_flashdata(
                'error',
                'Server belum mendukung pemeriksaan format foto.'
            );
            redirect('gallery');
            return;
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        if ($finfo === false) {
            $this->session->set_flashdata(
                'error',
                'Foto gagal diperiksa. Silakan coba kembali.'
            );
            redirect('gallery');
            return;
        }

        $mime = finfo_file($finfo, $_FILES['photo']['tmp_name']);
        finfo_close($finfo);

        $allowed_types = array(
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp'
        );

        if (!isset($allowed_types[$mime])) {
            $this->session->set_flashdata(
                'error',
                'Format foto harus JPG, PNG, atau WEBP.'
            );
            redirect('gallery');
            return;
        }

        // Siapkan folder penyimpanan foto.
        $upload_dir = FCPATH . 'assets/uploads/gallery/';

        if (!is_dir($upload_dir)) {
            if (!@mkdir($upload_dir, 0755, true) && !is_dir($upload_dir)) {
                log_message(
                    'error',
                    'Gagal membuat folder galeri: ' . $upload_dir
                );

                $this->session->set_flashdata(
                    'error',
                    'Folder upload galeri tidak dapat dibuat.'
                );
                redirect('gallery');
                return;
            }
        }

        if (!is_writable($upload_dir)) {
            log_message(
                'error',
                'Folder galeri tidak dapat ditulis: ' . $upload_dir
            );

            $this->session->set_flashdata(
                'error',
                'Folder upload galeri tidak dapat ditulis oleh server.'
            );
            redirect('gallery');
            return;
        }

        // Buat nama file acak.
        try {
            $filename = bin2hex(random_bytes(16))
                . '.' . $allowed_types[$mime];
        } catch (Exception $e) {
            log_message(
                'error',
                'Gagal membuat nama file galeri: ' . $e->getMessage()
            );

            $this->session->set_flashdata(
                'error',
                'Foto gagal diproses. Silakan coba kembali.'
            );
            redirect('gallery');
            return;
        }

        // Pindahkan foto ke folder galeri.
        $temporary_file = $_FILES['photo']['tmp_name'];
        $destination = $upload_dir . $filename;

        if (!is_uploaded_file($temporary_file) ||
            !move_uploaded_file($temporary_file, $destination)) {

            log_message(
                'error',
                'Gagal memindahkan foto ke: ' . $destination
            );

            $this->session->set_flashdata(
                'error',
                'Foto gagal disimpan ke folder galeri.'
            );
            redirect('gallery');
            return;
        }

        // Siapkan data untuk database.
        $photo_data = array(
            'space_id'       => $space->id,
            'uploaded_by'    => $user_id,
            'title'          => trim($this->input->post('title', TRUE)),
            'caption'        => trim((string) $this->input->post('caption', TRUE)),
            'photo_date'     => $this->input->post('photo_date', TRUE),
            'photo_filename' => $filename
        );

        // Simpan data foto.
        if ($this->Gallery_model->create_photo($photo_data)) {
            $this->session->set_flashdata(
                'success',
                'Foto berhasil ditambahkan ke Our Gallery.'
            );
        } else {
            // Hapus file jika penyimpanan database gagal.
            if (is_file($destination)) {
                @unlink($destination);
            }

            log_message(
                'error',
                'Gagal menyimpan data foto ke database.'
            );

            $this->session->set_flashdata(
                'error',
                'Data foto gagal disimpan. Silakan coba kembali.'
            );
        }

        redirect('gallery');
    }

    /**
     * Menghapus foto dari database dan folder upload.
     */
    public function delete($id = 0)
    {
        if (!$this->session->userdata('user_id')) {
            redirect('login');
            return;
        }

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

        $id = (int) $id;

        if ($id < 1) {
            show_404();
            return;
        }

        // Pastikan foto berada di memory space milik pengguna.
        $photo = $this->Gallery_model->get_photo($id, $space->id);

        if (!$photo) {
            show_404();
            return;
        }

        $file_path = FCPATH . 'assets/uploads/gallery/'
            . basename($photo->photo_filename);

        if ($this->Gallery_model->delete_photo($id, $space->id)) {
            // Penghapusan data tetap berhasil jika file sudah tidak ada.
            if (is_file($file_path) && !@unlink($file_path)) {
                log_message(
                    'error',
                    'Data foto terhapus, tetapi file gagal dihapus: '
                    . basename($photo->photo_filename)
                );
            }

            $this->session->set_flashdata(
                'success',
                'Foto berhasil dihapus dari Our Gallery.'
            );
        } else {
            $this->session->set_flashdata(
                'error',
                'Foto gagal dihapus. Silakan coba kembali.'
            );
        }

        redirect('gallery');
    }

    /**
     * Validasi tanggal dengan format YYYY-MM-DD.
     */
    public function valid_photo_date($date)
    {
        $parsed = DateTime::createFromFormat('!Y-m-d', $date);

        if (!$parsed || $parsed->format('Y-m-d') !== $date) {
            $this->form_validation->set_message(
                'valid_photo_date',
                'Tanggal foto tidak valid.'
            );

            return FALSE;
        }

        return TRUE;
    }
}
