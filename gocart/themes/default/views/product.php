<script>
    window.onload = function()
    {
        $('.product').equalHeights();
    }
</script>

<script type="text/javascript">
	$(document).ready(function() {
		$(".fancybox").fancybox();
                
                $('#remove-form').submit(function(){
                    $.post( $('#remove-form').attr('action'), 
                            $( "#remove-form" ).serialize()) 
                            .done(function( data ) {
                                if( data.trim() == '1' ){
                                    $('#send-message').show();
                                    setTimeout(function() { 
                                        $('#send-message').hide();
                                        //$('.fancybox').close();
                                        $.fancybox.close();
                                    }, 2000)
                                }else{
                                    $('#send-error').show();
                                    setTimeout(function() { 
                                        $('#send-error').hide();
                                        
                                    }, 2000)
                                }
                            });
                    return false;
                });
        });
        
</script>

<div id="delete-request" style="display:none;">
    <p id="send-message" style="color:green;display:none;">Successful.</p>
    <p id="send-error" style="color:red;display:none;">Problem!</p>
    <form id="remove-form" action="<?php echo SITE_URL;?>cart/add_remove_request" method="post">
        <table>
            <tr>
                <td>Name:</td>
                <td><input type="text" name="user_name" value=""></td>
            </tr>
            <tr>
                <td>Email:</td>
                <td><input type="text" name="email" value=""></td>
            </tr>
            <tr>
                <td>Phone:</td>
                <td><input type="text" name="phone" value=""></td>
            </tr>
            <tr>
                <td>Reason to removal:</td>
                <td><textarea name="reason"></textarea></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" value="Send"></td>
            </tr>
            <input type="hidden" value="<?php echo $product->id;?>" name="product_id">
        </table>    
    </form>    
</div>

<div class="row">
    <div class="span4">
        
        <div class="row">
            <div class="span4" id="primary-img">
                <?php
                $photo  = theme_img('no_picture.png', lang('no_image_available'));
                $product->images    = array_values($product->images);

                if(!empty($product->images[0]))
                {
                    $primary    = $product->images[0];
                    foreach($product->images as $photo)
                    {
                        if(isset($photo->primary))
                        {
                            $primary    = $photo;
                        }
                    }

                    $photo  = '<img class="responsiveImage" src="'.base_url('uploads/images/medium/'.$primary->filename).'" alt="'.$product->seo_title.'"/>';
                }
                echo $photo
                ?>
            </div>
        </div>
        <?php if(!empty($primary->caption)):?>
        <div class="row">
            <div class="span4" id="product_caption">
                <?php echo $primary->caption;?>
            </div>
        </div>
        <?php endif;?>
        <?php if(count($product->images) > 1):?>
        <div class="row">
            <div class="span4 product-images">
                <?php foreach($product->images as $image):?>
                <img class="span1" onclick="$(this).squard('390', $('#primary-img'));" src="<?php echo base_url('uploads/images/medium/'.$image->filename);?>"/>
                <?php endforeach;?>
            </div>
        </div>
        <?php endif;?>
    </div>
    <div class="span8 pull-right">
        
        <div class="row">
            <div class="span8">
                <div class="page-header">
                    <h2 style="font-weight:normal">
                        <?php echo $product->name;?>
                        <?php if($this->session->userdata('admin')): ?>
                        <a class="btn" title="<?php echo lang('edit_product'); ?>" href="<?php echo  site_url($this->config->item('admin_folder').'/products/form/'.$product->id); ?>"><i class="icon-pencil"></i></a>
                        <?php endif; ?>
<!--                        <span class="pull-right">
                            <?php //if($product->saleprice > 0):?>
                                <small><?php //echo lang('on_sale');?></small>
                                <span class="product_price"><?php //echo format_currency($product->saleprice); ?></span>
                            <?php //else: ?>
                                <small><?php //echo lang('product_price');?></small>
                                <span class="product_price"><?php //echo format_currency($product->price); ?></span>
                            <?php //endif;?>
                        </span>-->
                    </h2>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="span8">
                <?php echo $product->excerpt;?>
            </div>
        </div>
        
        <div class="row" style="margin-top:15px; margin-bottom:15px;">
<!--            <div class="span4 sku-pricing">
                <?php //if(!empty($product->sku)):?><div><?php //echo lang('sku');?>: <?php //echo $product->sku; ?></div><?php //endif;?>&nbsp;
            </div>-->
            <?php if((bool)$product->track_stock && $product->quantity < 1 && config_item('inventory_enabled')):?>
            <div class="span4 out-of-stock">
                <div><?php echo lang('out_of_stock');?></div>
            </div>
            <?php endif;?>
        </div>
        
        <div class="row">
            <div class="span8">
                <div class="product-cart-form">
                    <?php echo form_open('cart/add_to_cart', 'class="form-horizontal"');?>
                    <input type="hidden" name="cartkey" value="<?php echo $this->session->flashdata('cartkey');?>" />
                    <input type="hidden" name="id" value="<?php echo $product->id?>"/>
                    <fieldset>
                    <?php if(count($options) > 0): ?>
                        <?php foreach($options as $option):
                            $required   = '';
                            if($option->required)
                            {
                                $required = ' <p class="help-block">Required</p>';
                            }
                            ?>
                            <div class="control-group">
                                <label class="control-label"><?php echo $option->name;?></label>
                                <?php
                                /*
                                this is where we generate the options and either use default values, or previously posted variables
                                that we either returned for errors, or in some other releases of Go Cart the user may be editing
                                and entry in their cart.
                                */

                                //if we're dealing with a textfield or text area, grab the option value and store it in value
                                if($option->type == 'checklist')
                                {
                                    $value  = array();
                                    if($posted_options && isset($posted_options[$option->id]))
                                    {
                                        $value  = $posted_options[$option->id];
                                    }
                                }
                                else
                                {
                                    if(isset($option->values[0]))
                                    {
                                        $value  = $option->values[0]->value;
                                        if($posted_options && isset($posted_options[$option->id]))
                                        {
                                            $value  = $posted_options[$option->id];
                                        }
                                    }
                                    else
                                    {
                                        $value = false;
                                    }
                                }

                                if($option->type == 'textfield'):?>
                                    <div class="controls">
                                        <input type="text" name="option[<?php echo $option->id;?>]" value="<?php echo $value;?>" class="span4"/>
                                        <?php echo $required;?>
                                    </div>
                                <?php elseif($option->type == 'textarea'):?>
                                    <div class="controls">
                                        <textarea class="span4" name="option[<?php echo $option->id;?>]"><?php echo $value;?></textarea>
                                        <?php echo $required;?>
                                    </div>
                                <?php elseif($option->type == 'droplist'):?>
                                    <div class="controls">
                                        <select name="option[<?php echo $option->id;?>]">
                                            <option value=""><?php echo lang('choose_option');?></option>

                                        <?php foreach ($option->values as $values):
                                            $selected   = '';
                                            if($value == $values->id)
                                            {
                                                $selected   = ' selected="selected"';
                                            }?>

                                            <option<?php echo $selected;?> value="<?php echo $values->id;?>">
                                                <?php echo($values->price != 0)?' (+'.format_currency($values->price).') ':''; echo $values->name;?>
                                            </option>

                                        <?php endforeach;?>
                                        </select>
                                        <?php echo $required;?>
                                    </div>
                                <?php elseif($option->type == 'radiolist'):?>
                                    <div class="controls">
                                        <?php foreach ($option->values as $values):

                                            $checked = '';
                                            if($value == $values->id)
                                            {
                                                $checked = ' checked="checked"';
                                            }?>
                                            <label class="radio">
                                                <input<?php echo $checked;?> type="radio" name="option[<?php echo $option->id;?>]" value="<?php echo $values->id;?>"/>
                                                <?php echo($values->price != 0)?'(+'.format_currency($values->price).') ':''; echo $values->name;?>
                                            </label>
                                        <?php endforeach;?>
                                        <?php echo $required;?>
                                    </div>
                                <?php elseif($option->type == 'checklist'):?>
                                    <div class="controls">
                                        <?php foreach ($option->values as $values):

                                            $checked = '';
                                            if(in_array($values->id, $value))
                                            {
                                                $checked = ' checked="checked"';
                                            }?>
                                            <label class="checkbox">
                                                <input<?php echo $checked;?> type="checkbox" name="option[<?php echo $option->id;?>][]" value="<?php echo $values->id;?>"/>
                                                <?php echo($values->price != 0)?'('.format_currency($values->price).') ':''; echo $values->name;?>
                                            </label>
                                            
                                        <?php endforeach; ?>
                                    </div>
                                    <?php echo $required;?>
                                <?php endif;?>
                                </div>
                        <?php endforeach;?>
                    <?php endif;?>
                    
                    <div class="control-group">
                        <label class="control-label"><?php //echo lang('quantity') ?></label>
                        <div class="controls">
                            <?php if(!config_item('inventory_enabled') || config_item('allow_os_purchase') || !(bool)$product->track_stock || $product->quantity > 0) : ?>
                                <?php if(!$product->fixed_quantity) : ?>
                                    <input class="span2" type="hidden" name="quantity" value="1"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <?php endif; ?>
                                <?php if(isset($you_watch_this) && $you_watch_this){ ?>    
                                    You watch this Ads.
                                <?php }else{ ?>
                                    <button class="btn btn-primary btn-large" type="submit" value="submit">
                                        <!--<i class="icon-shopping-cart icon-white"></i>--> 
                                            <?php echo lang('form_add_to_cart');?>
                                    </button>
                                <?php }?>
                            <?php endif;?>
                        </div>
                    </div>
                    
                    </fieldset>
                    </form>
                </div>
    
            </div>
        </div>
        
         <div class="row" style="margin-top:15px;">
            <div class="span8">
                <button  class="btn btn-primary btn-large fancybox" value="submit" href="#delete-request">
                                        <!--<i class="icon-shopping-cart icon-white"></i>--> 
                                            Request to delete
                                    </button>
            </div>
        </div>
        
        <div class="row" style="margin-top:15px;">
            <div class="span8">
                <?php echo $product->description; ?>
            </div>
        </div>
        
       
        
    </div>
    
    <?php if(!empty($product->related_products)):?>
    <div class="related_products">
        <div class="row">
            <div class="span4">
                <h3 style="margin-top:20px;"><?php echo lang('related_products_title');?></h3>
                <ul class="thumbnails"> 
                <?php foreach($product->related_products as $relate):?>
                    <li class="span2 product">
                        <?php
                        $photo  = theme_img('no_picture.png', lang('no_image_available'));
                        
                        
                        
                        $relate->images = array_values((array)json_decode($relate->images));
                        
                        if(!empty($relate->images[0]))
                        {
                            $primary    = $relate->images[0];
                            foreach($relate->images as $photo)
                            {
                                if(isset($photo->primary))
                                {
                                    $primary    = $photo;
                                }
                            }

                            $photo  = '<img src="'.base_url('uploads/images/thumbnails/'.$primary->filename).'" alt="'.$relate->seo_title.'"/>';
                        }
                        ?>
                        <a class="thumbnail" href="<?php echo site_url($relate->slug); ?>">
                            <?php echo $photo; ?>
                        </a>
                        <h5 style="margin-top:5px;"><a href="<?php echo site_url($relate->slug); ?>"><?php echo $relate->name;?></a>
                        <?php if($this->admin_session->userdata('admin')): ?>
                        <a class="btn" title="<?php echo lang('edit_product'); ?>" href="<?php echo  site_url($this->config->item('admin_folder').'/products/form/'.$relate->id); ?>"><i class="icon-pencil"></i></a>
                        <?php endif; ?>
                        </h5>

<!--                        <div>
                            <?php //if($relate->saleprice > 0):?>
                                <span class="price-slash"><?php //echo lang('product_reg');?> <?php //echo format_currency($relate->price); ?></span>
                                <span class="price-sale"><?php //echo lang('product_sale');?> <?php //echo format_currency($relate->saleprice); ?></span>
                            <?php //else: ?>
                                <span class="price-reg"><?php //echo lang('product_price');?> <?php //echo format_currency($relate->price); ?></span>
                            <?php //endif; ?>
                        </div>-->
                        <?php if((bool)$relate->track_stock && $relate->quantity < 1 && config_item('inventory_enabled')) { ?>
                            <div class="stock_msg"><?php echo lang('out_of_stock');?></div>
                        <?php } ?>
                    </li>
                <?php endforeach;?>
                </ul>
            </div>
        </div>
    </div>
    <?php endif;?>
</div>
<script>
$(function(){ 
    $('.category_container').each(function(){
        $(this).children().equalHeights();
    }); 
});
</script>