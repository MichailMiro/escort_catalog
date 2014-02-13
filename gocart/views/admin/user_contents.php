<script type="text/javascript">
function areyousure()
{
	return confirm('Are you sure?');
}
</script>



<table class="table table-striped">
	<thead>
		<tr>
			<th>E-Mail</th>
			<th>Url</th>
			<th>Added</th>
			
			<th></th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($contents as $content):?>
		<tr>
			<td><a href="mailto:<?php echo $content->email;?>"><?php echo $content->email; ?></a></td>
			<td><?php echo $content->url; ?></td>
			<td><?php echo $content->added; ?></td>
			<td>
				<div class="btn-group" style="float:right;">
					<a class="btn" href="<?php echo site_url($this->config->item('admin_folder').'/user_content/import_content/'.$content->id);?>"><i class="icon-pencil"></i> Import</a>	
					<a class="btn btn-danger" href="<?php echo site_url($this->config->item('admin_folder').'/user_content/delete_content/'.$content->id); ?>" onclick="return areyousure();"><i class="icon-trash icon-white"></i> Delete</a>
				</div>
			</td>
		</tr>
<?php endforeach; ?>
	</tbody>
</table>