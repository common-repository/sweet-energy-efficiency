<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly;

class See_contact extends Winter_MVC_Controller {

	public function __construct(){
		parent::__construct();
	}
    
	public function index()
	{
        // Load view
        $this->load->view('see_contact/index', $this->data);
    }
    
}
