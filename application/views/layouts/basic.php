<div class="graph_wrapper graph_id_<?php echo wmvc_show_data('widget_id', $atts, ''); ?>">

<div class="graph_title">
<?php echo wmvc_show_data('title', $db_data, ''); ?>
</div>

<div class="graph_border">
    <div class="graph_unit">
    <?php echo wmvc_show_data('unit', $db_data, ''); ?>
    </div>
    <div class="graph_and_current">

        <div class="rating_container">
        <?php
            $json_data = wmvc_show_data('json_data', $db_data, '');

            $json = json_decode($json_data);

            //echo '<pre>';
            //echo dump($json);
            //echo '</pre>';

            if(is_array($json))
            foreach($json as $key=>$row):
            if(empty($row->label))continue;
        ?>

        <div class="graph_rating gr_<?php echo esc_attr($key); ?>"><span class="graph_from_to">(<?php echo esc_attr($row->from); ?>-<?php echo esc_attr($row->to); ?>)</span><span class="graph_label"><?php echo esc_attr($row->label); ?></span></div>

        <?php endforeach; ?>
        </div>


        <div class="graph_current">
            <div class="part-right">
                <?php echo wmvc_show_data('value', $atts, 'XX'); ?>
            </div>
            <div class="arrow-left">
            </div>
        </div>
        <div style="clear:both"></div>
    </div>
    
</div>

<div class="graph_description">
<?php echo wmvc_show_data('description', $db_data, ''); ?>
</div>

</div>

<style>

.graph_wrapper
{
    background:white;
    max-width:400px;
    padding:5px;
    position:relative;
    line-height: 18px;
    font-size: 13px;
    font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif;
    outline: 0;
}

.graph_description
{
    padding: 5px;
}

.graph_wrapper *
{
    box-sizing: border-box;
}

.graph_title
{
    background: #0272BC;
    color: white;
    padding: 10px;
}

.graph_from_to
{
    font-size: 13px;
}

.graph_border
{
    border: 1px solid black;
    margin-top: 5px;
    width: 100%;
}

.rating_container
{
    margin-top: 5px;
    width: 80%;
    float:left;
}

.graph_current
{
    right:0px;
    position:absolute;
}

.graph_unit
{
    font-style: italic;
    padding:5px;
}

.graph_rating
{
    background: green;
    margin: 5px 0px 0px 0px;
    padding: 5px;
    color: white;
    outline: 0;
}

.graph_label
{
    float:right;
    font-size: 20px;
    font-weight: bold;
}

.arrow-left {
  width: 0; 
  height: 0; 
  border-top: 14px solid transparent;
  border-bottom: 14px solid transparent; 
  border-right:10px solid green; 
  float:right;
}

.part-right {
  float:right;
  background: green;
  color: white;
  border:10px green;
  font-size: 20px;
  font-weight: bold;
  padding:5px;
}

.graph_and_current
{
    position:relative;
}

<?php
    if(is_array($json)):

    $g_width = 50;

    // remove empty rows
    foreach($json as $key=>$row)
    {
        if(empty($row->label))unset($json[$key]);
    }

    $total_ratings = count($json);

    $step_width = intval((100-$g_width)/$total_ratings);

    $test_value = intval($total_ratings/2);

    $test_value_top = 10+33.2*$test_value;
    
    foreach($json as $key=>$row):
    if(empty($row->label))continue;

    $g_width+=$step_width;
?>

.gr_<?php echo esc_attr($key); ?>
{
    background: <?php echo esc_attr($row->color); ?>;
    width: <?php echo esc_attr($g_width); ?>%;
}
<?php endforeach; endif; ?>

<?php

    if(isset($atts['value']) && is_numeric($atts['value']))
    {
        foreach($json as $key=>$row)
        {
            if( $row->from <= $atts['value'] && $atts['value'] <= $row->to )
            {
                $test_value_top = 10+33.2*$key;
                break;
            }
        }
    }
    elseif(isset($atts['value']))
    {
        foreach($json as $key=>$row)
        {
            if( $row->label == $atts['value'])
            {
                $test_value_top = 10+33.2*$key;
                break;
            }
        }
    }

?>


.graph_id_<?php echo wmvc_show_data('widget_id', $atts, ''); ?> .graph_current
{
    top: <?php echo esc_attr($test_value_top); ?>px;
}

</style>


