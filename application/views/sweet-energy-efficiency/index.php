<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://listing-themes.com/
 * @since      1.0.0
 *
 * @package    Winter_Activity_Log
 * @subpackage Winter_Activity_Log/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap see_wrap">

<h1><?php echo __('Manage Graphs','sweet-energy-efficiency'); ?> <a href="<?php menu_page_url( 'see_add_graph', true ); ?>" class="page-title-action"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php echo __('Add New','sweet-energy-efficiency')?></a></h1>

<div class="ee-wrapper">
    <div class="ee-panel ee-panel-default">
        <div class="ee-panel-heading flex">
            <h3 class="ee-panel-title"><?php echo __('Graphs data','sweet-energy-efficiency'); ?></h3>
            <a href="#bulk_remove-form" id="bulk_remove" class="inv btn btn-danger page-title-action pull-right popup-with-form"><i class="fa fa-remove"></i>&nbsp;&nbsp;<?php echo __('Bulk remove','sweet-energy-efficiency')?></a>
        </div>
        <div class="ee-panel-body">

            <!-- Data Table -->
            <div class="box box-without-bottom-padding">
                <div class="tableWrap dataTable table-responsive js-select">
                    <table id="din-table" class="table table-striped" style="width: 100%;">
                        <thead>
                            <tr>
                                <th data-priority="1">#</th>
                                <th data-priority="2"><?php echo __('Title', 'sweet-energy-efficiency'); ?></th>
                                <th><?php echo __('Description', 'sweet-energy-efficiency'); ?></th>
                                <th data-priority="3"></th>
                                <th><input type="checkbox" class="selectAll" name="selectAll" value="all"></th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th><input type="text" name="filter_id" class="dinamic_par"  placeholder="<?php echo __('Filter #', 'sweet-energy-efficiency'); ?>" /></th>
                                <th><input type="text" name="filter_title" class="dinamic_par" placeholder="<?php echo __('Filter Title', 'sweet-energy-efficiency'); ?>" /></th>
                                <th><input type="text" id="filter_description" name="filter_date" class="dinamic_par" placeholder="<?php echo __('Filter Description', 'sweet-energy-efficiency'); ?>" /></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>
    
</div>
</div>


<?php

wp_enqueue_style('sweet-energy-efficiency_basic_wrapper');
wp_enqueue_script( 'datatables' );
wp_enqueue_script( 'dataTables-responsive' );
wp_enqueue_script( 'dataTables-select' );

wp_enqueue_style( 'dataTables-select' );
?>

<script>
 
var wal_timer_live_monitoring;
var temp_change = '';

// Generate table
jQuery(document).ready(function($) {
    var table;

    //$(".selectAll").unbind();

    $(".selectAll").on( "click", function(e) {
        if ($(this).is( ":checked" )) {
            table.rows(  ).select();        
            //$(this).attr('checked','checked');
        } else {
            table.rows(  ).deselect(); 
            //$(this).attr('checked','');
        }
        //return false;
    });

    $('#bulk_remove').click(function(){
        var count = table.rows( { selected: true } ).count();
        
        if(count == 0)
        {
            alert('<?php echo esc_attr__('Please select listings to remove', 'sweet-energy-efficiency'); ?>');
            return false;
        }
        else
        {

            if(confirm('<?php echo_js(__('Are you sure?', 'sweet-energy-efficiency')); ?>'))
            {
                $('img#ajax-indicator-masking').show();

                var form_selected_listings = table.rows( { selected: true } );
                var ids = table.rows( { selected: true } ).data().pluck( 'idgraph' ).toArray();

                // ajax to remove rows
                $.post('<?php menu_page_url( 'sweet-energy-efficiency', true ); ?>&function=bulk_remove', { graph_ids: ids }, function(data) {

                    $('img#ajax-indicator-masking').hide();

                    table.ajax.reload();

                });
            }
        }

        return false;
    });


	if ($('#din-table').length) {

        sw_log_s_table_load_counter = 0;

        table = $('#din-table').DataTable({
            "ordering": true,
            "responsive": true,
            "processing": true,
            "serverSide": true,
            'ajax': {
                "url": ajaxurl,
                "type": "POST",
                "data": function ( d ) {

                    $(".selectAll").prop('checked', false);

                    return $.extend( {}, d, {
                        "page": 'sweet-energy-efficiency',
                        "function": 'datatable',
                        "action": 'sweet-energy-efficiency_action'
                    } );


                }
            },
            "language": {
                search: "<?php echo_js(__('Search', 'sweet-energy-efficiency')); ?>",
                searchPlaceholder: "<?php echo_js(__('Enter here filter tag for any column', 'sweet-energy-efficiency')); ?>"
            },
            "initComplete": function(settings, json) {
            },
            "fnDrawCallback": function (oSettings){

                if(sw_log_s_table_load_counter == 0)
                {
                    sw_log_s_table_load_counter++;
                    if($('#filter_user').val() != '')
                    setTimeout(function(){ table.columns(4).search( $('#filter_user').val() ).draw(); }, 1000);
                    
                }

                $('a.delete_button').click(function(){
                    
                    if(confirm('<?php echo_js(__('Are you sure?', 'sweet-energy-efficiency')); ?>'))
                    {
                       // ajax to remove row
                        $.post($(this).attr('href'), function( [] ) {
                            table.row($(this).parent()).remove().draw( false );
                        });
                    }

                   return false;
                });

                if ( table.responsive.hasHidden() )
                {
                    jQuery('table.dataTable td.details-control').addClass('details-control');
                }
                else
                {
                    jQuery('table.dataTable td.details-control').removeClass('details-control');
                }
                jQuery('.dataTable div.dataTables_wrapper div.dataTables_filter input').addClass("dinamic_par").attr('name','sw_log_search');
                jQuery('.dataTable div.dataTables_wrapper div.dataTables_length select').addClass("dinamic_par").attr('name','sw_log_count');
                
            },
            'columns': [
                { data: "idgraph" },
                { data: "title" },
                { data: "description"   },
                { data: "edit"    },
                { data: "checkbox"  }
            ],
//            columnDefs: [
//                { responsivePriority: 1, targets: 0 },
//                { responsivePriority: 2, targets: -2 }
//            ],
            responsive: {
                details: {
                    type: 'column',
                    target: 0
                }
            },
            order: [[ 0, 'desc' ]],
            columnDefs: [   {
                                //className: 'control',
                                className: 'details-control',
                                orderable: true,
                                targets:   0
                            },
                            {
                                //className: 'control',
                                //className: 'details-control',
                                orderable: true,
                                targets:   1
                            },
                            {
                                className: 'select-checkbox',
                                orderable: false,
                                defaultContent: '',
                                targets:   4
                            }
            ],
            select: {
                style:    'multi',
                selector: 'td:last-child'
            },
			'oLanguage': {
				'oPaginate': {
					'sPrevious': '<i class="fa fa-angle-left"></i>',
					'sNext': '<i class="fa fa-angle-right"></i>'
				},
                'sSearch': "<?php echo_js(__('Search', 'sweet-energy-efficiency')); ?>",
                "sLengthMenu": "<?php echo_js(__('Show _MENU_ entries', 'sweet-energy-efficiency')); ?>",
                "sInfoEmpty": "<?php echo_js(__('Showing 0 to 0 of 0 entries', 'sweet-energy-efficiency')); ?>",
                "sInfo": "<?php echo_js( __('Showing _START_ to _END_ of _TOTAL_ entries', 'sweet-energy-efficiency')); ?>",
                "sEmptyTable": "<?php echo_js(__('No data available in table', 'sweet-energy-efficiency')); ?>",
			},
			'dom': "<'row'<'col-sm-7 col-md-5'f><'col-sm-5 col-md-6'l>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>"
		});
        
//		$('.js-select select:not(.basic-select)').select2({
//			minimumResultsForSearch: Infinity
//		});
        
        // Apply the search
        table.columns().every( function () {
            var that = this;
     
            $( 'input,select', this.footer() ).on( 'keyup change', function () {
                if ( that.search() !== this.value ) {
                    that
                        .search( this.value )
                        .draw();
                }
            } );

        } );

        if ($('#wal_live_monitoring').is(':checked')) {
            wal_timer_live_monitoring = setInterval(function(){ table.ajax.reload(); }, 10000);
        }
        
	}

    // Add event listener for opening and closing details
    $('table.dataTable tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row( tr );
 
        if ( row.child.isShown() ) {
            // This row is already open - close it
            //row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
            //row.child( format(row.data()) ).show();
            tr.addClass('shown');
        }
    });


});

</script>


<style>

.ee-wrapper #din-table_wrapper .row
{
    margin:0px;
}

.ee-wrapper .dataTable div.dataTables_wrapper label
{
    width:100%;
    padding:10px 0px;
}

.dataTable div.dataTables_wrapper div.dataTables_filter input
{
    display:inline-block;
    width:65%;
    margin: 0 10px;
}

.dataTable div.dataTables_wrapper div.dataTables_length select
{
    display:inline-block;
    width:100px;
    margin: 0 10px;
}

.dataTable td.control
{
    color:#337AB7;
    display:table-cell !important;
    font-weight: bold;
}

.dataTable th.control
{
    display:table-cell !important;
}

.ee-wrapper .table > tbody > tr > td, .ee-wrapper .table > tbody > tr > th, 
.ee-wrapper .table > tfoot > tr > td, .ee-wrapper .table > tfoot > tr > th, 
.ee-wrapper .table > thead > tr > td, .ee-wrapper .table > thead > tr > th {
    vertical-align: middle;
}

table.dataTable tbody > tr.odd.selected, table.dataTable tbody > tr > .odd.selected {
    background-color: #B0BED9;
}

.ee-wrapper table.dataTable tbody td.select-checkbox::before, 
.ee-wrapper table.dataTable tbody td.select-checkbox::after, 
.ee-wrapper table.dataTable tbody th.select-checkbox::before, 
.ee-wrapper table.dataTable tbody th.select-checkbox::after {
    display: block;
    position: absolute;
    /*top: 2.5em;*/
    top:50%;
    left: 50%;
    width: 12px;
    height: 12px;
    box-sizing: border-box;
}

.ee-wrapper a#bulk_remove:hover,
.ee-wrapper a#bulk_remove:focus {
    text-decoration: none;
}

tfoot input{
    width:100%;
    min-width:70px;
}

img.avatar
{
    width: 50px;
    height: 50px;
    border-radius: 50%;
}

.wal-system-icon{
    width: 50px;
    font-size: 50px;
    height: 50px;
}

.dashicons.wal-system-icon.dashicons-before::before {
    display: inline-block;
    font-family: dashicons;
    transition: color .1s ease-in;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    width: 50px;
    font-size: 50px;
    height: 50px;
}

/* sw_log_notify */

.sw_log_notify-box {
    position: fixed;
    right: 15px;
    bottom: 0;
    z-index: 100;
    
    position: fixed;
    z-index: 5000;
    bottom: 10px;
    right: 10px;
}

.sw_log_notify {
    position: relative;
    background: #fffffff7;
    padding: 12px 15px;
    border-radius: 15px;
    width: 250px;
    box-shadow: 0px 1px 0px 0.25px rgba(0, 0, 0, 0.07);
    -webkit-box-shadow: 0px 0 3px 2px rgba(0, 0, 0, 0.08);
    margin: 0;
    margin-bottom: 10px;
    font-size: 16px;
    
    background: #5cb811;
    background: rgba(92, 184, 17, 0.9);
    padding: 15px;
    border-radius: 4px;
    color: #fff;
    text-shadow: -1px -1px 0 rgba(0, 0, 0, 0.5);
    
    -webkit-transition: all 500ms cubic-bezier(0.175, 0.885, 0.32, 1.275);
    -moz-transition: all 500ms cubic-bezier(0.175, 0.885, 0.32, 1.275);
    -ms-transition: all 500ms cubic-bezier(0.175, 0.885, 0.32, 1.275);
    -o-transition: all 500ms cubic-bezier(0.175, 0.885, 0.32, 1.275);
    transition: all 500ms cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.sw_log_notify.error  {
    margin: 0;
    margin-bottom: 10px;
    background: #cf2a0e;
    padding: 12px 15px;
}

.sw_log_notify.loading  {
    background: #5bc0de;
}

.sw_log_notify {
    display: block;
    margin-top: 10px;
    position: relative;
    opacity: 0;
    transform: translateX(120%);
}

.sw_log_notify.show {
    transform: translateX(0);
    opacity: 1;
}
    
/* end sw_log_notify */

.ee-wrapper .dataTables_filter .form-control {
    height: 30px;
}


body .ee-wrapper .table-responsive {
    overflow-x: visible;
}


body .datepicker table.table-condensed tbody > tr:hover > td:first-child, body .datepicker table.table-condensed tbody > tr.selected > td:first-child {
    border-left: 0px solid #fba56a;
    border-radius: 3px 0 0 3px;
}
body .datepicker table.table-condensed tbody > tr > td:first-child {
    border-left: 0px solid #ffff;
    border-radius: 3px 0 0 3px;
}

</style>

<?php $this->view('general/footer', $data); ?>
