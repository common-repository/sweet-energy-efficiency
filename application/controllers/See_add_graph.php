<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly;

class See_add_graph extends Winter_MVC_Controller {

	public function __construct(){
		parent::__construct();
	}
    
	public function index()
	{
        //$this->enable_error_reporting();       

        $this->load->model('graph_m');

        $graph_id = $this->input->post_get('id');

        // Prepare db data
        $this->data['db_data'] = NULL;

        if(!empty($graph_id))
            $this->data['db_data'] = $this->graph_m->get($graph_id, TRUE);

        //dump($this->data['db_data']);

        //echo $this->db->last_query();

        $this->data['form'] = &$this->form;

        $rules = array(
            array(
                'field' => 'title',
                'label' => __('Title', 'sweet-energy-efficiency'),
                'rules' => 'required'
            ),
            array(
                'field' => 'description',
                'label' => __('Description', 'sweet-energy-efficiency'),
                'rules' => 'required'
            ),
            array(
                'field' => 'ratings_number',
                'label' => __('Ratings number', 'sweet-energy-efficiency'),
                'rules' => ''
            ),
            array(
                'field' => 'layout',
                'label' => __('Layout', 'sweet-energy-efficiency'),
                'rules' => 'required'
            ),
            array(
                'field' => 'json_data',
                'label' => __('Scales data', 'sweet-energy-efficiency'),
                'rules' => 'required'
            ),
            array(
                'field' => 'unit',
                'label' => __('Unit', 'sweet-energy-efficiency'),
                'rules' => ''
            ),
        );

        if($this->form->run($rules))
        {
            // Save procedure for basic data
 
            $data = $this->graph_m->prepare_data($this->input->post(), 
                                                    array('title', 
                                                        'description', 'ratings_number', 
                                                        'layout', 'json_data', 'unit'));


            if ( headers_sent($file, $line) ) {
				exit('header sent on: '.$file.' - '.$line);
            }
            
            $data['json_data'] = stripslashes($data['json_data']);

            $id = $this->graph_m->insert($data, $graph_id);

            if($id > 0)
            {
                wp_redirect(admin_url("admin.php?page=see_add_graph&id=$id&is_updated=true")); exit;
            }
        }

        // Load view
        $this->load->view('see_add_graph/index', $this->data);
    }
}


?>