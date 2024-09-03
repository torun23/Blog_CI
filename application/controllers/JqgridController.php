<?php

defined('BASEPATH') or exit('No direct script access allowed');
/**
 *
 */
class JqgridController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('JqgridModel');
    }
public function getAllProfilesData() {
    $this->load->model('JqgridModel');
    $profiles = $this->JqgridModel->getAllProfiles();
    
    echo json_encode($profiles);
}
public function getFormDetails($id) {
    $this->load->model('JqgridModel');
    $formDetails = $this->JqgridModel->getFormDetailsById($id);
    echo json_encode($formDetails);
}
    
    
    
    
    public function grid()
    {
        $this->load->view('jqgrid_view');
    }

    public function getData()
    {
        $page = $this->input->get('page', true);
        $limit = $this->input->get('rows', true);
        $sidx = $this->input->get('sidx', true);
        $sord = $this->input->get('sord', true);
        $filters = $this->input->get('filters', true);

        if (!$sidx) {
            $sidx = 'id'; // Default sorting by 'id'
        }

        if (!$limit || $limit == 0) {
            $limit = 10; // Default to 10 rows if limit is not set or is zero
        }

        $where = "";
        if ($filters) {
            $filters = json_decode($filters, true);
            $whereArray = [];

            if (isset($filters['rules']) && is_array($filters['rules'])) {
                foreach ($filters['rules'] as $rule) {
                    $field = $this->db->escape_str($rule['field']);
                    $data = $this->db->escape_like_str($rule['data']);
                    $whereArray[] = "$field LIKE '%$data%'";
                }
            }

            if (!empty($whereArray)) {
                $where = "WHERE " . implode(" AND ", $whereArray);
            }
        }

        $countQuery = $this->db->query("SELECT COUNT(*) AS count FROM forms $where");
        $countResult = $countQuery->row();
        $count = $countResult ? $countResult->count : 0;

        $total_pages = ($count > 0) ? ceil($count / $limit) : 0;

        if ($page > $total_pages) {
            $page = $total_pages;
        }

        $start = ($limit * $page) - $limit;
        if ($start < 0) {
            $start = 0;
        }

        $query = $this->db->query(
            "
            SELECT Serial_ID,id, title, response_link, created_at, is_responsive,user_id
            FROM forms
            $where
            ORDER BY $sidx $sord
            LIMIT $start, $limit
        "
        );

        $rows = $query->result_array();

        $response = new stdClass();
        $response->page = $page;
        $response->total = $total_pages;
        $response->records = $count;
        $response->rows = [];

        foreach ($rows as $row) {
            $response->rows[] = [
                'id' => $row['id'],  // This must be 'id' to match the data index
                'cell' => [
                    $row['Serial_ID'],
                    $row['id'],
                    $row['title'],
                    $row['response_link'],
                    $row['created_at'],
                    $row['is_responsive'],
                    $row['user_id'],
                ]
            ];
        }

        echo json_encode($response);
    }

    public function deleteData($id)
    {

        log_message('debug', 'Deleting record with ID: ' . $id);  // Log the ID being deleted

        if ($id) {
            $this->db->where('id', $id);
            if ($this->db->delete('forms')) {
                echo "success";
            } else {
                echo "error";
            }
        } else {
            echo "error";
        }
    }

    public function updateData()
    {
        $id = $this->input->post('id', true);
        $data = [
            'title' => $this->input->post('title', true),
            'response_link' => $this->input->post('response_link', true),
            'is_responsive' => $this->input->post('is_responsive', true)
        ];

        if ($id) {
            $result = $this->JqgridModel->updateRecord($id, $data);

            if ($result) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update record.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid ID provided.']);
        }
    }

    public function addData()
    {
        // Get all POST data without XSS filtering
        $data = [
            'user_id' => $this->input->post('user_id'),
            'title' => $this->input->post('title'),
            'response_link' => $this->input->post('response_link'),
            'created_at' => date('Y-m-d H:i:s'),
            'is_responsive' => $this->input->post('is_responsive')

        ];

        // Debugging: Log the received data (optional)
        // log_message('debug', 'Data to be inserted: ' . print_r($data, true));
        // var_dump($data);

        // Save the data to the database
        $result = $this->JqgridModel->insertRow($data);

        if ($result) {
            echo json_encode(array(
                "status" => "success"
            ));
            // echo json_encode(['status' => 'success', 'insert_id' => $result]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to insert record.']);
        }
    }


}
