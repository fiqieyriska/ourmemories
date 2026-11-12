
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('User_model');
        $this->load->library('form_validation');
    }

    public function register()
    {
        if ($this->session->userdata('user_id')) {
            redirect('dashboard');
        }

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules(
                'name', 'Nama',
                'required|trim|min_length[2]|max_length[100]'
            );

            $this->form_validation->set_rules(
                'email', 'Email',
                'required|trim|valid_email|max_length[190]'
            );

            $this->form_validation->set_rules(
                'password', 'Password',
                'required|min_length[8]'
            );

            $this->form_validation->set_rules(
                'password_confirm', 'Konfirmasi Password',
                'required|matches[password]'
            );

            if ($this->form_validation->run()) {
                $name = trim($this->input->post('name', TRUE));
                $email = strtolower(trim(
                    $this->input->post('email', TRUE)
                ));
                $password = $this->input->post('password');

                if ($this->User_model->find_by_email($email)) {
                    $data['error'] = 'Email sudah terdaftar.';
                } else {
                    $this->db->trans_begin();

                    $user_id = $this->User_model->create_user(
                        $name, $email, $password
                    );

                    if ($user_id) {
                        $this->db->insert('memory_spaces', array(
                            'name'       => 'Our Memories',
                            'created_by' => $user_id
                        ));

                        $space_id = $this->db->insert_id();

                        if ($space_id) {
                            $this->db->insert('memory_members', array(
                                'space_id' => $space_id,
                                'user_id'  => $user_id,
                                'role'     => 'owner'
                            ));
                        }
                    }

                    if (
                        $user_id &&
                        !empty($space_id) &&
                        $this->db->trans_status() !== FALSE
                    ) {
                        $this->db->trans_commit();

                        $this->session->sess_regenerate(TRUE);
                        $this->session->set_userdata(array(
                            'user_id'   => $user_id,
                            'user_name' => $name
                        ));

                        redirect('home');
                        return;
                    }

                    $this->db->trans_rollback();
                    $data['error'] =
                        'Registrasi gagal. Silakan coba kembali.';
                }
            }
        }

        $this->load->view('auth/register', isset($data) ? $data : array());
    }

    public function login()
    {
        if ($this->session->userdata('user_id')) {
            redirect('dashboard');
        }

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules(
                'email', 'Email',
                'required|trim|valid_email'
            );

            $this->form_validation->set_rules(
                'password', 'Password',
                'required'
            );

            if ($this->form_validation->run()) {
                $email = strtolower(trim(
                    $this->input->post('email', TRUE)
                ));
                $password = $this->input->post('password');
                $user = $this->User_model->find_by_email($email);

                if (
                    $user &&
                    (int) $user->is_active === 1 &&
                    password_verify($password, $user->password_hash)
                ) {
                    $this->session->sess_regenerate(TRUE);
                    $this->session->set_userdata(array(
                        'user_id'   => $user->id,
                        'user_name' => $user->name
                    ));

                    redirect('index.php/home');
                    return;
                }

                $data['error'] = 'Email atau password tidak sesuai.';
            }
        }

        $this->load->view('auth/login', isset($data) ? $data : array());
    }

    public function logout()
    {
        if ($this->input->method() !== 'post') {
            show_error('Method tidak diizinkan.', 405);
        }

        $this->session->sess_destroy();
        redirect('login');
    }
}
