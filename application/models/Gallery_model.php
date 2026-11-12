
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gallery_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Mengambil seluruh foto dalam satu memory space.
     */
    public function get_photos($space_id)
    {
        return $this->db
            ->select('gallery_photos.*, users.name AS uploader_name')
            ->from('gallery_photos')
            ->join(
                'users',
                'users.id = gallery_photos.uploaded_by',
                'left'
            )
            ->where('gallery_photos.space_id', $space_id)
            ->order_by('gallery_photos.photo_date', 'DESC')
            ->order_by('gallery_photos.id', 'DESC')
            ->get()
            ->result();
    }

    /**
     * Mengambil satu foto berdasarkan ID dan memory space.
     */
    public function get_photo($photo_id, $space_id)
    {
        return $this->db
            ->where('id', $photo_id)
            ->where('space_id', $space_id)
            ->get('gallery_photos')
            ->row();
    }

    /**
     * Menyimpan data foto baru.
     */
    public function create_photo($data)
    {
        return $this->db->insert('gallery_photos', $data);
    }

    /**
     * Menghapus data foto berdasarkan ID dan memory space.
     */
    public function delete_photo($photo_id, $space_id)
    {
        return $this->db
            ->where('id', $photo_id)
            ->where('space_id', $space_id)
            ->delete('gallery_photos');
    }
}
