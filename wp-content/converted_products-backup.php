<?php
    function csv_to_array($filename='', $delimiter=',')
    {
        if(!file_exists($filename) || !is_readable($filename))
            return FALSE;
    
        $header = NULL;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== FALSE)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
            {   
                if(!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }
        return $data;
    }
    $woo_table_fields = array(
			  'post_title',
			  'post_date',
			  'sku',
			  'stock',
			  'regular_price',
			  'tax_status',
			  'images',
			  'tax:product_type',
			  'tax:product_cat',
			  'meta:total_sales',
			  'attribute:pa_cap',
			  'attribute:pa_size',
			  'attribute:pa_size-ml'
		    );
    $glass_bottles_csv_arr = csv_to_array("uploadedfiles/".$_POST['csv-filename']);
    $products_arr = array();
    $counter = 0;
    $arr_count = array();
    foreach($glass_bottles_csv_arr as $key => $val){
        $arr = array();
        $arr['post_title'] = $val[$_POST['post_title']];
	$category = strtolower($val[$_POST['tax_product_cat']]);
        $arr['post_date'] = '2013-07-25 02:43:08';
        $arr['sku'] = $val[$_POST['sku']];
	$arr['stock'] = '';
        $arr['regular_price'] = str_replace(',','',$val[$_POST['regular_price']]);
	$arr['tax_status'] = 'none';
	if($_POST['images1']!='' && $_POST['images2']!='' && $_POST['images3']!=''){
	    if($val[$_POST['images3']] == ''){
		$images = $val[$_POST['images1']].'-'.$val[$_POST['images2']];
	    }else{
		$images = $val[$_POST['images1']].'-'.$val[$_POST['images2']].'-+-'.$val[$_POST['images3']];
	    }
	}else if($_POST['images1']!='' && $_POST['images2']!='' && $_POST['images3']==''){
	    $images = $val[$_POST['images1']].'-'.$val[$_POST['images2']];
	}else if($_POST['images1']!='' && $_POST['images2']=='' && $_POST['images3']==''){
	    $images = $val[$_POST['images1']];
	}
	$arr['images'] = 'http://localhost/wordpress/wp-content/product-images/'.$category.'/'.$val[$_POST['post_title']].'/'.$images.'.jpg';
	//$arr['images'] = 'http://localhost/wordpress/wp-content/product-images/'.$images.'.jpg';
        //$arr['images'] = 'http://localhost/wordpress/wp-content/product-images/'.$val[$_POST['images']].'.jpg';
	$arr['tax:product_type'] = 'variable';
        $arr['tax:product_cat'] = $val[$_POST['tax_product_cat']];
	$arr['meta:total_sales'] = '0';
	if($_POST['attribute_pa_cap']!=''){
	    $arr['attribute:pa_cap'] = $val[$_POST['attribute_pa_cap']];
	}
	if($_POST['attribute_pa_size']!=''){
	    $arr['attribute:pa_size'] = $val[$_POST['attribute_pa_size']];
	}
	if($_POST['attribute_pa_size-ml']!=''){
	    $arr['attribute:pa_size-ml'] = $val[$_POST['attribute_pa_size-ml']];
	}
	if($_POST['attribute_pa_size-g']!=''){
	    $arr['attribute:pa_size-g'] = $val[$_POST['attribute_pa_size-g']];
	}
        
        if($arr['sku'] != ''){
            
            foreach($woo_table_fields as $woo_table_field_val){
                
                if(isset($arr[$woo_table_field_val])){
                    
                    $products_arr[$counter][$woo_table_field_val] = $arr[$woo_table_field_val];
                    
                }else{
                    
                    $products_arr[$counter][$woo_table_field_val] = '';
                    
                }
		if($woo_table_field_val == "post_title"){
		    array_push($arr_count,$arr[$woo_table_field_val]);
		    $directory = $arr[$woo_table_field_val];
		    if (!file_exists('product-images/'.$category.'/'.$directory)) {
			mkdir('product-images/'.$category.'/'.$directory, 0777, true);
		    }
		}
		if($woo_table_field_val == "images"){
		
		    //copy("product-images/442487-c8452_01.jpg", str_replace("http://localhost/wordpress/wp-content/product-images/", "product-images/", $arr[$woo_table_field_val]));
		    copy("uploadedfiles/default-image.jpg", str_replace("http://localhost/wordpress/wp-content/product-images/".$category.'/'.$directory, 'product-images/'.$category.'/'.$directory, $arr[$woo_table_field_val]));
		}
		
            }	
            
            $counter++;
            
        }
    }
    $asdf = array_count_values($arr_count);
    $ff = count($products_arr);
    $new_products_arr = array();
    foreach($asdf as $key => $value){
	if($value == 1){
	    foreach($products_arr as $key1 => $value1){
		foreach($value1 as $key2 => $value2){
		    if($key2 == 'post_title' && $value2 == $key){
			$products_arr[$key1]['post_title'] = $products_arr[$key1]['attribute:pa_size-ml'].' '.$products_arr[$key1]['post_title'].' '.$products_arr[$key1]['attribute:pa_cap'];
			$products_arr[$key1]['tax:product_type'] = 'simple';
		    }
		}
	    }
	}else if($value > 1){
	    foreach($products_arr as $key1 => $value1){
		foreach($value1 as $key2 => $value2){
		    if($key2 == 'post_title' && $value2 == $key){
			$products_arr[$key1]['sku'] = '';
			$products_arr[$key1]['regular_price'] = '';
			$products_arr[$key1]['images'] = '';
			$products_arr[$key1]['attribute:pa_cap'] = '';
			$products_arr[$key1]['attribute:pa_size-ml'] = '';
		    }
		}
	    }
	}
    }
    $another_array = array();
    $counter1 = 0;
    foreach($products_arr as $key1 => $value1){
	if (!in_array($value1, $another_array)) {
	    array_push($another_array,$value1);
	}
    }
    //echo "<pre>";
    //print_r($another_array);
    //print_r($asdf);
    header('Content-Type: application/excel');
    header('Content-Disposition: attachment; filename="'.$_POST['csv-filename'].'"');
    $fp = fopen('php://output', 'w');
    
    $conbine = array($woo_table_fields);
    foreach($conbine as $val) {
	fputcsv($fp, $val);
    }
    foreach($another_array as $arr) {
	
	fputcsv($fp, $arr);
    }
?>