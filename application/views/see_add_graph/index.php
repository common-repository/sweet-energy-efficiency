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

<h1><?php echo __('Add/Edit graph','sweet-energy-efficiency'); ?></h1>

<form method="post" id="graph_edit" action="">

<?php //dump($_POST); ?>
<div class="ee-wrapper">
<?php 

$form->messages();

if(isset($_GET['is_updated']))
{
  echo '<p class="alert alert-success">'.__('Successfully saved', 'wmvc_win').'</p>';
}


?>
</div>


<div class="ee-wrapper">
    <div class="ee-panel ee-panel-default">
        <div class="ee-panel-heading">
            <h3 class="ee-panel-unit"><?php echo __('Graph details','sweet-energy-efficiency'); ?></h3>
        </div>
        <div class="ee-panel-body form-horizontal">

        <?php
          $default_i = '';
        ?>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="inputtitle"><?php echo __('Graph title','sweet-energy-efficiency'); ?>*</label>
            <div class="col-sm-10">
                <input name="title" type="text" class="form-control" id="inputtitle" value="<?php echo wmvc_show_data('title', $db_data, $default_i); ?>" placeholder="<?php echo __('Graph title','sweet-energy-efficiency'); ?>">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="inputdescription"><?php echo __('Graph description','sweet-energy-efficiency'); ?>*</label>
            <div class="col-sm-10">
                <textarea name="description" class="form-control" id="inputdescription" placeholder="<?php echo __('Graph description','sweet-energy-efficiency'); ?>"><?php echo wmvc_show_data('description', $db_data, $default_i); ?></textarea>
            </div>
        </div>

        <?php
          $default_i = '';
        ?>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="inputunit"><?php echo __('Unit','sweet-energy-efficiency'); ?></label>
            <div class="col-sm-10">
                <input name="unit" type="text" class="form-control" id="inputunit" value="<?php echo wmvc_show_data('unit', $db_data, $default_i); ?>" placeholder="<?php echo __('Unit','sweet-energy-efficiency'); ?>">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="inputlayout"><?php echo __('Layout','sweet-energy-efficiency'); ?>*</label>
            <div class="col-sm-10">
            <?php 
            
            $options_array = array();

            // Load existing views from folder
            if ($handle = opendir(SWEET_ENERGY_EFFICIENCY_PATH.'application/views/layouts')) {

                /* This is the correct way to loop over the directory. */
                while (false !== ($entry = readdir($handle))) {
                    if(strpos($entry, '.php') !== FALSE)
                        $options_array[str_replace('.php', '', $entry)] = 
                            str_replace('.php', '', ucfirst($entry));
                }
            
                closedir($handle);
            }

            
            echo wmvc_select_radio('layout', $options_array, wmvc_show_data('layout', $db_data, 'basic')); 
            ?>
            </div>
        </div>

        <?php
          $default_i = '';
        ?>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="inputjson_data"><?php echo __('Scales data','sweet-energy-efficiency'); ?>*</label>
            <div class="col-sm-10">
                <textarea name="json_data" readonly class="form-control" id="inputjson_data" placeholder="<?php echo __('Scales data','sweet-energy-efficiency'); ?>"><?php echo wmvc_show_data('json_data', $db_data, $default_i); ?></textarea>
            </div>
        </div>

        </div>
    </div>
</div>

<div class="ee-wrapper">
    <div class="ee-panel ee-panel-default">
        <div class="ee-panel-heading">
            <h3 class="ee-panel-unit"><?php echo __('Scales data','sweet-energy-efficiency'); ?></h3>
        </div>
        <div class="ee-panel-body form-horizontal">

        <table class="table table-stripe" id="scales_data">
            <tr>
                <th><?php echo __('Color','sweet-energy-efficiency'); ?></th>
                <th><?php echo __('From value','sweet-energy-efficiency'); ?></th>
                <th><?php echo __('To value','sweet-energy-efficiency'); ?></th>
                <th><?php echo __('Label','sweet-energy-efficiency'); ?></th>
                <th></th>
            </tr>

            <?php
                $json_data = wmvc_show_data('json_data', $db_data, '');

                $json = json_decode($json_data);

                //echo '<pre>';
                //echo dump($json);
                //echo '</pre>';

                if(is_array($json))
                foreach($json as $row):
                if(empty($row->label))continue;
            ?>

            <tr>
                <td><input name="color[]" type="text" class="form-control see-color-field" value="<?php echo esc_html($row->color); ?>" placeholder="<?php echo __('Color','sweet-energy-efficiency'); ?>"></td>
                <td><input name="from[]" type="text" class="form-control" value="<?php echo esc_html($row->from); ?>" placeholder="<?php echo __('From value','sweet-energy-efficiency'); ?>"></td>
                <td><input name="to[]" type="text" class="form-control" value="<?php echo esc_html($row->to); ?>" placeholder="<?php echo __('To value','sweet-energy-efficiency'); ?>"></td>
                <td><input name="label[]" type="text" class="form-control" value="<?php echo esc_html($row->label); ?>" placeholder="<?php echo __('Label','sweet-energy-efficiency'); ?>"></td>
                <td></td>
            </tr>
            
            <?php endforeach; ?>

            <tr>
                <td><input name="color[]" type="text" class="form-control see-color-field" value="" placeholder="<?php echo __('Color','sweet-energy-efficiency'); ?>"></td>
                <td><input name="from[]" type="text" class="form-control" value="" placeholder="<?php echo __('From value','sweet-energy-efficiency'); ?>"></td>
                <td><input name="to[]" type="text" class="form-control" value="" placeholder="<?php echo __('To value','sweet-energy-efficiency'); ?>"></td>
                <td><input name="label[]" type="text" class="form-control" value="" placeholder="<?php echo __('Label','sweet-energy-efficiency'); ?>"></td>
                <td><button type="button" class="add_btn btn btn-info"><span class="dashicons dashicons-plus"></span></button></td>
            </tr>
        </table>

        <div class="form-group">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-success"><?php echo __('Save graph','sweet-energy-efficiency'); ?></button>
            </div>
        </div>
        </div>
    </div>

</div>

<?php if(isset($_GET['id'])): ?>

<div class="ee-wrapper">
    <div class="ee-panel ee-panel-default">
        <div class="ee-panel-heading">
            <h3 class="ee-panel-unit"><?php echo __('Example graph layout','sweet-energy-efficiency'); ?></h3>
        </div>
        <div class="ee-panel-body form-horizontal">

        <p class="alert alert-info"><?php echo __('Graph will refresh after save', 'wmvc_win'); ?></p>

        <?php $this->view('layouts/'.wmvc_show_data('layout', $db_data, 'basic'), $data); ?>

        </div>
    </div>

</div>

<div class="ee-wrapper">
    <div class="ee-panel ee-panel-default">
        <div class="ee-panel-heading">
            <h3 class="ee-panel-unit"><?php echo __('Example shortcode','sweet-energy-efficiency'); ?></h3>
        </div>
        <div class="ee-panel-body form-horizontal">

        <pre>[see_graph id="<?php echo intval($_GET['id']); ?>" value="XX"]</pre>
            <?php if(function_exists('run_wpdirectorykit')):?>
            <pre>[see_graph id="<?php echo intval($_GET['id']); ?>" wdk_field_id="XX"]</pre>
            attr 'wdk_field_id' - set field id for WDK Listings 
            <?php endif;?>
        </div>
    </div>

</div>

<?php endif; ?>

</form>


<?php

wp_enqueue_style('sweet-energy-efficiency_basic_wrapper');

?>
<script>

jQuery(document).ready(function($) {

    $("form#graph_edit").submit(function(){
        see_generate_json();
    });

    $('button.add_btn').on('click', function()
    {
        var clone = $(this).parent().parent().clone();
        clone.find('td:last-child').html('');

        $(this).parent().parent().before(clone);

        $(this).parent().parent().find('input').val('');

    });


    $('.see-color-field').wpColorPicker();

    $('table#scales_data input').blur(function(){
        see_generate_json();
    });

    function see_generate_json()
    {
        var json_gen = '';

        $('table#scales_data tr').each(function(index, tr) { 

            if(index==0)return;

            json_gen += '{';

            $(this).find('input').each(function(){
                var el_name = $(this).attr('name');

                if(el_name)
                {
                    json_gen += '"'+el_name.substr(0, el_name.length-2)+'"';

                    json_gen += ': "'+$(this).val()+'" ';

                    json_gen += ', ';
                }

            });

            json_gen = json_gen.substr(0, json_gen.length-2);

            json_gen += '}, ';


        });

        if(json_gen.length > 0)
            json_gen = json_gen.substr(0, json_gen.length-2);

        if(json_gen.length > 0)
                $('#inputjson_data').val('[ ' + json_gen + ' ]');

    }

});



</script>

<style>

.ee-wrapper .table-stripe tr td:first-child * {
    -webkit-box-sizing: content-box;
    -moz-box-sizing: content-box;
    box-sizing: content-box;
}

.button.button-small.wp-picker-clear {
    margin: 4px 0px 0px 5px;
}

</style>

<?php $this->view('general/footer', $data); ?>
