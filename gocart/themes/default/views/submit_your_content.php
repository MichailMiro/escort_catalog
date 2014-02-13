<?php
$message	= array('id'=>'contact_message', 'class'=>'span6', 'name'=>'message', 'value'=> '');

$email		= array('id'=>'contact_email', 'class'=>'span3', 'name'=>'email', 'value'=>'');

$url		= array('id'=>'contact_url', 'class'=>'span3', 'name'=>'url', 'value'=>'');

?>
<div class="row" style="margin-top:50px;">
	<div class="span6 offset3">
		<div class="page-header">
			<h1>Contact Us</h1>
		</div>
                <?php if(isset($contact_message)){?>
                    <h2 style="color:green;"><?php echo $contact_message;?></h2>
                <?php } ?>    
		<?php echo form_open('cart/submit_your_content'); ?>
			<input type="hidden" name="submitted" value="submitted" />
			
                        <fieldset>
				
			
				<div class="row">
					<div class="span3">
						<label for="contact_email">E-Mail</label>
						<?php echo form_input($email);?>
					</div>
				</div>
			
                                <div class="row">
					<div class="span3">
						<label for="contact_message">Your Message</label>
						<?php echo form_textarea($message);?>
					</div>
				</div>
                            
                                <div class="row">
					<div class="span3">
						<label for="contact_url">Url</label>
						<?php echo form_input($url);?>
					</div>
				</div>
			
				<input type="submit" value="Send" class="btn btn-primary" />
			</fieldset>
		</form>
	
		
	</div>
</div>