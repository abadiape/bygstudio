<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login_model extends CI_Model{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('email');
    }
    
    public function getmail()
    {
        $username = $this->security->xss_clean($this->input->get('info'));
        $query = $this->db->get_where('byg_users',array('user' => $username)); 
        if ($query->num_rows() === 0)
            return FALSE;
        else 
        {
            $row = $query->row_array();
            $newpass = substr($row['pwd'], 0, 8);
            send_email($row['correo'], 'Password Bygstudio', 'Su nuevo password para la página web administrativa de Bygstudio es: ' . $newpass);
            $this->db->where('user', $username);
            $newpass = md5('byg' . $newpass).':byg';
            $this->db->update('byg_users', array('pwd' => $newpass));
            return $row;
        }
    } 
      
    public function validate()
    {
        //Grab user input
        $username = $this->security->xss_clean($this->input->post('usuario'));
        $password = $this->security->xss_clean($this->input->post('password'));
        $password = md5('byg' . $password).':byg';
        // Prep the query
        $this->db->where('user', $username);   
        // Run the query in byg_users
        $query = $this->db->get('byg_users');
        // Let's check if there are any results
        if($query->num_rows === 1)
        {
            // If there is a user, then create session data
            $row = $query->row();
            if ($row->pwd === $password)
            {
                $data = array(
                        'userid' => $row->id,
                        'name' => $row->nombre,
                        'correo' => $row->correo,
                        'user' => $row->user,
                        'type' => 'admin',
                        'validated' => true
                        );
                $this->session->set_userdata($data);
                return true;
            }
        }
        else
        {
            // once again prep the query
            $this->db->where('user', $username);
            // Run the query in "clientes" table
            $query = $this->db->get('customers_users');
            // Let's check if there are any results
            if($query->num_rows === 1)
            {
                // If there is a user, then create session data
                $row = $query->row();
                if ($row->pwd === $password)
                {
                    $data = array(
                    'userid' => $row->id,
                    'name' => $row->contact,
                    'prod'=> $row->cliente,
                    'user' => $row->user,
                    'correo' => $row->correo,
                    'type' => 'client',
                    'validated' => true
                    );
                    $this->session->set_userdata($data);
                    return true;
                }
            }     	
            // If any of the previous comparisons did not validate
            // then return false.
            return false;
        }
    }
    
     public function verify() 
    {
     // grab user input
        $username = $this->security->xss_clean($this->input->get('user'));
        $password = $this->security->xss_clean($this->input->get('info'));
        $password = md5('byg' . $password).':byg';
        $newp = $this->security->xss_clean($this->input->get('new'));
        // Prep the query
        $this->db->where('user', $username);
        $this->db->where('pwd', $password);      
        // Run the query in byg_users
        $query = $this->db->get('byg_users');
        // Let's check if there are any results
        if($query->num_rows() === 1)
        {
            $query->free_result();
            $this->db->where('user', $username);
            $newp = md5('byg' . $newp).':byg';
            $this->db->update('byg_users', array('pwd' => $newp));
            return true;
        }
        return false; 
}
}