<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Version_model extends CI_Model{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
    public function add_visit($file_name)
    {
        $this->db->like('file_name',$file_name);
        $query= $this->db->get('videos_clientes');
        $info['video'] = $query->row_array();
        $n = $info['video']['visitas'] + 1;
        $this->db->where('file_name',$info['video']['file_name']);
        $this->db->update('videos_clientes', array('visitas' => $n));
    }
    
    public function get_productora($project)
    {
        $trans = array('aQ'=>'á','eQ'=>'é','iQ'=>'í','oQ'=>'ó','uQ'=>'ú','_'=>' ');
        $project = strtr($project,$trans);
        $query = $this->db->get_where('proyectos_clientes', array('project' => $project));
        $prod = $query->row_array();	
        return $prod['cliente'];	
    } 
		  
    public function get_version()
    {
        $proyecto = $this->uri->segment(2);
        if ($this->uri->segment(3) && $this->uri->segment(3) !== 'secure')
        {        	
            if ($proyecto === 'video' || $proyecto === 'login')
            {
                $proyecto = $this->uri->segment(3);
                $video_file = FALSE;
            }
            else
            {
                $video_file = urldecode($this->uri->segment(3));
            }
        }
        elseif ( ! $this->uri->segment(3))
        {
            $video_file = FALSE;
        }
        else
        {
            $videoHash =  $this->uri->segment(4);
            $this->ftp->connect();
            $files = array_diff($this->ftp->list_files($proyecto.'/secure/'), array('..', '.'));
            $this->ftp->close();
            foreach ($files as $file)
            {
                $urlFile = md5(str_replace('_','',$file));
                if ($urlFile === $videoHash || md5($file) === $videoHash)
                {
                        if ($urlFile === $videoHash)	
                                $video_file = str_replace('_',' ',$file);
                        else
                                $video_file = $file;
                        break;
                }        		
            }
        }
        if ($video_file)
        { 
            if ($proyecto !== 'download')
            {
            $this->db->like('file_name',$video_file);
            }
            else $this->db->like('id',$video_file);
        }
        else $this->db->like('id',$proyecto);
        $query= $this->db->get('videos_clientes');
        $info['video'] = $query->row_array();
        return $info;
    }
	    
    public function save_custcom($id,$comments)
    {
        $this->db->where('id',$id);
        return $this->db->update('videos_clientes',array('comments' => $comments));
    }
    //Check credentials for users whose login data was sent by e-mail for just a particular video.
    public function validate()
    {
        //Grab user input
        $username = $this->security->xss_clean($this->input->post('usuario'));
        $password = $this->security->xss_clean($this->input->post('password'));
        $videoId = $this->security->xss_clean($this->input->post('videoid'));
        //Run the query in table "videos_clientes"
        $query = $this->db->get_where('videos_clientes', array('id' => $videoId));
        $row = $query->row_array();
        // Let's check if there are any results
        if ($row)
        {
            $project = str_replace(' ','_',$row['proyecto']);
            $file = md5(str_replace(' ','',$row['file_name']));
            $user = substr(strtolower($project), 0, 5);
            $accessKey = substr(md5('sec' . $file) , 0, 6);
            if ($user === $username && $accessKey === $password)
            {
                $data = array(
                    'videoid' => $videoId,
                    'project'=> $project,
                    'user' => $user,          
                    'type' => 'guest', 
                    'validated' => TRUE
                    );
                $this->session->set_userdata($data);
                return $data;
            }  		
        }	        
        // If any of the previous comparisons did not validate then return just the video ID.
        return $videoId;        
    }
}