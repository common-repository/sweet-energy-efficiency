<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly;

class Sweet_energy_efficiency_index extends Winter_MVC_Controller {

	public function __construct(){
		parent::__construct();
	}
    
	public function index()
	{

        // Load view
        $this->load->view('sweet-energy-efficiency/index', $this->data);
    }

	// Called from ajax
	// json for datatables
	public function datatable()
	{
        //$this->enable_error_reporting();
        remove_action( 'shutdown', 'wp_ob_end_flush_all', 1 );

        // configuration
        $columns = array('idgraph', 'title', 'description');
        $controller = 'graph';
        
        // Fetch parameters
        $parameters = $this->input->post();
        $draw = $this->input->post_get('draw');
        $start = $this->input->post_get('start');
        $length = $this->input->post_get('length');
		$search = $this->input->post_get('search');

        if(isset($search['value']))
			$parameters['searck_tag'] = $search['value'];
			
		$this->load->model($controller.'_m');

        $recordsTotal = $this->{$controller.'_m'}->total_lang(array(), NULL);
        
        see_prepare_search_query_GET($columns, $controller.'_m');
        $recordsFiltered = $this->{$controller.'_m'}->total_lang(array(), NULL);
        
        see_prepare_search_query_GET($columns, $controller.'_m');
        $data = $this->{$controller.'_m'}->get_pagination_lang($length, $start, NULL);

        $query = $this->db->last_query();

        // Add buttons
        foreach($data as $key=>$row)
        {
            foreach($columns as $val)
            {
                if(isset($row->$val))
                {
                    
                }
                elseif(isset($row->json_object))
                {
                    $json = json_decode($row->json_object);
                    if(isset($json->$val))
                    {
                        $row->$val = $json->$val;
                    }
                    else
                    {
                        $row->$val = '-';
                    }
                }
                else
                {
                    $row->$val = '-';
                }
            }

            $options = btn_edit(admin_url("admin.php?page=see_add_graph&id=".$row->{"id$controller"})).' ';

            $row->edit = $options;

            $row->checkbox = '';
        }

        //format array is optional
        $json = array(
                "parameters" => $parameters,
                "query" => $query,
                "draw" => $draw,
                "recordsTotal" => $recordsTotal,
                "recordsFiltered" => $recordsFiltered,
                "data" => $data
                );

        //$length = strlen(json_encode($data));
        header('Pragma: no-cache');
        header('Cache-Control: no-store, no-cache');
        //header('Content-Type: application/json; charset=utf8');
        //header('Content-Length: '.$length);
        echo json_encode($json);
        
        exit();
    }
    
    public function bulk_remove($id = NULL, $redirect='1')
	{   
        $this->load->model('graph_m');

        // Get parameters
        $graph_ids = $this->input->post('graph_ids');

        $json = array(
            "graph_ids" => $graph_ids,
            );

        foreach($graph_ids as $graph_id)
        {
            if(is_numeric($graph_id))
                $this->graph_m->delete($graph_id);
        }

        echo json_encode($json);
        
        exit();
    }
    
    public function remove($id = NULL, $redirect='')
	{   
        if(!wal_access_allowed('winterlock_logs'))
        {
            exit();
        }

        $this->load->model('log_m');
        // Get parameters
        $id = $this->input->post_get('log_id');
        
        if(is_numeric($id))
            $this->log_m->delete($id);
        
        if(empty($redirect)):
            wp_redirect(admin_url("admin.php?page=sweet-energy-efficiency&is_updated=true")); exit;
        else:
            wp_redirect($redirect); exit;
        endif;
        
        exit();
    }
    

    
}
