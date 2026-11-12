
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invitations extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('User_model');
        $this->load->model('Space_invitation_model');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper('url');
    }


    public function create()
    {
        // Pastikan pengguna sudah login.
        $user_id = (int) $this->session->userdata('user_id');

        if (!$user_id) {
            redirect('login');
            return;
        }

        // Cari ruang kenangan yang dimiliki pengguna.
        $space = $this->db
            ->select('memory_spaces.id, memory_spaces.name')
            ->from('memory_spaces')
            ->join(
                'memory_members',
                'memory_members.space_id = memory_spaces.id'
            )
            ->where('memory_members.user_id', $user_id)
            ->where('memory_members.role', 'owner')
            ->limit(1)
            ->get()
            ->row();

        if (!$space) {
            show_error('Ruang kenangan milik owner tidak ditemukan.', 403);
            return;
        }

        $data = array(
            'space' => $space,
            'error' => '',
            'invitation_url' => ''
        );

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules(
                'email',
                'Email pasangan',
                'required|trim|valid_email|max_length[190]'
            );

            if ($this->form_validation->run()) {
                $email = strtolower(trim(
                    $this->input->post('email', TRUE)
                ));

                // Ambil email pemilik akun.
                $owner = $this->db
                    ->select('email')
                    ->where('id', $user_id)
                    ->get('users')
                    ->row();

                if (!$owner) {
                    $data['error'] = 'Data akun tidak ditemukan.';
                } elseif ($email === strtolower($owner->email)) {
                    $data['error'] =
                        'Email undangan harus berbeda dari email akunmu.';
                } else {
                    // Pastikan email pasangan belum menjadi anggota ruang ini.
                    $existing_member = $this->db
                        ->select('memory_members.id')
                        ->from('memory_members')
                        ->join(
                            'users',
                            'users.id = memory_members.user_id'
                        )
                        ->where('memory_members.space_id', $space->id)
                        ->where('users.email', $email)
                        ->limit(1)
                        ->get()
                        ->row();

                    if ($existing_member) {
                        $data['error'] =
                            'Email tersebut sudah menjadi anggota ruang kenangan ini.';
                    } else {
                        $token = $this->Space_invitation_model
                            ->create_invitation(
                                $space->id,
                                $email,
                                $user_id
                            );

                        if ($token !== FALSE) {
                            $data['invitation_url'] = site_url(
                                'invitations/accept/' . $token
                            );
                        } else {
                            $data['error'] =
                                'Undangan gagal dibuat. Silakan coba kembali.';
                        }
                    }
                }
            }
        }

        $this->load->view('invitations/create', $data);
    }

    
    public function accept($token = '')
    {
        $invitation = $this->Space_invitation_model
            ->find_valid_invitation($token);

        if (!$invitation) {
            show_error(
                'Undangan tidak valid, sudah digunakan, atau telah kedaluwarsa.',
                400
            );
            return;
        }

        $email = strtolower(trim($invitation->invited_email));
        $current_user_id = (int) $this->session->userdata('user_id');

        // Jika pengguna sudah login, gunakan akun tersebut.
        if ($current_user_id) {
            $user = $this->db
                ->select('id, email')
                ->where('id', $current_user_id)
                ->where('is_active', 1)
                ->get('users')
                ->row();

            if (!$user || strtolower($user->email) !== $email) {
                show_error(
                    'Login menggunakan akun dengan email yang diundang.',
                    403
                );
                return;
            }

            $this->join_invited_space($invitation, $user->id);
            return;
        }

        $data = array(
            'invitation' => $invitation,
            'error' => ''
        );

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules(
                'name',
                'Nama',
                'required|trim|min_length[2]|max_length[100]'
            );

            $this->form_validation->set_rules(
                'password',
                'Password',
                'required|min_length[8]'
            );

            $this->form_validation->set_rules(
                'password_confirm',
                'Konfirmasi Password',
                'required|matches[password]'
            );

            if ($this->form_validation->run()) {
                // Email tidak diambil dari input pengguna.
                // Akun harus menggunakan email yang tertera pada undangan.
                $existing_user = $this->User_model
                    ->find_by_email($email);

                if ($existing_user) {
                    $data['error'] =
                        'Email ini sudah terdaftar. Login menggunakan akun tersebut, '
                        . 'kemudian buka kembali tautan undangan.';
                } else {
                    $name = trim($this->input->post('name', TRUE));
                    $password = $this->input->post('password');

                    $this->db->trans_begin();

                    // Kunci undangan untuk mencegah penerimaan bersamaan.
                    $locked_invitation = $this->db
                        ->query(
                            'SELECT * FROM space_invitations
                            WHERE id = ? AND accepted_at IS NULL
                            AND expires_at > ?
                            FOR UPDATE',
                            array(
                                $invitation->id,
                                date('Y-m-d H:i:s')
                            )
                        )
                        ->row();

                    if (!$locked_invitation) {
                        $this->db->trans_rollback();

                        show_error(
                            'Undangan sudah digunakan atau kedaluwarsa.',
                            400
                        );
                        return;
                    }

                    $user_id = $this->User_model->create_user(
                        $name,
                        $email,
                        $password
                    );

                    if ($user_id) {
                        $this->db->insert('memory_members', array(
                            'space_id' => $locked_invitation->space_id,
                            'user_id'  => $user_id,
                            'role'     => 'member'
                        ));

                        $this->db
                            ->where('id', $locked_invitation->id)
                            ->where('accepted_at IS NULL', NULL, FALSE)
                            ->update('space_invitations', array(
                                'accepted_at' => date('Y-m-d H:i:s')
                            ));
                    }

                    if (
                        $user_id &&
                        $this->db->trans_status() !== FALSE
                    ) {
                        $this->db->trans_commit();

                        $this->session->sess_regenerate(TRUE);
                        $this->session->set_userdata(array(
                            'user_id'   => $user_id,
                            'user_name' => $name
                        ));

                        redirect('dashboard');
                        return;
                    }

                    $this->db->trans_rollback();

                    $data['error'] =
                        'Pendaftaran gagal. Silakan coba kembali.';
                }
            }
        }

        $this->load->view('invitations/accept', $data);
    }

    private function join_invited_space($invitation, $user_id)
    {
        $this->db->trans_begin();

        $locked_invitation = $this->db
            ->query(
                'SELECT * FROM space_invitations
                WHERE id = ? AND accepted_at IS NULL
                AND expires_at > ?
                FOR UPDATE',
                array(
                    $invitation->id,
                    date('Y-m-d H:i:s')
                )
            )
            ->row();

        if (!$locked_invitation) {
            $this->db->trans_rollback();
            show_error('Undangan sudah digunakan atau kedaluwarsa.', 400);
            return;
        }

        $user = $this->db
            ->select('id, email, is_active')
            ->where('id', $user_id)
            ->get('users')
            ->row();

        if (
            !$user ||
            (int) $user->is_active !== 1 ||
            strtolower($user->email) !== strtolower($locked_invitation->invited_email)
        ) {
            $this->db->trans_rollback();
            show_error('Akun tidak sesuai dengan undangan.', 403);
            return;
        }

        $existing_member = $this->db
            ->where('space_id', $locked_invitation->space_id)
            ->where('user_id', $user_id)
            ->get('memory_members')
            ->row();

        if ($existing_member) {
            $this->db->trans_rollback();
            show_error('Kamu sudah menjadi anggota ruang ini.', 400);
            return;
        }

        $this->db->insert('memory_members', array(
            'space_id' => $locked_invitation->space_id,
            'user_id'  => $user_id,
            'role'     => 'member'
        ));

        $this->db
            ->where('id', $locked_invitation->id)
            ->where('accepted_at IS NULL', NULL, FALSE)
            ->update('space_invitations', array(
                'accepted_at' => date('Y-m-d H:i:s')
            ));

        if ($this->db->trans_status() !== FALSE) {
            $this->db->trans_commit();
            redirect('dashboard');
            return;
        }

        $this->db->trans_rollback();
        show_error('Gagal bergabung ke ruang kenangan.', 500);
    }

}
