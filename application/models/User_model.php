
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
    public function find_by_email($email)
    {
        return $this->db
            ->where('email', strtolower(trim($email)))
            ->limit(1)
            ->get('users')
            ->row();
    }

    public function create_user($name, $email, $password)
    {
        $this->db->insert('users', array(
            'name'          => trim($name),
            'email'         => strtolower(trim($email)),
            'password_hash' => password_hash(
                $password,
                PASSWORD_DEFAULT
            )
        ));

        return $this->db->insert_id();
    }
}
