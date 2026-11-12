
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Memory_model extends CI_Model
{
    /**
     * Mengambil ruang kenangan tempat user menjadi anggota.
     */
    public function get_user_space($user_id)
    {
        return $this->db
            ->select('memory_spaces.id, memory_spaces.name')
            ->from('memory_spaces')
            ->join(
                'memory_members',
                'memory_members.space_id = memory_spaces.id'
            )
            ->where('memory_members.user_id', (int) $user_id)
            ->limit(1)
            ->get()
            ->row();
    }

    /**
     * Mengambil semua kenangan milik ruang bersama.
     */
    public function get_memories($space_id)
    {
        return $this->db
            ->select(
                'memories.*, users.name AS creator_name'
            )
            ->from('memories')
            ->join('users', 'users.id = memories.created_by')
            ->where('memories.space_id', (int) $space_id)
            ->order_by('memories.memory_date', 'DESC')
            ->order_by('memories.id', 'DESC')
            ->get()
            ->result();
    }

    /**
     * Mengambil satu kenangan berdasarkan ID dan ruang.
     */
    public function get_memory($memory_id, $space_id)
    {
        return $this->db
            ->select('memories.*, users.name AS creator_name')
            ->from('memories')
            ->join('users', 'users.id = memories.created_by')
            ->where('memories.id', (int) $memory_id)
            ->where('memories.space_id', (int) $space_id)
            ->limit(1)
            ->get()
            ->row();
    }

    /**
     * Menyimpan kenangan baru.
     */
    public function create_memory($data)
    {
        $this->db->insert('memories', $data);

        if ($this->db->affected_rows() !== 1) {
            return FALSE;
        }

        return $this->db->insert_id();
    }

    /**
     * Mengubah kenangan hanya jika berada di ruang yang sesuai.
     */
    public function update_memory($memory_id, $space_id, $data)
    {
        $this->db
            ->where('id', (int) $memory_id)
            ->where('space_id', (int) $space_id)
            ->update('memories', $data);

        return $this->db->affected_rows() >= 0
            && $this->get_memory($memory_id, $space_id) !== NULL;
    }

    /**
     * Menghapus kenangan hanya dari ruang yang sesuai.
     */
    public function delete_memory($memory_id, $space_id)
    {
        $memory = $this->get_memory($memory_id, $space_id);

        if (!$memory) {
            return FALSE;
        }

        $this->db
            ->where('id', (int) $memory_id)
            ->where('space_id', (int) $space_id)
            ->delete('memories');

        return $this->db->affected_rows() === 1;
    }
}
