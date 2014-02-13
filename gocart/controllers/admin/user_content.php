<?php
class User_content extends Admin_Controller
{
	//these are used when editing, adding or deleting an admin
	var $admin_id		= false;
	var $current_admin	= false;
	function __construct()
	{
		parent::__construct();
        
		$this->auth->check_access('Admin', true);
		
		$this->load->model(array('Product_model'));
		$this->load->helper('form');
		$this->lang->load('product');
	}

	function index()
	{
		$data['page_title']	= 'Content from user';
		$data['contents']		= $this->Product_model->get_all_сontents();
                
		$this->view($this->config->item('admin_folder').'/user_contents', $data);
	}
        
        
        function parse_phone($body){
            $phone = false;

            # Strip out special html entities as sometimes they are found as part of the phone number
            $patterns1 = array('/eleven/i','/twelve/i','/thirteen/i','/fourteen/i','/fifteen/i','/sixteen/i','/seventeen/i','/eighteen/i','/nineteen/i',
              '/ten/i','/twenty/i','/thirty/i','/fourty/i','/fifty/i','/sixty/i','/seventy/i','/eighty/i','/ninety/i',
              '/one(\s|-){0,1}hundred/i','/two(\s|-){0,1}hundred/i','/three(\s|-){0,1}hundred/i','/four(\s|-){0,1}hundred/i','/five(\s|-){0,1}hundred/i','/six(\s|-){0,1}hundred/i','/seven(\s|-){0,1}hundred/i','/eight(\s|-){0,1}hundred/i','/nine(\s|-){0,1}hundred/i',
              '/one(\s|-){0,1}thousand/i','/two(\s|-){0,1}thousand/i','/three(\s|-){0,1}thousand/i','/four(\s|-){0,1}thousand/i','/five(\s|-){0,1}thousand/i','/six(\s|-){0,1}thousand/i','/seven(\s|-){0,1}thousand/i','/eight(\s|-){0,1}thousand/i','/nine(\s|-){0,1}thousand/i',
              '/&#\d{2,5};/i', '/\*82/i');
            $replacements1 = array('11','12','13','14','15','16','17','18','19',
              '10','20','30','40','50','60','70','80','90',
              '100','200','300','400','500','600','700','800','900',
              '1000','2000','3000','4000','5000','6000','7000','8000','9000',
              '','');

            $body_for_regex = preg_replace($patterns1, $replacements1, $body);

            if(preg_match(':([^1|one|uno](\d|\(|\sone|\stwo|\sthree|\sfour|\sfive|\ssix|\sseven|\seight|\snine|\szero|\suno|\sdos|\stres|\scuatro|\scinco|\sseis|\ssiete|\socho|\snueve)(\D{0,4})){1}((\d|one|two|three|four|five|six|seven|eight|nine|zero|uno|dos|tres|cuatro|cinco|seis|siete|ocho|nueve|too|to|for|fo|oh|o|fiv|8ight)(\D{0,4})){9,10}:i', $body_for_regex, $matches)){
            $patterns = array('/zero/i','/one/i','/two/i','/three/i','/four/i','/five/i','/six/i','/seven/i','/eight/i','/nine/i',
              '/cero/i','/uno/i','/dos/i','/tres/i','/cuatro/i','/cinco/i','/seis/i','/siete/i','/ocho/i', '/nueve/i',
              '/too/i', '/to/i', '/for/i', '/fo/i', '/oh/i', '/o/i', '/fiv/i', '/8ight/i');
            $replacements = array('0','1','2','3','4','5','6','7','8','9',
              '0','1','2','3','4','5','6','7','8','9',
              '2','2','4','4','0','0','5','8');

            $matchedIF = $matches[0];
            $phone = preg_replace($patterns, $replacements, preg_replace('/^1|^one/i','',trim($matches[0])));
            $phone = preg_replace(':\D:', '', $phone);
            $phone = substr($phone,0,3).'-'.substr($phone,3,3).'-'.substr($phone,6,4);
            }
            return $phone;
        }
        
        
        function import_content($id)
	{
                //date_default_timezone_set(TIME_ZONE);
                $this->load->model(array('Option_model', 'Category_model', 'Product_model'));
                $this->load->helper('text');
                $this->load->model('Routes_model');
                $this->load->library('curl');
                $this->load->helper('simple_html_dom_helper');
                
                $content = $this->Product_model->get_one_сontent($id);    
                
                $proxy = array(
                    '190.144.115.146:8080'
                );
                $number = array_rand($proxy);
                
                $url_to_adv = explode('/',$content->url);
                $code = $url_to_adv[count($url_to_adv)-1];
                
                $this->curl->create($content->url);
                //$this->curl->proxy('190.144.115.146', 8080);
                $curl_scraped_page =  $this->curl->execute();
                                
//                $ch = curl_init();
//                curl_setopt($ch, CURLOPT_URL,$content->url);
//                //curl_setopt($ch, CURLOPT_PROXY, $proxy[$number]);
//                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
//                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//                curl_setopt($ch, CURLOPT_HEADER, 1);
//                $curl_scraped_page = curl_exec($ch);

                $adv_html = str_get_html($curl_scraped_page);
                
                if($curl_scraped_page){
            
                    $name = $adv_html->find('h1',0)->innertext;
                    $city = $adv_html->find('span.city',0)->innertext;
                    $city = explode(',',$city);
                    $city = $city[0];
                    
                    //var_dump($name);die;
                    
                    $text = $adv_html->find('div.postingBody',0)->innertext;
                    $phone = $this->parse_phone($text);
                    if(!$this->Product_model->check_list($phone) && !$this->Product_model->check_sku($code)){

                    //$sql = "SELECT * FROM adv WHERE code='".$code."'";
                    //$result = mysql_query($sql);
                    //if(!$result && !mysql_num_rows($result)){
                    //if(!$result){                        
                        if(!file_exists(REAL_PATH.'/uploads/images/thumbnails/'.$city)){
                            mkdir(REAL_PATH.'/uploads/images/thumbnails/'.$city);
                            chmod(REAL_PATH.'/uploads/images/thumbnails/'.$city,0777);
                        }
                        if(!file_exists(REAL_PATH.'/uploads/images/small/'.$city)){
                            mkdir(REAL_PATH.'/uploads/images/small/'.$city);
                            chmod(REAL_PATH.'/uploads/images/small/'.$city,0777);
                        }
                        if(!file_exists(REAL_PATH.'/uploads/images/medium/'.$city)){
                            mkdir(REAL_PATH.'/uploads/images/medium/'.$city);
                            chmod(REAL_PATH.'/uploads/images/medium/'.$city,0777);
                        }
                        if(!file_exists(REAL_PATH.'/uploads/images/full/'.$city)){
                            mkdir(REAL_PATH.'/uploads/images/full/'.$city);
                            chmod(REAL_PATH.'/uploads/images/full/'.$city,0777);
                        }

                        if(!file_exists(REAL_PATH.'/uploads/images/thumbnails/'.$city.'/'.$code)){
                            mkdir(REAL_PATH.'/uploads/images/thumbnails/'.$city.'/'.$code);
                            chmod(REAL_PATH.'/uploads/images/thumbnails/'.$city.'/'.$code,0777);
                        }
                        if(!file_exists(REAL_PATH.'/uploads/images/small/'.$city.'/'.$code)){
                            mkdir(REAL_PATH.'/uploads/images/small/'.$city.'/'.$code);
                            chmod(REAL_PATH.'/uploads/images/small/'.$city.'/'.$code,0777);
                        }
                        if(!file_exists(REAL_PATH.'/uploads/images/medium/'.$city.'/'.$code)){
                            mkdir(REAL_PATH.'/uploads/images/medium/'.$city.'/'.$code);
                            chmod(REAL_PATH.'/uploads/images/medium/'.$city.'/'.$code,0777);
                        }
                        if(!file_exists(REAL_PATH.'/uploads/images/full/'.$city.'/'.$code)){
                            mkdir(REAL_PATH.'/uploads/images/full/'.$city.'/'.$code);
                            chmod(REAL_PATH.'/uploads/images/full/'.$city.'/'.$code,0777);
                        }
                        
                        foreach($adv_html->find('img') as $key=>$element){
                            $unique_id = uniqid() . time() . uniqid();
                            if ($key == 0) {
                                $img[$unique_id]['primary'] = true;
                            }
                            $img[$unique_id]['filename'] = $city.'/'.$code.'/'.basename($element->src);
                            
                            $homepage = file_get_contents($element->src);
                            file_put_contents(REAL_PATH.'/uploads/images/medium/'.$city.'/'.$code.'/'.basename($element->src),$homepage);
                            chmod(REAL_PATH.'/uploads/images/medium/'.$city.'/'.$code.'/'.basename($element->src),0777);
                            
                            $large_image = str_replace('/medium/','/large/',$element->src);
                            $homepage = file_get_contents($large_image);
                            file_put_contents(REAL_PATH.'/uploads/images/full/'.$city.'/'.$code.'/'.basename($element->src),$homepage);
                            chmod(REAL_PATH.'/uploads/images/full/'.$city.'/'.$code.'/'.basename($element->src),0777);
                            
                            $small_image = str_replace('/medium/','/small/',$element->src);
                            $homepage = file_get_contents($small_image);
                            file_put_contents(REAL_PATH.'/uploads/images/thumbnails/'.$city.'/'.$code.'/'.basename($element->src),$homepage);
                            chmod(REAL_PATH.'/uploads/images/thumbnails/'.$city.'/'.$code.'/'.basename($element->src),0777);
                            file_put_contents(REAL_PATH.'/uploads/images/small/'.$city.'/'.$code.'/'.basename($element->src),$homepage);
                            chmod(REAL_PATH.'/uploads/images/small/'.$city.'/'.$code.'/'.basename($element->src),0777);
                        
                            $img[$unique_id]['alt'] = "";
                            $img[$unique_id]['caption'] = "";
                        }
                        
                        $slug = sha1($code);
                        $route['slug'] = $slug;
                        $route_id = $this->Routes_model->save($route);

                        $save['id'] = "";
                        $save['sku'] = $code;
                        $save['name'] = $name;
                        $save['seo_title'] = $name;
                        $save['meta'] = "";
                        $save['description'] = $text;
                        $save['excerpt'] = "";
                        $save['price'] = "";
                        $save['saleprice'] = "";
                        $save['weight'] = "";
                        $save['track_stock'] = "1";
                        $save['fixed_quantity'] = "";
                        $save['quantity'] = "";
                        $save['shippable'] = "1";
                        $save['taxable'] = "0";
                        $save['enabled'] = "1";
                        $save['slug'] = $slug;
                        $save['route_id'] = $route_id;
                        $save['images'] = json_encode($img);
                        
                        $save['create_time'] = date('Y-m-d H:i:s');
                        
                        
                        $save['escort_phone'] = $phone;
                        $save['escort_link'] = $content->url;
                        $save['escort_city'] = $city;
                        
                        $product_id = $this->Product_model->save($save, $options = false, $categories = false);
                        
                        $this->Routes_model->update_route($route_id,'cart/product/'.$product_id);
                        if(strpos($content->url,'FemaleEscorts')!=FALSE){ $category_products['category_id']='1'; };
                        if(strpos($content->url,'TranssexualEscorts')!=FALSE){ $category_products['category_id']='2'; };
                        if(strpos($content->url,'BodyRubs')!=FALSE){$category_products['category_id']='3';};
                        $category_products['product_id'] = $product_id;
                        
                        $product_category_id = $this->db->insert('category_products', $category_products);
                        
                        $this->Product_model->change_added_status($id);
                    //}
                    }
                }else{
                    var_dump(1);die;
                }
            
                redirect($this->config->item('admin_folder').'/user_content');
	}
        
        function delete_content($id)
	{
                $this->Product_model->delete_сontent($id);
		redirect($this->config->item('admin_folder').'/user_content');
	}
        
        function import_city(){
            
            if($curl_scraped_page){
            $html = str_get_html($curl_scraped_page);
            //$html = file_get_html($url);
                foreach($html->find('div[class=communitylist] a') as $element){
                    $string = explode('.',$element->href);

                    if($string[0]!='http://www'){

                            $url_string = parse_url($string[0]);
                            $url_string_mas = explode('.',$url_string['host']);    

                            $sql = "SELECT * FROM city WHERE name='".$url_string_mas[0]."'";
                            $result = mysql_query($sql);
                            if(!mysql_num_rows($result)){
                                $number = array_rand($proxy);    
                                curl_setopt($ch, CURLOPT_URL,$string[0].'.backpage.com/FemaleEscorts/');
                                curl_setopt($ch, CURLOPT_PROXY, $proxy[$number]);
                                //curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);
                                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                curl_setopt($ch, CURLOPT_HEADER, 1);
                                $curl_scraped_page = curl_exec($ch);
                                $inner_page_html = str_get_html($curl_scraped_page);
                                //var_dump($inner_page_html->find('a.rssLink',0)->href);die;
                                if($curl_scraped_page){
                                $link1 = $inner_page_html->find('a.rssLink',0)->href;
                                }

                                $number = array_rand($proxy);

                                curl_setopt($ch, CURLOPT_URL,$string[0].'.backpage.com/TranssexualEscorts/');
                                curl_setopt($ch, CURLOPT_PROXY, $proxy[$number]);
                                //curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);
                                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                curl_setopt($ch, CURLOPT_HEADER, 1);
                                $curl_scraped_page = curl_exec($ch);
                                $inner_page_html = str_get_html($curl_scraped_page);
                                if($curl_scraped_page){
                                $link2 = $inner_page_html->find('a.rssLink',0)->href;
                                }

                                $number = array_rand($proxy);

                                curl_setopt($ch, CURLOPT_URL,$string[0].'.backpage.com/BodyRubs/');
                                curl_setopt($ch, CURLOPT_PROXY, $proxy[$number]);
                                //curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);
                                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                curl_setopt($ch, CURLOPT_HEADER, 1);
                                $curl_scraped_page = curl_exec($ch);
                                $inner_page_html = str_get_html($curl_scraped_page);
                                if($curl_scraped_page){
                                $link3 = $inner_page_html->find('a.rssLink',0)->href;
                                }

                                $sql = 'INSERT INTO city (name,link_escort, link_ts, link_bodyrubs) VALUES ( "'.$url_string_mas[0].'", "'.$link1.'", "'.$link2.'", "'.$link3.'" )';
                                mysql_query($sql);

                            }
                            flush();
                            ob_flush();
                            sleep(1);
                    }
                }
            }
        }
	
}