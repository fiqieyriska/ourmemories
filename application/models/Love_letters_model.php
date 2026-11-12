
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Love_letters_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Mengambil semua surat dalam memory space.
     */
    public function get_letters($space_id)
    {
        return $this->db
            ->select('love_letters.*, users.name AS author_name')
            ->from('love_letters')
            ->join(
                'users',
                'users.id = love_letters.author_id',
                'left'
            )
            ->where('love_letters.space_id', $space_id)
            ->order_by('love_letters.created_at', 'DESC')
            ->order_by('love_letters.id', 'DESC')
            ->get()
            ->result();
    }

    /**
     * Mengambil detail surat berdasarkan ID dan memory space.
     */
    public function get_letter($letter_id, $space_id)
    {
        return $this->db
            ->select('love_letters.*, users.name AS author_name')
            ->from('love_letters')
            ->join(
                'users',
                'users.id = love_letters.author_id',
                'left'
            )
            ->where('love_letters.id', $letter_id)
            ->where('love_letters.space_id', $space_id)
            ->get()
            ->row();
    }

    /**
     * Menyimpan surat baru.
     */
    public function create_letter($data)
    {
        return $this->db->insert('love_letters', $data);
    }

    /**
     * Memperbarui surat.
     */
    public function update_letter($letter_id, $space_id, $data)
    {
        $this->db
            ->where('id', $letter_id)
            ->where('space_id', $space_id);

        return $this->db->update('love_letters', $data);
    }

    /**
     * Menghapus surat berdasarkan ID dan memory space.
     */
    public function delete_letter($letter_id, $space_id)
    {
        return $this->db
            ->where('id', $letter_id)
            ->where('space_id', $space_id)
            ->delete('love_letters');
    }
}
