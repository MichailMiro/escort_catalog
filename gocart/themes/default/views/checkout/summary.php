	
<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th style="width:10%;"><?php echo lang('sku');?></th>
				
				<!-- <th style="width:10%;"><?php //echo lang('price');?></th> -->
				<th>Link</th>
				<th style="width:10%;"><?php // echo lang('quantity');?></th>
				<!-- <th style="width:8%;"><?php //echo lang('totals');?></th> -->
			</tr>
		</thead>
		
		<tbody>
			<?php
			$subtotal = 0;

			foreach ($this->go_cart->contents() as $cartkey=>$product):?>
                    
                   
				<tr>
					<td><?php echo $product['sku']; ?></td>
					
					<!--<td><?php //echo format_currency($product['price']);?></td>-->
					<td>
                                            <a class="thumbnail_cart" href="<?php echo site_url(implode('/', $base_url).'/'.$product['slug']); ?>">
                                                <?php echo $product['name']; ?>
                                            </a>
                                            
						
					</td>
                                        <td style="white-space:nowrap">
					<input type="hidden" name="cartkey[<?php echo $cartkey;?>]" value="1"/>
                                        <button class="btn btn-danger" type="button" onclick="if(confirm('<?php echo lang('remove_item');?>')){window.location='<?php echo site_url('cart/remove_item/'.$cartkey);?>';}"><i class="icon-remove icon-white"></i></button>
                                        </td>    
                                        </tr>
			<?php endforeach;?>
		</tbody>
	</table>
