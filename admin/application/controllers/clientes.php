<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Clientes extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('email');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('ftp');
		$this->load->model('clientes_model');
		$this->load->library('pagination');
		$this->check_isvalidated();	
	}
        //Adds a new clip Category in table clip_categories.
        public function addClipCategory()
        {
            $name = $data['name'] = strtolower($this->input->get('name'));
            $order = $data['order'] = $this->input->get('position');
            $visible = $this->input->get('visible');
            $info = $this->clientes_model->add_clip_category($name, $visible, $order);
            $data['label'] = 'Categoría';
            $data['code'] = $info['code'];
            $data['id'] = $info['id'];
            $data['visible'] = $visible;
            $newCatHtml = $this->load->view('clientes/new_clip_category',$data, true);
            echo $newCatHtml;
        }
        //It loads and sends the empty new clip category html Div.
        public function addNewCategoryDiv()
        {
            $newCatDivHtml = $this->load->view('clientes/new_category','', true);
            echo $newCatDivHtml;
        }       
        /*Adiciona un nuevo proyecto. Se requiere un nombre para el proyecto y seleccionar la productora. */
	public function agregar() 
	{
		$data['title'] = 'Proyectos ';
		$cliente = $this->uri->segment(2);
		$this->form_validation->set_rules('project','Proyecto','required');
		$order = FALSE;
		$data['clientes'] = $this->clientes_model->get_clientes($cliente,$order);
		$type = $this->session->userdata('type');
		if($this->form_validation->run() === FALSE)
		{		
			$this->load->view('templates/header_creardocs',$data);
			if ($type === 'admin')
			{
				$this->load->view('templates/logo');
				$this->load->view('clientes/agregar',$data);
			}
			else 
			{
				$this->load->view('templates/logo');
			}
		}
		else 
		{
			$data['title'] = 'Proyectos ';
			$data['projects'] = $this->clientes_model->set_proyecto();
			$this->load->view('templates/header',$data);
			if ($type === 'admin')
			{
				$this->load->view('templates/menu_admin');
				$this->load->view('templates/logo');
				$this->load->view('clientes/show_projects',$data);
			}
			else 
			{
				$this->load->view('templates/logo');
			}
		}
		$this->load->view('templates/footer');
	}
        /*Función de búsqueda */
	public function buscar()
	{
		$user = $this->session->userdata('type');
		$busq = $this->input->post('texto');
		if ($this->input->post("pageppal") === "Productoras")
		{
			if (empty($busq))
			{
				$data['title'] = 'Por favor ingrese un criterio de busqueda!'; 
				$this->load->view('templates/header',$data);
				$this->load->view('templates/menu_admin');
				$this->load->view('templates/logo');
			}
			else 
			{
				$data['clientes'] = $this->clientes_model->buscar_dato($busq,$user);
				if (empty($data['clientes']))
				{
					$data['title'] = 'No se encontraron coincidencias!'; 
					$this->load->view('templates/header',$data);
					$this->load->view('templates/menu_admin');
					$this->load->view('templates/logo');
					$this->load->view('clientes/crear',$data);		
				}
				else
				{			
					$data['title'] = 'Resultados Busqueda';		
					$this->load->view('templates/header',$data);
					$this->load->view('templates/menu_admin');
					$this->load->view('templates/logo');
					$this->load->view('clientes/view',$data);
				}; 
			};
		}
		else
		{
			if (empty($busq))
			{
				$data['title'] = 'Por favor ingrese un criterio de busqueda!'; 
				$this->load->view('templates/header',$data);
				if ($user === 'admin') $this->load->view('templates/menu_admin');
				else $this->load->view('templates/menu_clientes');
				$this->load->view('templates/logo');
			}
			else 
			{
				$data['clientes'] = $this->clientes_model->buscar_dato($busq,$user);
				if (empty($data['clientes']))
				{
					$data['title'] = 'No se encontraron coincidencias!'; 
					$this->load->view('templates/header',$data);
					if ($user === 'admin') $this->load->view('templates/menu_admin');
					else $this->load->view('templates/menu_clientes');
					$this->load->view('templates/logo');	
				}
				else
				{			
					$data['title'] = 'Resultados Búsqueda';		
					$this->load->view('templates/header',$data);
					if ($user === 'admin') $this->load->view('templates/menu_admin');
					else $this->load->view('templates/menu_clientes');
					$this->load->view('templates/logo');
					$this->load->view('clientes/ver',$data);
				};
			}
		}
		$this->load->view('templates/footer');
	}
        //It changes the order of appearance on the web page of a given category. 
        public function changeCategoryOrder()
        {
            $newValue = $this->input->get('value');
            $categoryId = $this->input->get('category_id');
            $result = $this->clientes_model->change_categoryOrder($categoryId,$newValue);
            echo json_encode($result);
        }
        /*Verifica que el usuario haya sido validado, sino lo envía a la página de login. */	
	private function check_isvalidated()
	{
        	if(! $this->session->userdata('validated'))
        	{
            		redirect(site_url('login'));
            	}
        }
        /*Deletes a given clip from the database and the server file system, based on clip type (category) and position.*/
        public function clipDelete()
        {
            $clipSuffix = substr($this->input->get('clip_suffix'), 1);
            $dashPos = strpos($clipSuffix, '-');
            $type = substr($clipSuffix, 0, $dashPos);
            $listOrder = substr($clipSuffix, $dashPos + 1);
            $clipDeleted = $this->clientes_model->clip_delete($type, $listOrder);
            echo $clipDeleted;   
        }
        /*Permite modificar los comentarios hechos por byg a un proyecto dado en una nueva ventana*/
        public function comments ()
	{
		$data['title'] = 'Comentarios ';
		$this->form_validation->set_rules('modify','Comentarios','required');
		if($this->form_validation->run() === FALSE)
		{
			$data['project'] = $this->clientes_model->get_comments();
			$this->load->view('templates/header',$data);
			$this->load->view('templates/logo');
			$this->load->view('clientes/show_comments',$data);
		}
		else
		{
			$this->clientes_model->save_comments();
			$data['title'] = 'Comentarios modificados!';
			$this->load->view('templates/header',$data);
			$this->load->view('templates/logo');
			echo "<h1>".$data['title']."</h1>";
			$this->load->view('templates/footer');
		}		
	}	
        /*Adiciona una nueva productora(cliente) con sus datos.	*/
	public function crear ()
	{
            $data['title'] = 'Nueva productora';
            $type = $this->session->userdata('type');		
            $this->form_validation->set_rules('cliente','Cliente','required');
            $this->form_validation->set_rules('rfc','RFC','required');
            $this->form_validation->set_rules('address','Direccion','required');
            $this->form_validation->set_rules('tel','Telefono','required');
            $this->form_validation->set_rules('contact1','Contacto','required');
            $this->form_validation->set_rules('correo1','Correo','required');
            $this->form_validation->set_rules('tel1','Telefono','required');
            /*$this->form_validation->set_rules('user1','Usuario','required');
            $this->form_validation->set_rules('pwd1','Password','required');*/
            if($this->form_validation->run() === FALSE)
            {		
                $this->load->view('templates/header',$data);
                $this->load->view('templates/logo');
                if ($type === 'admin')
                {
                    $this->load->view('clientes/crear',$data);
                }
            }
            else 
            {
                $data['title'] = 'El cliente ha sido creado con  &eacute;xito';
                $cliente = $this->input->post('cliente');
                $this->clientes_model->set_clientes();
                $data['clientes'] = $this->clientes_model->get_clientes($cliente);
                $this->load->view('templates/header',$data);
                $this->load->view('templates/menu_admin');
                $this->load->view('templates/logo');
                $this->load->view('clientes/view',$data);	
            }
            $this->load->view('templates/footer');
	}
        /*Creación de nuevos documentos para un proyecto dado.	*/
	public function creardocs ()
	{
		$data['title'] = 'Nuevo documento';
		$this->load->view('templates/header_creardocs',$data);
		$type = $this->session->userdata('type');
		if ($type === 'admin')
		{
			$this->load->view('templates/menu_admin');
			$this->load->view('templates/logo');
			$this->load->view('clientes/creardocs',$data);
		}
		else $this->load->view('templates/logo');
		$this->load->view('templates/footer');	
	}	
        /*Borra un contacto de un cliente dado */	
	public function delcont()
	{
	$conta = $this->input->get('info');
	$idi = $this->input->get('idi');
	$this->clientes_model->del_contact($conta,$idi);
	echo " Contact ".$conta." deleted!";			
	}
        /*Borra una productora; si ya No tiene proyectos asignados*/	
	public function delcustomer()
	{
            $prod = $this->input->get('info');
            $id = $this->input->get('id');
            if ($this->clientes_model->del_customer($id))
                echo " Productora ".$prod." eliminada!";
            else
                return false;
	}
        /*Borra un proyecto presente en el listado de un cliente (tanto los directorios y archivos, como info. en base de datos). */	
	public function delproject()
	{
	$project = $this->input->get('info');
	$erase = $this->clientes_model->del_project($project);
	if ($erase)
	{
		$data['title'] = 'Recarga ';
		$data['projects'] = $this->clientes_model->get_projects();
		$listado = $this->load->view('clientes/show_projects',$data, TRUE);	
		echo $listado;
	}			
	else return FALSE;			
	}
/*Borra un video presente en la lista de versiones de un proyecto (tanto el archivo como info. en base de datos). */	
	public function delvideo()
	{
	$id = $this->input->get('infoid');
	$file = $this->input->get('infofile');
	$sign = substr($file,8);
	$erase = $this->clientes_model->del_video($file,$id);
	if ($erase)
	{
		echo 'TRUE';
	}			
	else return FALSE;			
	}
        /*Permite entrar al modo de edicion de un contacto de un cliente dado para cambios, excepto de usuario y contraseña*/	
	public function editcon()
	{
	$type = $this->session->userdata('type');
	$conta['contact'] = $this->input->get('info1');
	$conta['correo'] = $this->input->get('info2');
	$conta['phone'] = $this->input->get('info3');
	$conta['idi'] = $this->input->get('idi');
	$this->form_validation->set_rules('contedit','Contact','required');
	$this->form_validation->set_rules('mailedit','Mail','required');
	$this->form_validation->set_rules('phonedit','Phone','required');
	if($this->form_validation->run() === FALSE && $type === 'admin')
	{
	echo $this->load->view('clientes/edit_mode',$conta);
	}
	else
	{
	$this->clientes_model->update_contact();	
	$cliente = FALSE;
	$data['clientes'] = $this->clientes_model->get_clientes($cliente);
	$data['title'] = 'Productoras ';	
	$this->load->view('templates/header',$data);
		if ($type === 'admin')
		{
		$this->load->view('templates/menu_admin');
		$this->load->view('templates/logo');
		$this->load->view('clientes/view',$data);
		}
		else $this->load->view('templates/logo');
		$this->load->view('templates/footer');	
	}
	}
        /*Permite entrar al modo de edicion de los datos fiscales de un cliente dado*/	
	public function editdata()
	{
            $type = $this->session->userdata('type');
            $data['rfc'] = $this->input->get('info1');
            $data['address'] = $this->input->get('info2');
            $data['tel'] = $this->input->get('info3');
            $data['id'] = $this->input->get('id');
            $data['cliente'] = $this->input->get('cliente');
            $this->form_validation->set_rules('rfcedit','RFC','required');
            $this->form_validation->set_rules('addedit','Address','required');
            $this->form_validation->set_rules('teledit','Tel','required');
            if($this->form_validation->run() === FALSE && $type === 'admin')
            {
            echo $this->load->view('clientes/data_edit',$data);
            }
            else
            {
            $this->clientes_model->update_data();	
            $cliente = FALSE;
            $data['message'] = "Los nuevos datos han sido guardados.";
            $this->load->view('clientes/data_edit',$data);
            /*$data['clientes'] = $this->clientes_model->get_clientes($cliente);
            $data['title'] = 'Productoras ';	
            $this->load->view('templates/header',$data);
                    if ($type === 'admin')
                    {
                    $this->load->view('templates/menu_admin');
                    $this->load->view('templates/logo');
                    $this->load->view('clientes/view',$data);
                    }
                    else $this->load->view('templates/logo');
                    $this->load->view('templates/footer');*/	
            }
	}
        //It brings a new clips load space for a given category.
        public function getClipLoadSpace()
        {
            $data['key'] = $this->input->get('code');
            if ($this->input->get('listorder'))
            {
               $data['order'] = $this->input->get('listorder'); 
               $clipObject = $this->clientes_model->get_clips($data['key'], $data['order']); 
               $data['title'] = ucwords($clipObject->title);
               $data['text'] = "<p>Arrastre el nuevo STILL para el clip: '" . $data['title'] . "', hacia el espacio debajo, para subirlo.</p>";
            }
            $newClipLoadSpace = $this->load->view('clientes/clip_upload_space', $data, true);
            echo $newClipLoadSpace;
        }     
        /*Muestra el listado de productoras o el Administrador de clips.*/		
	public function index($cliente = FALSE, $order = FALSE)
	{
            $data['title'] = 'Productoras ';
            $data['clientes'] = $this->clientes_model->get_clientes($cliente,$order);
            $data['order'] = $order; 
            $data['clips_admin_title'] = 'Administrador de Clips';
            $data['clips'] = $this->clientes_model->get_clips();
            $this->load->view('templates/header_clientlist',$data);
            $type = $this->session->userdata('type');
            if ($type === 'admin')
            {
                    $this->load->view('templates/menu_admin',$data);
                    $this->load->view('templates/logo');
                    $this->load->view('clientes/clientslist',$data);
            }
            else 
            {
                    $this->load->view('templates/menu_clientes');
                    $this->load->view('templates/logo');
            }
            $this->load->view('templates/footer');
	}
        /*Muestra la pagina de los clips/trailers video/(cinema/series/spots/videos) */
	public function loadclips()
	{
		 	$clipstype = $this->uri->segment('2');
		 	$type = $this->session->userdata('type');
		 	$data['clips'] = $this->clientes_model->get_clips($clipstype);
		 	$data['title'] = 'Clips '.ucfirst($clipstype);
		 	$data['tipo'] = $clipstype;		
		   	$this->load->view('templates/header_upload',$data);
			if ($type === 'admin')
			{
				$this->load->view('templates/logo');
				$this->load->view('clientes/clips_display',$data);
			}
			$this->load->view('templates/footer');				
	}
        /*Sale de la sesión de usuario */	
        public function logout ()
        {
        	$this->session->sess_destroy();
        	redirect(site_url('login'));      
        }
        /*Permite modificar datos de la productora. */	
	public function modificar()
	{
		$data['clientes'] = $this->clientes_model->get_clientes();
		$data['clientes_item'] = $this->clientes_model->get_clientes('id');		
		$data['title'] = 'Cambio datos cliente';		
		$this->form_validation->set_rules('cliente','Cliente','required');
		$this->form_validation->set_rules('rfc','RFC','required');
		$this->form_validation->set_rules('correo','Correo','required');
		$this->form_validation->set_rules('tel','Telefono','required'); 
		$type = $this->session->userdata('type');
		if($this->form_validation->run() === FALSE)
		{		
		$this->load->view('templates/header',$data);
		$this->load->view('templates/logo');
			if ($type === 'admin')
			{
			$this->load->view('clientes/view',$data);
			}
		}
		else 
		{
		$data['title'] = 'Cambios solicitados realizados';			
		$id = $this->input->post('id');
		$this->clientes_model->cambio_cliente($id);
		$data['clientes'] = $this->clientes_model->get_clientes();
		$this->load->view('templates/header_upload',$data);
		$this->load->view('templates/logo');
			if ($type === 'admin')
			{
			$this->load->view('templates/menu_admin');
			$this->load->view('clientes/view');
			}
			else $this->load->view('templates/menu_clientes');			
		}
		$this->load->view('templates/footer');
	}
        /*Permite agregar un nuevo contacto para una productora especifica en una modal*/
        public function newcontact()
	{
            $data['title'] = 'Nuevo contacto ';
            $cliente = $this->uri->segment(2);
            $this->form_validation->set_rules('contactn','Contact','required');
            $this->form_validation->set_rules('correon','E-mail','required');
            $this->form_validation->set_rules('teln','Phone','required');
            $this->form_validation->set_rules('usern','User','required');
            $this->form_validation->set_rules('pwdn','Password','required');
            if($this->form_validation->run() === FALSE)
            {
                $data['clientes'] = $this->clientes_model->get_clientes($cliente);
                $this->load->view('templates/header',$data);
                $this->load->view('templates/logo');
                $this->load->view('clientes/new_contact',$data);
            }
            else
            {
                $this->clientes_model->add_contact();
                redirect('clientes');
            }
            $this->load->view('templates/footer');		
	}		
        /*Permite modificar los comentarios hechos por el cliente a un proyecto dado en una nueva ventana*/
        public function notes()
        {
		$data['title'] = 'Comentarios ';
		$this->form_validation->set_rules('notesc','Comentarios','required');
		if($this->form_validation->run() === FALSE)
		{
		$data['project'] = $this->clientes_model->get_comments();
		$this->load->view('templates/header',$data);
		$this->load->view('templates/logo');
		$this->load->view('clientes/notes',$data);
		}
		else
		{
		$this->clientes_model->save_notes();
		$data['title'] = 'Comentarios guardados!';
		$this->load->view('templates/header',$data);
		$this->load->view('templates/logo');
		echo "<h1>".$data['title']."</h1>";
		$this->load->view('templates/footer');
		}		
	}
        /*Guarda los comentarios hechos por el cliente a un video dado*/
        public function savecomments()
	{
            $id = $this->input->get('info1');
            $comments = $this->input->get('info2');
            $this->clientes_model->save_custcom($id,$comments);
            $type = $this->session->userdata('type');
            if ($type === 'admin')
            {
                echo "Comments saved!";
            }
            else
            {
                $version = substr($this->input->get('info3'), 0, -4);
                $project = $this->input->get('info4');
                $this->load->model('version_model');
                $productora = $this->version_model->get_productora($project);	 
                $this->email->from($this->session->userdata('correo'),$productora);
                $this->email->to('camilo.a@bygstudio.com,eduardo.a@bygstudio.com,luisa.h@bygstudio.com'); 
                $this->email->bcc('rafael@bygstudio.com');
                $this->email->subject('Comentarios '.$version. ' proyecto '. $project);
                $this->email->message($comments);
                if (!$this->email->send())
                {
                        echo $this->email->print_debugger(); 
                }
                else echo "Comentarios enviados!";
            }
	}
        /* Envía email con enlace(s) de descarga de última(s) versión(es) de proyecto */
	public function sendmail()
	{
		$n = 1;
		while ($this->session->userdata('correo'.$n))
		{
			$data['correo'.$n] = $this->session->userdata('correo'.$n);
			$n++;
		}
		$data['count'] = $n-1;
		$data['signed'] =$this->session->userdata('name');
		$data['file'] = $this->session->userdata('file_name');
                $m = (int) $this->session->userdata('files_count');
                if ($m > 1)
                {                    
                    $data['files_count'] = $m;
                    for ($i=0; $i<$m; $i++)
                    {
                       $data['file' . $i] = $this->session->userdata('file' . $i);  
                    }
                }
		$data['project'] = $this->session->userdata('proyecto');
		$data['secure'] = $this->session->userdata('secure');
		$this->form_validation->set_rules('correos','Destinatario(s)','required');
		$this->form_validation->set_rules('subject','Asunto','required');
		$this->form_validation->set_rules('texto','Texto','required');
		if($this->form_validation->run() === FALSE)
		{
                    $data['title'] = 'Env&iacute;o de correo ';
                    $this->load->view('templates/header',$data);
                    $this->load->view('templates/logo');
                    $this->load->view('clientes/texto_mail',$data);
		}
		else
		{
                    $data['title'] = 'Correo enviado!';
                    $this->email->from($this->session->userdata('correo'), $data['signed']);
                    $this->email->to($this->input->post('correos'));
                    $this->email->cc($this->session->userdata('correo')); //Copia del correo al usuario quien lo  envia
                    $this->email->bcc('rafael@bygstudio.com, luisa.h@bygstudio.com');
                    $this->email->subject($this->input->post('subject'));
                    $this->email->message($this->input->post('texto'));
                    if (!$this->email->send())
                    {
                    /*$data['title'] = "Error, correo NO enviado por favor verifique datos y conexion a Internet";// Generate error
                    $this->load->view('templates/header',$data);
                    $this->load->view('templates/logo');
                    $this->load->view('clientes/texto_mail');*/
                            echo $this->email->print_debugger();
                    }
                    else 
                    {
                        $this->load->view('templates/header',$data);
                        $this->load->view('templates/logo');
                        $this->load->view('clientes/exito');
                    }
		}
		$this->load->view('templates/footer');
	}
        //Sets a given feature (visibility, name) for a given clip category.
        public function setCategoryFeature()
        {
           $checkboxId = $this->input->get('checkbox_id');
           $name = false;
           if (strpos($checkboxId, 'name_') !== false && $this->input->get('name') !== '')
           {
               $name = $this->input->get('name');
           }
           $categoryFeatureUpdate = $this->clientes_model->set_categoryFeature($checkboxId, $name);
           echo $categoryFeatureUpdate;
        }
        //Sets a given feature (visibility, banner, etc.) for a given clip.
        public function setClipFeature()
        {
           $checkboxId = $this->input->get('checkbox_id');
           $title = false;
           if (strpos($checkboxId, 'title_') !== false && $this->input->get('title') !== '')
           {
               $title = $this->input->get('title');
           }
           $clipFeatureUpdate = $this->clientes_model->set_clipFeature($checkboxId, $title);
           echo $clipFeatureUpdate;
        }
        /*Muestra el listado de proyectos */	
	public function showprojects ()
	{
		$type = $this->session->userdata('type');
		$data['title'] = 'Proyectos ';
		$data['projects'] = $this->clientes_model->get_projects();
		$this->load->view('templates/header',$data);
		if ($type === 'admin')
		{
                    $this->load->view('templates/menu_admin');
                    $this->load->view('templates/logo');
                    $this->load->view('clientes/show_projects',$data);	
		}
		else 
		{
                    $this->load->view('templates/menu_clientes');
                    $this->load->view('templates/logo');
                    $this->load->view('clientes/show_proyectos',$data);	
		}	
		$this->load->view('templates/footer');
	}
        /*Muestra la pagina para subir los archivos y los documentos dejados allí son cargados en el sistema de archivos dentro de la carpeta uploads/"proyecto" */
	public function subirdocs ()
	{
		$trans = array('aQ'=>'á','eQ'=>'é','iQ'=>'í','oQ'=>'ó','uQ'=>'ú','_'=>' ');
		$data['title'] = strtr($this->uri->segment(2),$trans);
		$type = $this->session->userdata('type');
		if (empty($_FILES)) 
		{
		$this->load->view('templates/header_upload',$data);
		$this->load->view('templates/menu_admin');
		$this->load->view('templates/logo');
		$this->load->view('clientes/subirdocs',$data);
		$this->load->view('templates/footer');	
		}
		else
			if ($_FILES['file']['size'] > 0)
			{
			$this->clientes_model->upload_docs();
			}
	}
        /*Actualiza el orden de los clips/trailers en la base de datos para un tipo particular, si hay que borrar alguno, borra también el archivo*/
	public function updateclips()
	{
		$this->clientes_model->update_clips();
	}
        /*Muestra la pagina para subir los archivos de video y los videos dejados alli son cargados en el sistema de archivos dentro de la carpeta uploads/"proyecto" */
	public function upload ()
	{
            $trans = array('aQ'=>'á','eQ'=>'é','iQ'=>'í','oQ'=>'ó','uQ'=>'ú','_'=>' ');

            if ($this->uri->segment(2) === 'secure')
            {
                    $data['title'] = strtr($this->uri->segment(3). ' (subida segura)',$trans);
                    $data['message'] = '<p style="color:#FF0000">Nota: Para ver el(los) video(s), se solicita usuario y contraseña 
                    (auto-generada y enviada con el link). Sino desea esto, cierre y súbalo(s) del modo convencional.</p>';
            }
            else
            {
                    $data['title'] = strtr($this->uri->segment(2),$trans);
                    $data['message'] = '';
            }

            $type = $this->session->userdata('type');
            if (empty($_FILES) && $this->input->get('mailinfo') == "") 
            {
                    if ($this->input->get('info') == "")
                    {
                            $this->load->view('templates/header_upload',$data);
                            if ($type === 'admin')
                            {
                                    $this->load->view('templates/logo');
                                    $this->load->view('clientes/upload',$data);
                            }
                            $this->load->view('templates/footer');
                    }
                    else echo $this->clientes_model->existing_video();
            }
            else
            {
                    if ($this->input->get('mailinfo') == "")
                    {
                            $this->clientes_model->upload_video();
                    }
                    else 
                    {
                        $filesArray = json_decode($this->input->get('filesarray'), TRUE);
                        $filesArray['files_count'] = count($filesArray);
                        $this->session->set_userdata($filesArray);
                        $atts = array(
                            'class' => 'mailtemplate',
                            'width'      => '\'+window.innerWidth*0.5+\'',
                            'height'     => '\'+window.innerHeight+\'',
                            'scrollbars' => 'no',
                            'screenx'    => '\'+((parseInt(screen.width) - window.innerWidth*0.5)/2)+\'',
                            'screeny'    => '\'+((parseInt(screen.height) - window.innerHeight)/2)+\''
                        );
                        echo anchor_popup('sendmail','Enviar link(URL) de esta versión',$atts);
                    }                            
            }
	}
        /*Sube el nuevo clip al sistema de archivos y actualiza en la base de datos la tabla byg_clips. */
	public function uploadclip()
	{
            $type = $this->session->userdata('type');
            if (empty($_FILES) && ! $this->input->get('tipo')) 
            {
                $data['title'] = $this->uri->segment(2);
                $this->load->view('templates/header_upload',$data);
                $this->load->view('templates/logo');
                if ( ! $this->input->get('fname'))
                {	
                    if ($type === 'admin')
                    {
                        $this->load->view('clientes/uploadclip',$data);
                    }
                }
                else echo $this->clientes_model->existing_clip(); //Added clip, verifies if it was loaded before
                $this->load->view('templates/footer');
            }
            else
            {
                if ( ! $this->input->get('tipo'))
                {
                    $filesNumber = $this->clientes_model->upload_clip();
                    if ($filesNumber > 0)
                    {
                        $this->session->set_userdata('files_number', '1');
                    }
                }
                else 
                {
                    $data['key'] = $this->input->get('tipo');
                    $data['clips'] = $this->clientes_model->get_clips($data['key']);
                    $clipIndex = count($data['clips']) - 1;
                    if (substr($this->input->get('ftype'), 0, 5) === 'image')
                    {
                        $newClipInfo = $this->load->view('clientes/new_clip', $data, TRUE);
                        if ($this->session->userdata('files_number'))
                        {
                           $newClipInfo .= '<p id="new-paragraph" style="display:none">El nuevo clip</p>';
                           $this->session->unset_userdata('files_number');
                        }
                        echo $newClipInfo;
                    }
                    elseif ($this->session->userdata('files_number'))
                    {
                        $this->session->unset_userdata('files_number');
                        echo 'El nuevo clip "'. ucwords($data['clips'][$clipIndex]['title']) . '" ha sido cargado en el servidor!';
                    }
                    else
                    {
                        echo 'Falta subir el still HD, que acompaña al clip! Favor hacerlo antes de salir de sesión.';
                    }                        
                }
            }		
	}
        /*Sube el thumbnail del video que se ha cargado en el sistema de archivos, a la carpeta uploads/"proyecto"/thumbnails */
	public function uploadstill ()
	{
		$this->clientes_model->upload_still();
	}
        /*Muestra el listado de versiones para un proyecto dado */	
	public function versions ()
	{
		$trans = array('aQ'=>'á','eQ'=>'é','iQ'=>'í','oQ'=>'ó','uQ'=>'ú','_'=>' ');
		$data['title'] = strtr($this->uri->segment(2),$trans);
		$project = $data['title'];
		$data['versions'] = $this->clientes_model->get_versions($project);
		$config['base_url'] = site_url('versions/'.$this->uri->segment(2));
		$config['total_rows'] = $data['versions']['countrow'];
		$data['versions']['total_rows']  = $config['total_rows'];
		$config['per_page'] = 10; 
		$config['uri_segment'] = 3;
		$config['num_links'] = 12;
		$config['use_page_numbers'] = TRUE;
		$this->pagination->initialize($config); 
		$data['pages'] = $this->pagination->create_links();
		$data['page'] = $this->uri->segment(3);
		$data['type'] = $this->session->userdata('type');
		$this->load->view('templates/header_video',$data);
		if ($data['type'] === 'admin')
		{
			$this->load->view('templates/menu_admin');
		}
		else 
		{
			$this->load->view('templates/menu_clientes');
		}
		$this->load->view('templates/logo');	
		$this->load->view('clientes/show_versions',$data);
		$this->load->view('templates/footer');
	}
        
        public function test($cliente = FALSE, $order = FALSE)
        {
            $trans = array('aQ'=>'á','eQ'=>'é','iQ'=>'í','oQ'=>'ó','uQ'=>'ú','_'=>' ');
            $data['title'] = strtr('DAILIES_DIA_17',$trans);
            $project = $data['title'];
            $data['versions'] = $this->clientes_model->get_versions($project);
            $config['base_url'] = site_url('versions/'.$this->uri->segment(2));
            $config['total_rows'] = $data['versions']['countrow'];
            $data['versions']['total_rows']  = $config['total_rows'];
            $config['per_page'] = 10; 
            $config['uri_segment'] = 3;
            $config['num_links'] = 12;
            $config['use_page_numbers'] = TRUE;
            $this->pagination->initialize($config); 
            $data['pages'] = $this->pagination->create_links();
            $data['page'] = $this->uri->segment(3);
            $data['type'] = $this->session->userdata('type');
            $this->load->view('templates/header_video',$data);
            $this->load->view('templates/logo');	
            $this->load->view('clientes/show_versions_scroll',$data);
            $this->load->view('templates/footer');
        }
}