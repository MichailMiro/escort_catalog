<div class="row">
    <div class="span12">
        <?php //$this->banners->show_collection(1, 5);?>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#last-create').dataTable({
            "bPaginate": true,
            "iDisplayLength": 12,
            "sPaginationType": "full_numbers",
            "bFilter": false,
            "bLengthChange": false,
            "bInfo": false
//                                        "bProcessing": true,
//                                        "bServerSide": true,
//                                        "sAjaxSource": "<?php echo SITE_URL; ?>user/books"
        });

//        $('#last-viewed').dataTable({
//            "bPaginate": true,
//            "iDisplayLength": 6,
//            "sPaginationType": "full_numbers",
//            "bFilter": false,
//            "bLengthChange": false,
//            "bInfo": false
//        });


    });
</script>

<style>
    .list-group {
font: 11px verdana;
    }

    .list-group li {
        list-style: none;
        float: left;
        margin-bottom: 20px;
        margin-right: 10px;
        height: 235px;
    }
    
    .list-group li a.thumbnail {
        margin-right: 10px;
        display: block;
        
    }
</style>

<h1>Last created</h1>

<div class="container">
    <div class="row">
<!--        <table class="table table-striped" id="last-create" >
            <thead><tr><th></th></tr></thead>
            <tbody>   
                <tr>
                    <td> -->
                        <?php 
                                                function time_elapsed_string($ptime)
                                                {
                                                    
                                                    $etime = time() - strtotime($ptime);

                                                    if ($etime < 1)
                                                    {
                                                        return '0 seconds';
                                                    }

                                                    $a = array( 12 * 30 * 24 * 60 * 60  =>  'year',
                                                                30 * 24 * 60 * 60       =>  'month',
                                                                24 * 60 * 60            =>  'day',
                                                                60 * 60                 =>  'hour',
                                                                60                      =>  'minute',
                                                                1                       =>  'second'
                                                                );

                                                    foreach ($a as $secs => $str)
                                                    {
                                                        $d = $etime / $secs;
                                                        if ($d >= 1)
                                                        {
                                                            $r = round($d);
                                                            return $r . ' ' . $str . ($r > 1 ? 's' : '') . ' ago';
                                                        }
                                                    }
                                                }
                                            ?>
        
        
                        <ul class="list-group">
                            <?php foreach ($last_create as $one_record) { ?>
                                <?php //var_dump($one_record->images);die;?> 
                                <li class="float">
                                    <div class="span2 product">
                                        <div>
                                            <?php
                                            $photo = theme_img('no_picture.png', lang('no_image_available'));
                                            $one_record->images = array_values($one_record->images);

                                            if (!empty($one_record->images[0])) {
                                                $primary = $one_record->images[0];
                                                foreach ($one_record->images as $photo) {
                                                    if (isset($photo->primary)) {
                                                        $primary = $photo;
                                                    }
                                                }

                                                $photo = '<img src="' . base_url('uploads/images/medium/' . $primary->filename) . '" />';
                                            }
                                            ?>
                                            
                                            <div class="product-image">
                                                <a class="thumbnail" href="<?php echo $base_url . $one_record->slug; ?>">
                                                    <?php echo $photo; ?>
                                                </a>
                                                <div>
                                                    <?php echo ucfirst($one_record->escort_city); ?>
                                                </div>
                                                <div>
                                                    <a title="<?php echo $one_record->escort_phone; ?>" href="<?php echo $base_url. $one_record->slug; ?>"><?php echo $one_record->escort_phone; ?></a>
                                                </div>
                                                <div data-id="290571" class="additional-info">
                                                    <i title="" class="icon-eye-open tt" data-original-title="last ad found"></i> 
                                                    <?php echo time_elapsed_string($one_record->create_time);?>
                                                </div>
                                                
<!--                                                <div>
                                                    <?php //echo $one_record->name; ?>
                                                </div>-->
                                            </div>
                                        </div>
                                    </div>
                                </li> 
                            <?php } ?>
                        </ul>
<!--                    </td>
                </tr>
            </tbody>
        </table>-->
    </div>
</div>
<!--<div class="<?php //echo ($cols == 4)?'span9':'span6';?>">-->
                <?php echo $this->pagination->create_links();?>&nbsp;
<!--</div>-->


<div class="clear"></div><br/>
<h1>Last viewed</h1>

<div class="container">
    <table id="last-viewed" class="table table-striped">
<!--        <thead><tr><th></th></tr></thead>
        <tbody>
            <tr>-->
        
                <?php foreach ($last_review as $one_record) { ?>
                    <?php //var_dump($one_record->images);die; ?>  
                    <!--<td>-->     
                        <div class="span2 product">
                            <div>
                                <?php
                                $photo = theme_img('no_picture.png', lang('no_image_available'));
                                $one_record->images = array_values($one_record->images);

                                if (!empty($one_record->images[0])) {
                                    $primary = $one_record->images[0];
                                    foreach ($one_record->images as $photo) {
                                        if (isset($photo->primary)) {
                                            $primary = $photo;
                                        }
                                    }

                                    $photo = '<img src="' . base_url('uploads/images/medium/' . $primary->filename) . '" alt="' . $one_record->seo_title . '"/>';
                                }
                                ?>
                                <div class="product-image">
                                    <a class="thumbnail" href="<?php echo $base_url. $one_record->slug; ?>">
                                        <?php echo $photo; ?>
                                    </a>
                                    <?php //echo time_elapsed_string($one_record->review_time);?>
                                </div>
                                <!--                        <h5 style="margin-top:5px;">
                                                                    <a href="<?php //echo site_url(implode('/', $base_url).'/'.$product->slug);            ?>"><?php //echo $product->name;           ?></a>
                                                                <a href="<?php echo site_url(implode('/', $base_url) . '/' . $one_record->slug); ?>"><?php echo $one_record->name; ?></a>
                                <?php if ($this->session->userdata('admin')): ?>
                                                                                                            <a class="btn" title="<?php echo lang('edit_product'); ?>" href="<?php echo site_url($this->config->item('admin_folder') . '/products/form/' . $one_record->id); ?>"><i class="icon-pencil"></i></a>
                                <?php endif; ?>
                                                            </h5>-->
                            </div>
                        </div>
                    <!--</td>-->
                <?php } ?>
<!--            </tr>
        </tbody>
    </table>-->
</div>


<?php $this->banners->show_collection(2, 3, '3_box_row'); ?>