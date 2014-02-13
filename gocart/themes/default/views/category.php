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

<?php if(!empty($category->description)): ?>
<div class="row">
    <div class="span12"><?php echo $category->description; ?></div>
</div>
<?php endif; ?>


<?php
    if(isset($category)):?>
        <h1><?php echo $category->name; ?></h1>
<?php endif;?>
        
<?php
      if(isset($city_name)):?>
        <h1><?php echo ucfirst($city_name); ?></h1>
<?php endif;?>
        
<?php
      if(isset($search_word)):?>
        <h1><?php echo $search_word; ?></h1>
<?php endif;?>        
        
<?php if(count($products) == 0):?>
        <h2 style="margin:50px 0px; text-align:center;">
            <?php echo lang('no_products');?>
        </h2>
<?php elseif(count($products) > 0):?>        
        
<div class="row" style="margin-top:20px; margin-bottom:15px">
            <div class="span9">
                <?php //echo $this->pagination->create_links();?>&nbsp;
            </div>
            <div class="span3 pull-right">
                <select class="span3" id="sort_products" onchange="window.location='<?php echo site_url(uri_string());?>/'+$(this).val();">
                    <option value=''><?php echo lang('default');?></option>
                    <option<?php echo(!empty($_GET['by']) && $_GET['by']=='name/asc')?' selected="selected"':'';?> value="?by=name/asc"><?php echo lang('sort_by_name_asc');?></option>
                    <option<?php echo(!empty($_GET['by']) && $_GET['by']=='name/desc')?' selected="selected"':'';?>  value="?by=name/desc"><?php echo lang('sort_by_name_desc');?></option>
                </select>
            </div>
</div>
        

<div class="container">
    <div class="row">
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
                            <?php foreach ($products as $one_record) { ?>
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
                                                <a class="thumbnail" href="<?php echo SITE_URL . $one_record->slug; ?>">
                                                    <?php echo $photo; ?>
                                                </a>
                                                <div>
                                                    <?php echo ucfirst($one_record->escort_city); ?>
                                                </div>
                                                <div>
                                                    <a title="<?php echo $one_record->escort_phone; ?>" href="<?php echo SITE_URL. $one_record->slug; ?>"><?php echo $one_record->escort_phone; ?></a>
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

    </div>
</div>

<?php echo $this->pagination->create_links();?>&nbsp;

<?php endif;?>

<div class="clear"></div><br/>