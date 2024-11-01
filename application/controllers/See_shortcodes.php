<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly;

class See_shortcodes extends Winter_MVC_Controller {

	public function __construct(){
		parent::__construct();
	}
    
	public function index()
	{
    }

	public function see_graph($atts, $content)
	{
        $this->load->model('graph_m');

        $this->data['atts'] = $atts;
        $this->data['content'] = $content;

        if(!empty($this->data['atts']['id']) && is_numeric($this->data['atts']['id']))
            $this->data['db_data'] = $this->graph_m->get($this->data['atts']['id'], TRUE);

        // Load view
        if(is_object($this->data['db_data']))
            return $this->load->view('layouts/basic', $this->data, FALSE);

        return '';
    }
    
}
