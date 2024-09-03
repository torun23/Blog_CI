<?php

class JqgridModel extends CI_Model
{
    public function deleteForm($id)
    {
        return $this->db->delete('forms', ['id' => $id]);
    }
    public function getAllProfiles() {
        $this->db->select('profile_id, profile_name, mail, gender');
        $this->db->from('profiles');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getFormDetailsById($id) {
        $this->db->select('description, is_published');
        $this->db->from('forms');
        $this->db->where('Serial_ID', $id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return false;
        }
    } 
    
    
    
    public function updateRecord($id, $data)
    {
        $this->db->where('id', $id);
        $updated = $this->db->update('forms', $data);
        
        if ($updated) {
            log_message('info', "Form with ID $id successfully updated.");
            return true;
        } else {
            log_message('error', "Failed to update form with ID $id.");
            return false;
        }
    }

    public function insertRow($data)
    {
        // Use the 'set' method to set the data to be inserted
        $this->db->set($data);
        // var_dump($data);
        $inserted = $this->db->insert('forms');
        
        if ($inserted) {
            $insert_id = $this->db->insert_id();
            log_message('info', "Form with ID $insert_id successfully inserted.");
            return $insert_id;
        } else {
            log_message('error', "Failed to insert new form.");
            return false;
        }
    }
    
    
}
