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

		    if(count($header)==count($row))

                    $data[] = array_combine($header, $row);

		    //echo '<pre>';

		    //echo count($header)."=".count($row)."<br/>"; 

            }

            fclose($handle);

        }

        return $data;

    }

    $woo_table_fields = array(

			  'Parent',

			  'post_parent',

			  'sku',

			  'stock',

			  'regular_price',

			  'images',

			  'meta:attribute_pa_cap',

			  'meta:attribute_pa_size',

			  'meta:attribute_pa_size-ml',

			  'meta:attribute_pa_size-g',

			  'meta:attribute_pa_fragrance-type',

		    );

    $glass_bottles_csv_arr = csv_to_array("uploadedfiles/".$_POST['csv-filename']);

    $woo_csv_arr = csv_to_array("uploadedfiles/".$_POST['csv-filenames']);

    $products_arr = array();

    $counter = 0;

    $arr_count = array();

    foreach($glass_bottles_csv_arr as $key => $val){

        $arr = array();

	

        $arr['Parent'] = trim($val[$_POST['Parent']]);

	$arr['post_parent'] = '';

	$category = trim($_POST['category']);

	//$directory = $val[$_POST['Parent']];

	//if (!file_exists('/product-images/'.$directory)) {

	    //mkdir('/product-images/'.$category.'/'.$directory, 0777, true);

	//}

        $arr['sku'] = trim($val[$_POST['sku']]);

	$arr['stock'] = '';

        $arr['regular_price'] = str_replace(',','',$val[$_POST['regular_price']]);

	if($_POST['images1']!='' && $_POST['images2']!='' && $_POST['images3']!=''){

	    if($val[$_POST['images3']] == ''){

		$images = trim($val[$_POST['images1']]).'-'.trim($val[$_POST['images2']]);

	    }else{

		$images = trim($val[$_POST['images1']]).'-'.trim($val[$_POST['images2']]).'-+-'.trim($val[$_POST['images3']]);

	    }

	}else if($_POST['images1']!='' && $_POST['images2']!='' && $_POST['images3']==''){

	    $images = trim($val[$_POST['images1']]).'-'.trim($val[$_POST['images2']]);

	}else if($_POST['images1']!='' && $_POST['images2']=='' && $_POST['images3']==''){

	    $images = trim($val[$_POST['images1']]);

	}

	$arr['images'] = 'http://grassefragrance.com/wordpress/wp-content/product-images/'.$category.'/'.trim($val[$_POST['Parent']]).'/'.$images.'.jpg';

	//$arr['images'] = $images;

	if($_POST['meta_attribute_pa_cap']!=''){

	    $arr['meta:attribute_pa_cap'] = strtolower(str_replace ('-+-','-',str_replace (' ','-',preg_replace('/\(|\)/','',trim($val[$_POST['meta_attribute_pa_cap']])))));

	}

	if($_POST['meta_attribute_pa_size']!=''){

	    $arr['meta:attribute_pa_size'] = trim($val[$_POST['meta_attribute_pa_size']]);

	}

	if($_POST['meta_attribute_pa_size-ml']!=''){

	    $arr['meta:attribute_pa_size-ml'] = trim($val[$_POST['meta_attribute_pa_size-ml']]);

	}

	if($_POST['meta_attribute_pa_size-g']!=''){

	    $arr['meta:attribute_pa_size-g'] = trim($val[$_POST['meta_attribute_pa_size-g']]);

	}

	if($_POST['meta_attribute_fragrance-type']!=''){

	    $arr['meta:attribute_pa_fragrance-type'] = strtolower(str_replace (' ','-',preg_replace('/\(|\)/','',trim($val[$_POST['meta_attribute_pa_fragrance-type']]))));

	}

        

        if($arr['sku'] != ''){

            

            foreach($woo_table_fields as $woo_table_field_val){

                

                if(isset($arr[$woo_table_field_val])){

                    

                    $products_arr[$counter][$woo_table_field_val] = $arr[$woo_table_field_val];

                    

                }else{

                    

                    $products_arr[$counter][$woo_table_field_val] = '';

                    

                }

		if($woo_table_field_val == "Parent"){

		    array_push($arr_count,$arr[$woo_table_field_val]);

		    $directory = $arr[$woo_table_field_val];

		    if (!file_exists('product-images/'.$category.'/'.$directory)) {

			mkdir('product-images/'.$category.'/'.$directory, 0777, true);

		    }

		}

		//if($woo_table_field_val == "images"){

		

		    //copy("product-images/442487-c8452_01.jpg", str_replace("http://grassefragrance.com/wordpress/wp-content/product-images/", "product-images/", $arr[$woo_table_field_val]));

		    //copy("uploadedfiles/default-image.jpg", str_replace("http://grassefragrance.com/wordpress/wp-content/product-images/".$category.'/'.$directory, 'product-images/'.$category.'/'.$directory, $arr[$woo_table_field_val]));

		    //copy("uploadedfiles/default-image.jpg", 'product-images/'.$category.'/'.$directory.'/'.$arr[$woo_table_field_val].'.jpg');

		//}

		

            }	

            

            $counter++;

        }

    }

    $arr1 = array();

    $arr_post_parent = array();

    $arr_post_title = array();

    foreach($woo_csv_arr as $wookey => $wooval){

	//array_push($arr_post_parent,$wooval[$_POST['post_parent']]);

	//array_push($arr_post_title,$wooval[$_POST['woo_post_title']]);

	$arr1[$wooval[$_POST['woo_post_title']]] = $wooval[$_POST['post_parent']];

    }

    /*$arr1 = array();

    foreach($arr_post_title as $arr_post_title_key => $arr_post_title_val){

	foreach($arr_post_parent as $arr_post_parent_key => $arr_post_parent_val){

	    $arr1[$arr_post_title_val] = $arr_post_parent_val;

	}

    }*/

    

    $asdf = array_count_values($arr_count);

    $ff = count($products_arr);

    $new_products_arr = array();

    /*foreach($asdf as $key => $value){

	if($value == 1){

	    foreach($products_arr as $key1 => $value1){

		foreach($value1 as $key2 => $value2){

		    if($key2 == 'Parent' && $value2 == $key){

			unset($products_arr[$key1]);

		    }

		}

	    }

	}

    }*/

    foreach($arr1 as $arr1key => $arr1val){

	foreach($products_arr as $products_arr_key => $products_arr_val){

	    foreach($products_arr_val as $products_arr_val_key => $products_arr_val_val){

		if($products_arr_val_val == $arr1key){

		    $products_arr[$products_arr_key]['post_parent'] = $arr1val;

		}

	    }

	}

    }

    //echo "<pre>";

    //print_r($arr1);

    //print_r($products_arr);

    //print_r($asdf);

    header('Content-Type: application/excel');

    header('Content-Disposition: attachment; filename="'.$_POST['csv-filename'].'"');

    $fp = fopen('php://output', 'w');

    

    $conbine = array($woo_table_fields);

    foreach($conbine as $val) {

	fputcsv($fp, $val);

    }

    foreach($products_arr as $arr) {

	fputcsv($fp, $arr);

    }

?>