
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Space_invitation_model extends CI_Model
{
    public function create_invitation($space_id, $email, $created_by)
    {
        // Token acak untuk tautan undangan.
        $token = bin2hex(random_bytes(32));

        $this->db->insert('space_invitations', array(
            'space_id'       => $space_id,
            'invited_email'  => strtolower(trim($email)),
            'token_hash'     => hash('sha256', $token),
            'expires_at'     => date('Y-m-d H:i:s', strtotime('+7 days')),
            'created_by'     => $created_by
        ));

        if ($this->db->affected_rows() !== 1) {
            return FALSE;
        }

        // Token asli hanya diberikan kepada pembuat tautan.
        return $token;
    }

    public function find_valid_invitation($token)
    {
        if (!is_string($token) || !preg_match('/^[a-f0-9]{64}$/', $token)) {
            return NULL;
        }

        return $this->db
            ->select('space_invitations.*, memory_spaces.name AS space_name')
            ->from('space_invitations')
            ->join(
                'memory_spaces',
                'memory_spaces.id = space_invitations.space_id'
            )
            ->where('space_invitations.token_hash', hash('sha256', $token))
            ->where('space_invitations.accepted_at IS NULL', NULL, FALSE)
            ->where('space_invitations.expires_at >', date('Y-m-d H:i:s'))
            ->get()
            ->row();
    }
}
