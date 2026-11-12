
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Special_dates_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Mengambil semua tanggal penting dalam memory space.
     */
    public function get_dates($space_id)
    {
        return $this->db
            ->select('special_dates.*, users.name AS creator_name')
            ->from('special_dates')
            ->join(
                'users',
                'users.id = special_dates.created_by',
                'left'
            )
            ->where('special_dates.space_id', $space_id)
            ->order_by('special_dates.event_date', 'ASC')
            ->order_by('special_dates.id', 'DESC')
            ->get()
            ->result();
    }

    /**
     * Mengambil detail tanggal berdasarkan ID dan memory space.
     */
    public function get_date($id, $space_id)
    {
        return $this->db
            ->where('id', $id)
            ->where('space_id', $space_id)
            ->get('special_dates')
            ->row();
    }

    /**
     * Menyimpan tanggal penting.
     */
    public function create_date($data)
    {
        return $this->db->insert('special_dates', $data);
    }

    /**
     * Memperbarui tanggal penting.
     */
    public function update_date($id, $space_id, $data)
    {
        return $this->db
            ->where('id', $id)
            ->where('space_id', $space_id)
            ->update('special_dates', $data);
    }

    /**
     * Menghapus tanggal penting.
     */
    public function delete_date($id, $space_id)
    {
        return $this->db
            ->where('id', $id)
            ->where('space_id', $space_id)
            ->delete('special_dates');
    }
}
