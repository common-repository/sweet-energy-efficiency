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

    <div class="ee-panel ee-panel-default">
        <div class="ee-panel-heading flex">
            <h3 class="ee-panel-title"><?php echo __('Contact Form','sweet-energy-efficiency'); ?></h3>
        </div>
        <div class="ee-panel-body">
            <div class="">

            <div class="validation-messages">
            </div>

            <form id="contactForm" class="form-layout">
                <div class="form-group">
                    <label for="contactForm_name"><?php echo __('Full Name','sweet-energy-efficiency'); ?></label>
                    <input name="name" type="name" class="form-control" id="contactForm_name" placeholder="<?php echo __('Name Surname','sweet-energy-efficiency'); ?>">
                </div>
                <div class="form-group">
                    <label for="contactForm_email"><?php echo __('Your Email','sweet-energy-efficiency'); ?></label>
                    <input name="email" type="email" class="form-control" id="contactForm_email" value="<?php echo get_bloginfo( 'admin_email' ); ?>" placeholder="name@example.com">
                </div>
                <div class="form-group">
                    <label for="contactForm_category"><?php echo __('Question Category','sweet-energy-efficiency'); ?></label>
                    <select name="category" class="form-control" id="contactForm_category">
                    <option><?php echo __('Not Selected','sweet-energy-efficiency'); ?></option>
                    <option><?php echo __('Suggestion','sweet-energy-efficiency'); ?></option>
                    <option><?php echo __('Issue','sweet-energy-efficiency'); ?></option>
                    <option><?php echo __('Custom Work','sweet-energy-efficiency'); ?></option>
                    <option><?php echo __('Other','sweet-energy-efficiency'); ?></option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="contactForm_rate"><?php echo __('Satisfication with plugin','sweet-energy-efficiency'); ?></label>
                    <select name="rate" class="form-control" id="contactForm_rate">
                    <option><?php echo __('Not Selected','sweet-energy-efficiency'); ?></option>
                    <option><?php echo __('1 (Not satisfied)','sweet-energy-efficiency'); ?></option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                    <option><?php echo __('5 (Very satisfied)','sweet-energy-efficiency'); ?></option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="contactForm_subject"><?php echo __('Subject','sweet-energy-efficiency'); ?></label>
                    <input name="subject" type="text" class="form-control" id="contactForm_subject" placeholder="Subject">
                </div>
                <div class="form-group">
                    <label for="contactForm_message"><?php echo __('Message','sweet-energy-efficiency'); ?></label>
                    <textarea name="message" class="form-control" id="contactForm_message" rows="10"></textarea>
                </div>
                <button type="submit" id="contactForm_submit" class="btn btn-success"><?php echo __('Send','sweet-energy-efficiency'); ?> <img id="ajax-indicator-masking" src="<?php echo SWEET_ENERGY_EFFICIENCY_URL . 'admin/images/ajax-loader-white-small.gif'; ?>" style="display: none;" /></button>
            </form>


            </div>
        </div>
    </div>
    
</div>


<?php

//wp_enqueue_script( 'datatables' );

?>

<script>

jQuery(document).ready(function($) {

    $('#contactForm_submit').click(function()
    {
        var data_form = $('#contactForm').serialize();

        $('#ajax-indicator-masking').show();

        // Assign handlers immediately after making the request,
        // and remember the jqxhr object for this request
        var jqxhr = $.post( "https://elementinvader.com/support/createTicket", data_form, function(data) {
        
            $('.validation-messages').html('');

            if(data.alert == 'danger')
            {
                $.each( data.errors, function( key, value ) {
                    $('.validation-messages').append( "<p class=\"alert alert-"+data.alert+"\">"+value+"</p>" );
                });
            }
            else if(data.alert == 'success')
            {
                $('.validation-messages').append( "<p class=\"alert alert-"+data.alert+"\">"+data.message+"</p>" );
                $('#contactForm')[0].reset();
            }
            else
            {
                $('.validation-messages').append( "<p class=\"alert alert-"+data.alert+"\">"+data.message+"</p>" );
            }
            
        })
        .done(function(data) {
            //alert( "second success" );
        })
        .fail(function(data) {
            alert( "Error: " + data );
        })
        .always(function(data) {
            //alert( "finished" );
            $('#ajax-indicator-masking').hide();
        });

        return false;
    });

});

</script>

<?php $this->view('general/footer', $data); ?>










