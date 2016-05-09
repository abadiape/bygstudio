  <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('login_model');	
    }
	
    public function index($title = NULL)
    {
        $this->form_validation->set_rules('usuario','User','required');
        $this->form_validation->set_rules('password','Password','required');

        if($this->form_validation->run() === FALSE || $title !== NULL)
        {
            $data['title'] = $title;
            if (! is_null($title))
                $data['title'] = 'Login page error!';
            $this->load->view('templates/header',$data);
            $data['title'] = $title;
            $this->load->view('templates/logo',$data);
            $this->load->view('clientes/login',$data);
            $this->load->view('templates/footer');
        }
        else 
        {	
            $result = $this->login_model->validate();
            // Now we verify the result
            if ( ! $result)
            {
            // If user did not validate, then show them login page again
                $title = '<div class="alert alert-danger">
  <strong>Danger!</strong> Invalid user name and/or password! Please try again!
</div>';
                $this->index($title);
            }
            else
            {
                // If user did validate, 
                // Send them to members area
                if ($this->session->userdata('type') == 'admin')
                {
                        redirect(site_url('clientes'));
                }
                else
                {         			
                        redirect(site_url('show/'.$this->session->userdata('user')));
                }
            }        
        }
    }
	
    public function changepass() {
        $field = $this->load->view('clientes/changepass', '', true);
        echo $field;
    }

    public function cambio() {
        $result = $this->login_model->verify();	
        echo $result;
    }

    public function pwdmail() {
        $result = $this->login_model->getmail();
        if ($result)
            echo $result['correo'];
        else return FALSE;
    }	
}