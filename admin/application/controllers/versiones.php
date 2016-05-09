<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Versiones extends CI_Controller {

    public function __construct()
    {
            parent::__construct();
            $this->load->helper('download');
            $this->load->library('email');
            $this->load->helper('form');
            $this->load->library('form_validation');
            $this->load->library('ftp');
            $this->load->library('session');
            $this->load->model('version_model');	
    }
    
    public function download ()
    {
        $data['version'] = $this->version_model->get_version();
        $file_name = $data['version']['video']['file_name'];
        $trans = array('�'=>'aQ','�'=>'eQ','�'=>'iQ','�'=>'oQ','�'=>'uQ',' '=>'_','á'=>'aQ','é'=>'eQ','�'=>'iQ','ó'=>'oQ','ú'=>'uQ');
        $proyecto = strtr($data['version']['video']['proyecto'],$trans);
        if ($this->session->userdata('type') === 'guest')
        {
                $proyecto = $proyecto . '/secure';
        }
        $content = file_get_contents(base_url('uploads/'.$proyecto.'/'.str_replace(' ','_',$file_name)));
        $this->version_model->add_visit($file_name);
        force_download($file_name,$content);
    }
    
    public function index ()
    {
        if ($this->uri->segment(2) !== 'video' && ($this->uri->segment(3) || is_numeric($this->uri->segment(2))))
        {
            $data['title'] = $this->uri->segment(2);
        }
        elseif ($this->uri->segment(2) === 'video' || $this->session->userdata('validated'))
        {
            $data['title'] = "secure";
        }
        else $data['title'] = "";
        $data['version'] = $this->version_model->get_version();
        if ($this->uri->segment(4))
        {
            $this->load->view('templates/header',$data);
        }
        else
        {
            if ($data['title'] === "secure" && ! $this->session->userdata('validated'))
                $this->load->view('templates/header',$data);
            else
                $this->load->view('templates/header_video',$data);
        }
            $this->load->view('templates/logo');
            if ($this->uri->segment(4))
            {
                $this->load->view('clientes/login_cliente',$data);
            }
            elseif ($this->uri->segment(3))
            {
                if ($data['title'] !== "secure")
                {
                    $this->load->view('clientes/show_version',$data);
                }
                else
                {
                    if ($this->session->userdata('validated'))
                    {
                            $this->load->view('clientes/show_version',$data);
                    }
                    else
                    {
                            $this->load->view('clientes/login_cliente',$data);
                    }
                }
            }
            else 
            {
                $this->load->view('clientes/play_version',$data);
                $file_name = $data['version']['video']['file_name'];
                $this->version_model->add_visit($file_name);
            }
            $this->load->view('templates/footer');
    }
    //Valida los datos del usuario para permitirle ver la nueva versi�n del proyecto
    public function login($title = NULL){
        $this->form_validation->set_rules('usuario','User','required');
        $this->form_validation->set_rules('password','Password','required');
        if ($this->uri->segment(2) === 'login')
            $title = '<span style="color:#C6D73D; font-size:.5em">Usuario y/o clave no valida. Por favor re-intente!</span>';
        else
                $title = '';	
        if($this->form_validation->run() === FALSE)
        {		
            $data['version'] = $this->version_model->get_version();
            $data['title'] = $data['version']['video']['proyecto'] . ' ' . $title;
            $this->load->view('templates/header',$data);
            $this->load->view('templates/logo',$data);
            $this->load->view('clientes/login_cliente',$data);
            $this->load->view('templates/footer');
        }
        else 
        {	
            $result = $this->version_model->validate();
            // Now we verify the result
            if ( ! is_array($result))
            {
            //If user did not validate, then show them login page again           			
                    redirect(site_url('versiones/login/'.$result));           			
            }
            else
            {          			
                    redirect(site_url('versiones/video/'.$this->session->userdata('videoid')));
            }        
        }
    }
    /*Env�a los comentarios hechos por el cliente para una versi�n espec�fica que se le subi�*/	 
    public function sendcomments()
    {
        $id = $this->input->get('info1');
        $comments = $this->input->get('info2');
        $projver = $this->input->get('info3');
        $pos = strpos($projver,'/');
        $project = substr($projver,0,($pos));
        $productora = $this->version_model->get_productora($project);
        $prod = str_replace(' ','',$productora);
        $this->version_model->save_custcom($id,$comments);		 
        $this->email->from('email@'.$prod.'.com',$productora);
        $this->email->to('camilo.a@bygfilms.com,eduardo.a@bygstudio.com,luisa.h@bygstudio.com'); 
        $this->email->bcc('rafael@bygstudio.com');
        $this->email->subject('Comentarios '.$projver);
        $this->email->message($comments);
        if (!$this->email->send())
        {
                echo $this->email->print_debugger(); 
        }
        else echo "Comentarios enviados!";
     }
     /*Aumenta el contador de visitas para una versi�n espec�fica de un video*/
     public function addvisit()
     {
        $file_name = $this->input->get('fname');
        $this->version_model->add_visit($file_name);
     }
}
