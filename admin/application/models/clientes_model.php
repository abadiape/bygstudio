<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Clientes_model extends CI_Model {

	public function __construct()
	{
            parent::__construct();
            $this->load->database();
            $this->load->dbforge();
            $this->load->helper('date');
            $this->load->helper('file');
            $this->load->helper('path');	
	}
        
        public function add_clip_category($name, $visible, $order)
        {
            $this->db->select('name')->from('clip_categories')->where('name', $name);
            if ($this->db->count_all_results() > 0)
            {
                echo 'El nombre de la categoría ya existe!, use otro nombre o agregue el clip en la ya existente.';
                return;
            }
            $createdAt = date('Y-m-d H:i:s');
            $user = $this->session->userdata('user');
            $code = str_replace(' ', '', $name);
            $code = substr(md5($name), 0, 12);
            $path = 'byg_clips/' . $code;
            $data = array(
                'code' => $code,
                'name' => $name,
                'path' => $path,
                'category_order' => $order,
                'visible' => $visible,
                'created_at' => $createdAt,
                'by_user' => $user
             );
            $this->db->insert('clip_categories', $data);
            $this->db->select('category_id')->from('clip_categories')->where('code', $code);
            $query = $this->db->get();
            $row = $query->row();
            $info['id'] = $row->category_id;
            $info['code'] = $code;
            return $info;
        }
        
	public function add_contact()
	{
            $cliente = $this->input->post('nameclient');
            $contact = $this->input->post('contactn');
            $password = $this->input->post('pwdn');
            $query= $this->db->get_where('customers_users',array('cliente'=>$cliente, 'contact' => $contact));
            $row = $query->row_array();
            if (! empty($row['contact']) && $row['contact'] === $this->input->post('contactn'))
            {
                $existe = TRUE;	 	
                return $existe;	
            }
            $data = array(
                        'cliente' => $cliente,
                        'contact' => $contact,
                        'correo' => $this->input->post('correon'),
                        'tel' => $this->input->post('teln'),
                        'user' => $this->input->post('usern'),
                        'pwd' => md5('byg' . $password).':byg'					 
                    );                        
            $this->db->insert('customers_users',$data);
            return $data;		
	}
        
	public function buscar_dato($busq,$user)
	{
            if ($this->input->post("pageppal") === "Productoras")
            {
                /*$this->db->like('cliente',$busq);
                $this->db->or_like('rfc',$busq);
                $query1 = $this->db->get('datos_clientes');*/
                $this->db->like('cliente',$busq);
                $this->db->or_like('correo1',$busq);
                $this->db->or_like('tel1',$busq);
                $this->db->or_like('contact1',$busq);
                $query2 = $this->db->get('contactos_clientes');
                $n = $query2->num_rows();
                $campos = $query2->num_fields();
                //Numero de contactos a mostrar en pantalla
                $m = intval($campos - (4 + 2*(intval($campos/5)-1)))/3; 
                $query = $query2->result_array();
                $clientes = array();
                foreach ($query as $query_item)
                {
                    $clientes[ ] = $query_item['cliente'];	
                }
                $info = array(); 
                for ($i=0;$i<$n;$i++)
                {
                    $query1 = $this->db->get_where('datos_clientes',array('cliente' => $clientes[$i]));	
                    $info[ ] = ($query1->row_array($i) + $query2->row_array($i));
                }
                $info['m'] = $m;
                return $info;
            }
            else
            {
                if ($user === 'client')
                {
                        $query = $this->db->get('contactos_clientes');
                        $campos = $query->num_fields();
                        //Numero de campos de usuario de cliente existentes
                        $m = intval($campos - (4 + 2*(intval($campos/5)-1)))/3; 
                        $query->free_result();
                        for ($i=1;$i<=$m;$i++)
                        {
                                $this->db->or_having('user'.$i,$this->session->userdata('user'));	
                        }
                        $query = $this->db->get('contactos_clientes');
                        $cliente = $query->row();
                        $query->free_result();	
                        $this->db->having('cliente',$cliente->cliente);
                }
                $query = $this->db->get('proyectos_clientes');
                $n = $query->num_rows();
                $i = 1;
                $proyectos = array();
                foreach ($query->result_array() as $row)
                {
                        $proyectos[$i] = $row['project'];
                        $i++;
                }
                for ($i=1;$i<=$n;$i++)
                {
                        $this->db->or_having('proyecto',$proyectos[$i]);
                }
                $this->db->like('proyecto',$busq);
                $this->db->or_like('file_name',$busq);
                $query = $this->db->get('videos_clientes');
            }
            return $query->result_array();
	}
        
	public function cambio_cliente($id)
	{		
            $data = array(
                    'cliente' => $this->input->post('cliente'),
                    'rfc' => $this->input->post('rfc'),
                    'correo' => $this->input->post('correo'),
                    'tel' => $this->input->post('tel'),
                    'contact' => $this->input->post('contact'),
                    'address' => $this->input->post('address'),
            );		
            $editar = 'editar'.$id;
            $comp = $this->input->post('editar'.$id);
            $borrar = 'borrar'.$id;
            $del = $this->input->post('borrar'.$id);	
            if ($comp === 'true')
            {					
                $this->db->where('id',$id);
                $this->db->update('datos_clientes',$data);
                $data['title'] = 'Cliente modificado';	
            }
            else
            {		
                $data['title'] = 'Cliente NO modificado';
            }

            if ($del === 'true') 
            {		
                $data['title'] = 'Cliente borrado';
                $this->db->delete('datos_clientes', array('id' => $id));
            }						
	}
        
        public function change_categoryOrder($categoryId,$newValue)
        {
            $query = $this->db->get_where('clip_categories', array('category_id' => $categoryId));
            $row = $query->row();
            $formerValue = $row->category_order;
            $query = $this->db->get_where('clip_categories', array('category_order' => $newValue));
            $row = $query->row();
            $presentOrderSelectId = 'position_' . $row->code . '_' .$row->category_id;
            $this->db->where('category_id', $categoryId);
            $this->db->update('clip_categories', array('category_order' => $newValue));
            $this->db->where('category_id', $row->category_id);
            $this->db->update('clip_categories', array('category_order' => $formerValue));
            return array($presentOrderSelectId, $formerValue);
        }
        
        public function clip_delete($type, $listOrder)
        {
            $query = $this->db->get_where('byg_clips', array('type' => $type, 'list_order' => $listOrder));  
            $row = $query->row();
            $imageName = str_replace(' ', '_', $row->img_name);
            $videoName = trim($row->fname);
            $clipId = $row->id;
            $title = ucwords($row->title);
            if (! $videoName)
            {
                $videoName = $imageName . '.mp4';
                $imageName = $imageName . '.jpg';
            }
            else
            {
                $videoName = str_replace(' ', '_', $videoName);
            }
            $query = $this->db->get_where('clip_categories', array('code' => $type));
            $row = $query->row();
            $clipFiles = array();
            if ($row->name !== $row->code)
            {
                $dir = realpath(APPPATH . '../uploads/' . $row->path);
                $clipFiles[0] = $dir . '/images/' . $imageName;
                $clipFiles[1] = $dir . '/videos/' . $videoName;
            }
            else
            {
                $dir = realpath(FCPATH . '../video/' . $row->path);
                $clipFiles[0] = $dir . '/' . $imageName;
                $clipFiles[1] = $dir . '/' .$videoName;
            }
            $n = 0;
            foreach ($clipFiles as $clipFile)
            {
                if (is_file($clipFile))
                {
                    unlink($clipFile);
                    $n++;
                    if ($n === 1)
                    {
                        $this->db->where('id', $clipId);
                        $this->db->delete('byg_clips');
                    }
                    else
                    {
                        return $title;
                    }
                }
            }
            return 0;
        }

        public function del_contact($conta,$idi)
	{
            $pos = strpos($idi,'i');
            $i = substr($idi,($pos+1));		
            $this->db->where('contact',$conta);	
            return $this->db->delete('customers_users', array('contact' => $conta));					
	}
        
        public function del_customer($id)
        {
           if ($this->db->delete('datos_clientes', array('id' =>  $id)))
                return TRUE;
           else
               return false;
        }
	
	public function del_project($project)
	{		
            $trans = array('á'=>'aQ','é'=>'eQ','í'=>'iQ','ó'=>'oQ','ú'=>'uQ',' '=>'_');
            $proyecto = strtr($project,$trans);
            if (is_dir('uploads/'.$proyecto))
            {		
                $this->db->delete('proyectos_clientes', array('project' =>  $project));
                delete_files('uploads/'.$proyecto, TRUE); //CI borra los subdirectorios y archivos dentro del directorio de proyecto 
                rmdir('uploads/'.$proyecto); //Borra el directorio vacio del proyecto
                return TRUE;
            }
            return FALSE;					
	}	
	
	public function del_video($file,$id)
	{		
            if (is_file($file))
            {
                unlink($file);
                $this->db->delete('videos_clientes', array('id' => $id));
                return TRUE;
            }
            return FALSE;					
	}
	
	public function existing_clip()
	{
            $fname = $this->input->get('fname');
            $fname = substr($fname,0,-4);
            $this->db->like('fname', $fname, 'after');
            $this->db->from('byg_clips');
            return $this->db->count_all_results();
	}
	
	public function existing_video($fname = NULL, $project = NULL)
	{
            if ( ! $fname)
            {
               $fname = $this->input->get('info');
               $project = $this->uri->segment(2); 
            }
            $trans = array('aQ'=>'á','eQ'=>'é','iQ'=>'í','oQ'=>'ó','uQ'=>'ú','_'=>' ');
            $proyecto = strtr($project,$trans);
            $this->db->like('file_name',$fname);
            $this->db->from('videos_clientes');
            return $this->db->count_all_results();
	}
        
	public function get_clientes($cliente, $order=NULL)
	{
            if ( ! $cliente)
            {
                $this->db->order_by("cliente","asc");
                $query1 = $this->db->get('datos_clientes');
                $n = $query1->num_rows();
                $query = $this->db->get('customers_users');
                $campos = $query->num_fields();
                //Numero de contactos a mostrar en pantalla
                $m = intval($campos - (4 + 2*(intval($campos/5)-1)))/3; 			
                $this->db->order_by("cliente","asc"); 		
                //$query2 = $this->db->get('customers_users');
                $info = array(); 
                for ($i=0;$i<$n;$i++)
                {
                    $row1 = $query1->row_array($i);
                    $projects = $this->db->get_where('proyectos_clientes', array('cliente' => $row1['cliente']));
                    $users = $this->db->get_where('customers_users', array('cliente' => $row1['cliente']));
                    $info[ ] = ($row1 + array('contactos' => $users->result_array()) + array('numprojects' => $projects->num_rows()));
                }
                $info['m'] = $m;
                //Se coloca aquí el campo con su valor del criterio primario que se quiere emplear para borrar videos
                $query = $this->db->get_where('videos_clientes',array('proyecto' => 'LHDN300'));
                if ($query->num_rows() > 40) //Solo se borra si hay más de 40 que cumplan el criterio
                {
                    $lhdn = $query->result_array();
                    $time = time();
                    $trans = array('á'=>'aQ','é'=>'eQ','í'=>'iQ','ó'=>'oQ','ú'=>'uQ',' '=>'_');
                    $this->ftp->connect();
                    foreach ($lhdn as $value)
                    {
                        $date = strtotime($value['date']);
                        if (substr($value['date'],0,9) === 'Wednesday')
                             $date = strtotime($value['date'].'C');
                        $project = $value['proyecto'];
                        $res = $time - $date;
                        if ($res > 1296000) //Solo se borran de más de 15 días de subidos
                        {
                            $fname = 'uploads/'.$project.'/'.strtr($value['file_name'],$trans);
                            if (file_exists($fname))
                            {
                                $fname = '/'.$project.'/'.strtr($value['file_name'],$trans);
                                if ($this->ftp->delete_file($fname))
                                {
                                    $this->db->where('date',$value['date']);
                                    $this->db->delete('videos_clientes');
                                }
                          }
                        }
                    }
                    $this->ftp->close();
                }
                return $info;			
            }
            else
            {
                $info = array();
                if ($this->uri->segment(2))
                {
                    $query1= $this->db->get_where('datos_clientes',array('id' => $cliente));
                    return $query1->row_array();
                }
                else $query1= $this->db->get_where('datos_clientes',array('cliente' => $cliente));
                $query2= $this->db->get_where('customers_users',array('cliente' => $cliente));
                $info[ ] = ($query1->row_array() + $query2->row_array());
                return $info;
            }
	}
        
	public function get_clips($type = false, $order = false)
	{
            $this->db->select('*');
            $this->db->order_by('type, list_order');
            if (! $type)
            {
               $this->db->from('clip_categories');
               $this->db->join('byg_clips', 'clip_categories.code = byg_clips.type');
               $query = $this->db->get();
               $info = $query->result_array();
            }
            else
            {
                if (! $order)
                {
                    $query =  $this->db->get_where('byg_clips', array('type' => $type));
                    $info = $query->result_array();
                }
                else
                {
                    $query =  $this->db->get_where('byg_clips', array('type' => $type, 'list_order' => $order));
                    $info = $query->row();
                }
            }           
            return $info;
	}
        
	public function get_comments()
	{
            $proyecto = $this->uri->segment(2);
            $query= $this->db->get_where('proyectos_clientes', array('project' => $proyecto));
            return $query->row_array();
	}
        
	public function get_projects()
	{
            if ($this->uri->segment(2))
                $id = $this->uri->segment(2);
            else $id = $this->input->get('id');	
            if ($this->session->userdata('type') === 'client')
            {
                $id = $this->session->userdata('userid');
                $query = $this->db->get_where('customers_users',array('id' => $id));
            }
            else 
            { 
                $query = $this->db->get_where('datos_clientes',array('id' => $id));
            }            	
            $info['datos'] = $query->row_array();
            $cliente = $info['datos']['cliente'];
            $query->free_result();
            $info['cliente'] = $cliente;
            $info['id'] = $id; //Retroalimentacion del "id" cuando se borran proyectos y no esta disponible a traves del URI
            $this->db->select('project');
            $this->db->order_by('id', 'desc');
            $query = $this->db->get_where('proyectos_clientes', array('cliente' => $cliente));
            $n = $query->num_rows();
            $numvideos = array();
            for ($i=0;$i<$n;$i++)
            {
            	$row = $query->row_array($i);
            	$versions = $this->db->get_where('videos_clientes', array('proyecto' => $row['project']));
            	$numvideos[ ] = $versions->num_rows();
            }
            $info['data'] = $query->result_array();
            $info['numversions'] = $numvideos;
            return $info;
	}
        
	public function get_versions ($project)
	{
            $this->db->order_by('id','desc');
            $query= $this->db->get_where('videos_clientes',array('proyecto'=>$project));
            $info['countrow']= $query->num_rows();
            $info['videos'] = $query->result_array();
            $trans = array('á'=>'aQ','é'=>'eQ','í'=>'iQ','ó'=>'oQ','ú'=>'uQ',' '=>'_');
            for ($i=0; $i < $info['countrow']; $i++)
            {
                $fname = 'uploads/'.strtr($project, $trans).'/'.strtr($info['videos'][$i]['file_name'],$trans);
                if ( ! file_exists($fname))
                {
                   $info['videos'][$i]['file_name'] = 'secure/' . $info['videos'][$i]['file_name']; 
                }
            }
            return $info;
	}
        
	public function save_comments ()
	{
            $proyecto = $this->input->post('projectC');
            $comments = $this->input->post('modify');
            $this->db->where('project',$proyecto);
            return $this->db->update('proyectos_clientes',array('anotaciones' => $comments));
	}
        
	public function save_custcom($id,$comments)
	{
            $this->db->where('id',$id);
            return $this->db->update('videos_clientes',array('comments' => $comments));
	}
        
	public function save_notes ()
	{
            $proyecto = $this->input->post('proyectoC');
            $comments = $this->input->post('notesc');
            $this->db->where('project',$proyecto);
            return $this->db->update('proyectos_clientes',array('notesc' => $comments));
	}
        
	public function set_clientes()
	{				
            $campos = array(
                     'cliente' => array(
                                          'type' => 'VARCHAR',
                                          'constraint' => '32', 
                                          'null' => FALSE
                                       ),
                     'rfc' => array(
                                          'type' => 'VARCHAR',
                                          'constraint' => '16',
                                          'null' => FALSE
                                       ),
                     'address' => array(
                                          'type' => 'VARCHAR',
                                          'constraint' => '64', 
                                          'null' => FALSE
                                       ),
                     'tel' => array(	  	  
                                            'type' =>'VARCHAR',
                                            'constraint' => '16',
                                            'null' => FALSE,
                                       ),
                     'date' =>  array(
                                          'type' => 'VARCHAR',
                                          'constraint' => '32',
                                          'null' => FALSE
                                       )                                                    
             );
            $this->dbforge->add_field($campos);
            $this->dbforge->add_field('id');
            $this->dbforge->add_key('cliente');
            $this->dbforge->create_table('datos_clientes',TRUE);
            $contact = array(
                     'cliente' => array(
                                              'type' => 'VARCHAR',
                                              'constraint' => '32', 
                                              'null' => FALSE
                                       ),
                     'contact' => array(
                                              'type' => 'VARCHAR',
                                              'constraint' => '32', 
                                              'null' => FALSE
                                       ),
                     'correo' => array(
                                              'type' =>'VARCHAR',
                                              'constraint' => '32',
                                              'null' => FALSE
                                       ),
                     'tel' => array(          'type' =>'VARCHAR',
                                              'constraint' => '16',
                                              'null' => FALSE,
                                       ),
                     'user' => array(
                                              'type' => 'VARCHAR',
                                              'constraint' => '16', 
                                              'null' => FALSE
                                       ),
                     'pwd' => array(
                                              'type' => 'VARCHAR',
                                              'constraint' => '48', 
                                              'null' => FALSE
                                       )                                                    
             );
            $this->dbforge->add_field($contact);
            $this->dbforge->add_field('id');
            $this->dbforge->add_key('cliente');
            $this->dbforge->create_table('customers_users',TRUE);
            $format = 'DATE_COOKIE';
            $time = time();
            $date = standard_date($format,$time);
            $data = array(
                    'cliente' => $this->input->post('cliente'),
                    'rfc' => $this->input->post('rfc'),
                    'address' => $this->input->post('address'),
                    'tel' => $this->input->post('tel'),
                    'date' => $date
            );
            $datos =  array(
                    'cliente' => $this->input->post('cliente'),
                    'contact' => $this->input->post('contact1'),
                    'correo' => $this->input->post('correo1'),
                    'tel' => $this->input->post('tel1'),
                    'user' => $this->input->post('user1'),
                    'pwd' => md5('byg' . $this->input->post('pwd1')). ':byg'
            );
            $this->db->insert('datos_clientes',$data);				
            return $this->db->insert('customers_users',$datos);				
	}
        
        public function set_categoryFeature($checkboxId, $name = false) 
        {
            $underscorePos = strpos($checkboxId, '_');
            $categoryFeature = substr($checkboxId, 0, $underscorePos);
            $categoryPlusOrder = substr($checkboxId, $underscorePos+1);
            $lastUnderscorePos = strpos($categoryPlusOrder, '_');
            $formerCategory = substr($categoryPlusOrder, 0, $lastUnderscorePos);
            $categoryId = substr($categoryPlusOrder, $lastUnderscorePos+1);
            $query = $this->db->get_where('clip_categories', array('category_id' => $categoryId));
            $row = $query->row();
            $featureValue = (int) $row->$categoryFeature;
            $this->db->where('category_id', $categoryId);
            if (! $name && $featureValue === 1)
            {
               $this->db->update('clip_categories', array($categoryFeature => 0)); 
               return 0;
            }
            else
            {
               if (! $name && $featureValue === 0)
               {
                  $this->db->update('clip_categories', array($categoryFeature => 1)); 
                  return 1; 
               }
               else
               {
                  $this->db->update('clip_categories', array($categoryFeature => strtolower($name))); 
                  return $name; 
               }               
            }
        }
        
        public function set_clipFeature($checkboxId, $title = false) 
        {
            $underscorePos = strpos($checkboxId, '_');
            $clipFeature = substr($checkboxId, 0, $underscorePos);
            if ($clipFeature === 'banner')
            {
                $clipFeature = 'in_banner';
            }
            $suffix = substr($checkboxId, $underscorePos+1);
            $dashPos = strpos($suffix, '-');
            $clip = array('type' => substr($suffix, 0, $dashPos), 'list_order' => substr($suffix, $dashPos + 1));
            $query = $this->db->get_where('byg_clips', $clip);
            $row = $query->row();
            $featureValue = (int) $row->$clipFeature;
            $this->db->where($clip);
            if (! $title && $featureValue === 1)
            {
               $this->db->update('byg_clips', array($clipFeature => 0)); 
               if ($clipFeature === 'visibility')
               {
                  $this->db->update('byg_clips', array('in_banner' => 0)); 
               }
               return 0;
            }
            else
            {
               if (! $title && $featureValue === 0)
               {
                  $this->db->update('byg_clips', array($clipFeature => 1)); 
                  return 1; 
               }
               else
               {
                  $this->db->update('byg_clips', array($clipFeature => $title)); 
                  return $title; 
               }               
            }
        }
        
	public function set_proyecto()
	{
            $fields = array(
                     'cliente' => array(
                                              'type' => 'VARCHAR',
                                              'constraint' => '32', 
                                              'null' => FALSE
                                       ),
                     'project' => array(
                                              'type' => 'VARCHAR',
                                              'constraint' => '32',
                                              'null' => FALSE
                                       ),
                     'date' => array(
                                              'type' =>'VARCHAR',
                                              'constraint' => '32',
                                              'null' => FALSE
                                       ),
                     'anotaciones' => array(
                                              'type' =>'TEXT',
                                              'constraint' => '1024',
                                              'null' => FALSE
                                       ),
                     'notesc' => array(
                                              'type' =>'TEXT',
                                              'constraint' => '1024',
                                              'null' => FALSE
                                       )
                       );
            $this->dbforge->add_field($fields);
            $this->dbforge->add_field('id');
            $this->dbforge->add_key('project');
            $this->dbforge->create_table('proyectos_clientes',TRUE);
            $format = 'DATE_COOKIE';
            $time = time();
            $date = standard_date($format,$time);
            $productora = $this->input->post('productora');
            $project = $this->input->post('project');	
            $query= $this->db->get_where('datos_clientes',array('id'=>$productora));
            $row = $query->row_array();
            $datos = array(
                            'cliente' => $row['cliente'],
                            'project' => $project,
                            'date' => $date,
                            'anotaciones' => $this->input->post('apuntes'),
                            'notesc' => $this->input->post('notesc')
                    );
            $this->ftp->connect();
            $trans = array('á'=>'aQ','é'=>'eQ','í'=>'iQ','ó'=>'oQ','ú'=>'uQ',' '=>'_');
            $dir = strtr($project,$trans);
            $this->ftp->mkdir($dir, DIR_WRITE_MODE);
            $this->ftp->mkdir($dir.'/docs', DIR_WRITE_MODE);
            $this->ftp->mkdir($dir.'/thumbnails', DIR_WRITE_MODE);
            $this->ftp->close();
            $this->db->insert('proyectos_clientes',$datos);
            $query= $this->db->get_where('proyectos_clientes',array('cliente'=>$datos['cliente']));
            $info['data'] = $query->result_array();
            $info['cliente'] = $datos['cliente'];
            $info['id'] = $row['id'];
            return $info; 	
        }
        
	public function update_clips()
	{
            $tipo = $this->input->get('tipo'); 
            $origen = $this->input->get('origen');
            $destino = $this->input->get('destino');
            $filetodel = realpath(FCPATH.'../'.substr($this->input->get('file'), 21)); 
            $fields = array('type' => $tipo, 'list_order' => $origen);
            $query = $this->db->get_where('byg_clips',$fields);
            $result = $query->row_array();
            $source = array('fname' => $result['fname'], 'fsize' => $result['fsize'], 'ftype' => $result['ftype'],
                     'date' => $result['date'], 'img_name' => $result['img_name']);
            $query->free_result();
            if ($destino)
            {			
                $fields = array('type' => $tipo, 'list_order' => $destino);
                $query = $this->db->get_where('byg_clips',$fields);
                $result = $query->row_array();
                $destino = array('fname' => $result['fname'], 'fsize' => $result['fsize'], 'ftype' => $result['ftype'],
                 'date' => $result['date'], 'img_name' => $result['img_name']);
                $query->free_result();
                $this->db->where($fields);
                $this->db->update('byg_clips', $source);
                $query->free_result();
                $fields = array('type' => $tipo, 'list_order' => $origen);
                $this->db->where($fields);
                $this->db->update('byg_clips', $destino);		
            }
            else
            {
                if (is_file($filetodel))
                {
                        unlink($filetodel);
                        if ($source['fname'] == '')
                        {
                                $filetodel = substr($filetodel,0,-3).'mp4';
                        }
                        else
                        {
                                $filetodel = strtr($source['fname'], array(' ' => '_'));
                        }
                        unlink($filetodel);
                        $this->db->where($fields);
                        $this->db->delete('byg_clips');
                }
            }
            return;
	}
		
	public function update_contact()
	{
            $idi = $this->input->post('idi');
            $pos = strpos($idi,'i');
            $id = substr($idi,0,$pos);
            $data = array(
                            'contact' => $this->input->post('contedit'),
                            'correo' => $this->input->post('mailedit'),
                            'tel' => $this->input->post('phonedit')
                    );	
            $this->db->where('id',$id);
            $this->db->update('customers_users',$data);					
	}
	
	public function update_data()
	{
            $id = $this->input->post('id');
            $data = array(
                            'rfc' => $this->input->post('rfcedit'),
                            'address' => $this->input->post('addedit'),
                            'tel' => $this->input->post('teledit')
                    );	
            $this->db->where('id',$id);
            $this->db->update('datos_clientes',$data);					
	}
        
	public function upload_docs ()
	{			
            $campos = array(
                     'proyecto' => array(
                                              'type' => 'VARCHAR',
                                              'constraint' => '32',
                                              'null' => FALSE
                                       ),
                     'file_name' => array(
                                              'type' => 'VARCHAR',
                                              'constraint' => '64',
                                              'null' => FALSE
                                       ),
                     'file_type' => array(
                                              'type' => 'VARCHAR',
                                              'constraint' => '32',
                                              'null' => FALSE
                                       ),
                     'file_size' => array(
                                              'type' => 'INT',
                                              'constraint' => '16',
                                              'null' => FALSE
                                       ),
                     'date' =>  array(
                                              'type' => 'VARCHAR',
                                              'constraint' => '32',
                                              'null' => FALSE
                                    )
                      );
            $this->dbforge->add_field($campos);
            $this->dbforge->add_field('id');
            $this->dbforge->add_key('proyecto');
            $this->dbforge->create_table('docs_clientes',TRUE);
            $handle = fopen($_FILES['file']['tmp_name'], "rb");
            $content = fread($handle, filesize($_FILES['file']['tmp_name']));
            fclose($handle);
            $content= base64_encode($content);
            $format = 'DATE_COOKIE';
            $time = time();
            $date = standard_date($format,$time);
            $data = array(
                    'proyecto'  => $this->input->post('proyecto'), 
                    'file_name' => $_FILES['file']['name'],
                    'file_size' => $_FILES['file']['size'],
                    'file_type' => $_FILES['file']['type'],
                    'date'      => $date
            );
            $cftp = array(
                    'hostname'  => 'ftp.bygfilms.com', 
                    'username' => 'videos@bygfilms.com',
                    'password' => 'bygstudio2013',
                    'debug' => TRUE
            );
            $this->ftp->connect($cftp);
            $this->ftp->upload($_FILES['file']['tmp_name'],'/'.$data['proyecto'].'/docs/'.str_replace(' ','',$data['file_name']),bin);
            $this->ftp->close();		
            return $this->db->insert('docs_clientes',$data);
	}
        
	public function upload_still ()
	{
            $still = $this->input->post('still');
            $still = substr($still, strpos($still, ',')+1);
            $still = str_replace(' ', '+', $still);
            $project = $this->input->post('project');
            $trans = array('aQ'=>'á','eQ'=>'é','iQ'=>'í','oQ'=>'ó','uQ'=>'ú','_'=>' ');
            $project = strtr($project,$trans);
            $fname = $this->input->post('fname');
            $this->db->where('proyecto',$project);
            $this->db->where('file_name',$fname);
            $this->db->update('videos_clientes',array('thumbnail' => $still));
            $still = base64_decode($still);
            $fname = substr($fname, 0, -3);
            write_file('uploads/'.$project.'/thumbnails/'.$fname.'png', $still);	
	}
	
	public function upload_clip()
	{
            if ($_FILES['file']['size'] !== 0)
            {
                $this->db->select('code')->from('clip_categories');
                $query = $this->db->get();
                $result = $query->result();
                foreach ($result as $row)
                {  
                    $tipo = $this->input->post('type-' . $row->code );
                    if ($tipo)
                    {
                        break;
                    }
                }
                $listOrder = $this->input->post('listorder');
                $date = date('Y-m-d H:i:s', time());
                $this->db->select_max('list_order');
                if ($listOrder)
                {
                    $query = $this->db->get_where('byg_clips', array('type' => $tipo, 'list_order' => $listOrder));
                }
                else
                {
                    $query = $this->db->get_where('byg_clips', array('type' => $tipo));
                }
                $clip = $query->row();
                $query = $this->db->get_where('clip_categories', array('code' => $tipo));
                $categoryData = $query->row();
                $categoryName = $categoryData->name;
                $dir = $categoryData->path;
                $newListorder = $clip->list_order + 1;
                if ($listOrder)
                {
                   $newListorder = $clip->list_order; 
                }
                if (! $clip->list_order)
                {
                    $this->ftp->connect();
                    $this->ftp->mkdir($dir, DIR_WRITE_MODE);
                    $this->ftp->mkdir($dir . '/images', DIR_WRITE_MODE);
                    $this->ftp->mkdir($dir . '/videos', DIR_WRITE_MODE);
                    $this->ftp->close();
                }
                if (strpos($_FILES['file']['type'], 'video') !== FALSE)
                {
                    $fname = $_FILES['file']['name'];
                    $imagename = 'video already set';
                    $title = '';
                    $config['upload_path'] = realpath(APPPATH . '../uploads/' . $dir . '/videos');                         
                }
                else
                {
                    $imagename = $_FILES['file']['name'];
                    $dotPos = strpos($imagename, '.');
                    $title = strtolower(substr($imagename, 0, $dotPos));
                    $fname = 'waiting for video';
                    if ($listOrder)
                    {
                        $title = $clip->title;
                        $fname = $clip->fname; 
                    }
                    $config['upload_path'] = realpath(APPPATH . '../uploads/' . $dir . '/images');  
                }	
                $data = array(
                        'type'  => $tipo,
                        'title' => $title,
                        'fname' => $fname,
                        'fsize' => $_FILES['file']['size'],
                        'ftype' => $_FILES['file']['type'],
                        'img_name' => $imagename,
                        'date' => $date,
                        'list_order' => $newListorder,
                        'visibility' => 1
                );
                $this->db->like('fname','waiting for video');
                $this->db->or_like('img_name','video already set');
                $this->db->from('byg_clips');
                $edata = $this->db->count_all_results();
                if ($edata > 0)
                {
                    if (strpos($_FILES['file']['type'], 'video') !== FALSE)
                    {
                        $this->db->where('type', $tipo);
                        $this->db->where('fname', 'waiting for video');
                        $this->db->update('byg_clips', array('fname' => $fname,'fsize' => $data['fsize'],
                        'ftype' => $data['ftype'], 'date' => $date));
                    }
                    else
                    {
                        $this->db->where('type',$tipo);
                        $this->db->where('img_name','video already set');
                        $this->db->update('byg_clips',array('img_name' => $imagename, 'title' => $title));
                    }
                    $this->db->where('code', $tipo);
                    $this->db->update('clip_categories',array(
                        'updated_at' => date('Y-m-d H:i:s', time()),
                        'by_user' => $this->session->userdata('user')
                        ));
                }
                elseif ($listOrder)
                {
                    $this->db->where('type', $tipo);
                    $this->db->where('list_order', $listOrder);
                    $this->db->update('byg_clips',array('img_name' => $imagename)); 
                }
                else  
                {
                    $this->db->insert('byg_clips', $data);
                }
                $field_name = "file";
                if ($tipo === $categoryName)
                {
                    $config['upload_path'] = realpath(FCPATH . '../video/' . $dir); 
                }
                $config['allowed_types'] = '*';
                $config['max_size'] = 0;
                $config['overwrite'] = TRUE;
                $this->load->library('upload', $config);	
                $this->upload->do_upload($field_name);
                return $edata;
            }
	}
		
	public function upload_video ()
	{			
            $campos = array(
                     'proyecto' => array(
                                              'type' => 'VARCHAR',
                                              'constraint' => '32',
                                              'null' => FALSE
                                       ),
                     'file_name' => array(
                                              'type' => 'VARCHAR',
                                              'constraint' => '128',
                                              'null' => FALSE
                                       ),
                     'file_type' => array(
                                              'type' => 'VARCHAR',
                                              'constraint' => '16',
                                              'null' => FALSE
                                       ),
                     'file_size' => array(
                                              'type' => 'INT',
                                              'constraint' => '16',
                                              'null' => FALSE
                                       ),
                     'thumbnail' => array(
                                              'type' =>'TEXT',
                                              'constraint' => '2048',
                                              'null' => FALSE
                                       ),
                     'visitas' => array(
                                              'type' => 'INT',
                                              'constraint' => '3',
                                              'null' => FALSE
                                       ),
                     'comments' => array(
                                              'type' =>'TEXT',
                                              'constraint' => '1024',
                                              'null' => FALSE
                                       ),
                     'date' =>  array(
                                              'type' => 'VARCHAR',
                                              'constraint' => '32',
                                              'null' => FALSE
                                       )
                    );
            $this->dbforge->add_field($campos);
            $this->dbforge->add_field('id');
            $this->dbforge->add_key('proyecto');
            $this->dbforge->create_table('videos_clientes',TRUE);
            if ($_FILES['file']['size'] !== 0)
            {
                $format = 'DATE_COOKIE';		
                $time = time();
                $date = standard_date($format,$time);
                $n = 1;
                $project = $this->input->post('proyecto');	
                $posParenthesis = strpos($project, '(subida');
                if ($posParenthesis)
                {
                    $project = substr($project, 0, $posParenthesis - 1);
                }
                $trans = array('aQ'=>'á','eQ'=>'é','iQ'=>'í','oQ'=>'ó','uQ'=>'ú','_'=>' ');
                $proyecto = strtr($project,$trans);
                $this->db->where('project',$proyecto);
                $query = $this->db->get('proyectos_clientes');
                $row = $query->row_array();
                $query = $this->db->get_where('customers_users', array('cliente' => $row['cliente']));
                $rows = $query->result_array();
                $list = array();
                foreach ($rows as $row)
                {
                   $list['correo'.$n] = $row['correo'];
                   ++$n; 
                }		 
                $this->session->set_userdata($list);
                $data = array(
                        'proyecto'  => $proyecto, 
                        'file_name' => $_FILES['file']['name'],
                        'file_size' => $_FILES['file']['size'],
                        'file_type' => $_FILES['file']['type'],
                        'date'      => $date
                );
                $this->db->like('file_name',$data['file_name']);
                $this->db->like('proyecto',$data['proyecto']);
                $this->db->from('videos_clientes');
                $evid = $this->db->count_all_results();
                if ($evid > 0)
                {
                    $this->db->where('file_name',$data['file_name']);
                    $this->db->where('proyecto',$data['proyecto']);
                    $this->db->update('videos_clientes',array('date' => $date,'file_size' => $data['file_size']));
                }
                else
                {
                    $this->db->insert('videos_clientes',$data);
                }
                $data['proyecto'] = $project;
                $data['secure'] = FALSE;
                if ($posParenthesis)
                {
                    $data['secure'] = TRUE;
                }
                $this->session->set_userdata($data);
                if ($data['file_size'] < 822083584)  //file_size < 784 MB para subida directa al servidor
                {
                    $field_name = "file";
                    $config['upload_path'] = realpath(APPPATH . '../uploads/'.$project); 
                    if ($posParenthesis)
                    {
                        $this->ftp->connect();
                        $secureFiles = $this->ftp->list_files($project.'/secure/');
                        if ( ! $secureFiles)
                        {
                            $this->ftp->mkdir($project.'/secure', DIR_WRITE_MODE, TRUE);
                        }
                        $this->ftp->close();
                        $config['upload_path'] = realpath(APPPATH . '../uploads/'.$project.'/secure'); 
                    }
                    $config['allowed_types'] = '*';
                    $config['max_size'] = 0;
                    $config['overwrite'] = TRUE;
                    $this->load->library('upload',$config);	
                    $this->upload->do_upload($field_name);
                    return $this->upload->display_errors('<h1>','</h1>');
                }
                else
                {
                    $this->ftp->connect();
                    $serverPath = $project.'/'.str_replace(' ','_',$_FILES['file']['name']);
                    if ($posParenthesis)
                    {
                        $serverPath = $project.'/secure/'.str_replace(' ','_',$_FILES['file']['name']);
                    }
                    $this->ftp->upload($_FILES['file']['tmp_name'], $serverPath, 'bin');
                    $this->ftp->close();
                }
            }
	}		
}