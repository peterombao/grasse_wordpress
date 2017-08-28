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

			  'post_name',

			  'post_date',

			  'stock',

			  'tax_status',

			  'images',

			  'tax:product_type',

			  'tax:product_cat',

			  'meta:total_sales',

			  'attribute:pa_cap',

			  'attribute_data:pa_cap',

			  'attribute:pa_size',

			  'attribute_data:pa_size',

			  'attribute:pa_size-ml',

			  'attribute_data:pa_size-ml',

			  'attribute:pa_size-g',

			  'attribute_data:pa_size-g',

			  'attribute:pa_fragrance-type',

			  'attribute_data:pa_fragrance-type'

		    );

    $glass_bottles_csv_arr = csv_to_array("uploadedfiles/".$_POST['csv-filename']);

    $products_arr = array();

    $counter = 0;

    $arr_count = array();

    foreach($glass_bottles_csv_arr as $key => $val){

        $arr = array();

        $arr['post_title'] = trim($val[$_POST['post_title']]);

	$arr['post_name'] = strtolower(str_replace (' ','-',preg_replace('/\(|\)/','',trim($val[$_POST['post_title']]))));

        $arr['post_date'] = '2013-07-25 02:43:08';

	$arr['stock'] = '';

	$arr['tax_status'] = 'none';

	/*$category = strtolower($val[$_POST['tax_product_cat']]);

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

	$arr['images'] = 'http://grassefragrance.com/wordpress/wp-content/product-images/'.$category.'/'.$val[$_POST['post_title']].'/'.$images.'.jpg';*/

	$arr['tax:product_type'] = 'variable';

        $arr['tax:product_cat'] = trim($val[$_POST['tax_product_cat']]);

	$arr['meta:total_sales'] = '0';

        /*if($_POST['attribute_pa_cap']!=''){

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

	}*/

        foreach($woo_table_fields as $woo_table_field_val){

                

	    if(isset($arr[$woo_table_field_val])){

		

		$products_arr[$counter][$woo_table_field_val] = $arr[$woo_table_field_val];

		

	    }else{

		

		$products_arr[$counter][$woo_table_field_val] = '';

		

	    }

	    if($woo_table_field_val == "post_title"){

		array_push($arr_count,$arr[$woo_table_field_val]);

	    }

	    

	}	

            

        $counter++;

    }

    $qwe = array();

    $counter2 = 0;

    $ert = array(

		'post_title',

		'images',

		'attribute:pa_cap',

		'attribute_data:pa_cap',

		'attribute:pa_size',

		'attribute_data:pa_size',

		'attribute:pa_size-ml',

		'attribute_data:pa_size-ml',

		'attribute:pa_size-g',

		'attribute_data:pa_size-g',

		'attribute:pa_fragrance-type',

		'attribute_data:pa_fragrance-type'

		);

    foreach($glass_bottles_csv_arr as $key => $val){

	$wer = array();

	$wer['post_title'] = trim($val[$_POST['post_title']]);

	$category = strtolower(trim($val[$_POST['tax_product_cat']]));

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

	$wer['images'] = 'http://grassefragrance.com/wordpress/wp-content/product-images/'.$category.'/'.trim($val[$_POST['post_title']]).'/'.$images.'.jpg';

	if($_POST['attribute_pa_cap']!=''){

	    $wer['attribute:pa_cap'] = trim($val[$_POST['attribute_pa_cap']]);

	    //$wer['attribute_data:pa_cap'] = '1|1|1';

	}

	if($_POST['attribute_pa_size']!=''){

	    $wer['attribute:pa_size'] = trim($val[$_POST['attribute_pa_size']]);

	    //$wer['attribute_data:pa_size'] = '0|1|1';

	}

	if($_POST['attribute_pa_size-ml']!=''){

	    $wer['attribute:pa_size-ml'] = trim($val[$_POST['attribute_pa_size-ml']]);

	    //$wer['attribute_data:pa_size-ml'] = '0|1|1';

	}

	if($_POST['attribute_pa_size-g']!=''){

	    $wer['attribute:pa_size-g'] = trim($val[$_POST['attribute_pa_size-g']]);

	    //$wer['attribute_data:pa_size-g'] = '0|1|1';

	}

	if($_POST['attribute_pa_fragrance-type']!=''){

	    $wer['attribute:pa_fragrance-type'] = trim($val[$_POST['attribute_pa_fragrance-type']]);

	    //$wer['attribute_data:pa_fragrance-type'] = '1|1|1';

	}

	foreach($ert as $ert_val){

                

	    if(isset($wer[$ert_val])){

		

		$qwe[$counter2][$ert_val] = $wer[$ert_val];

		

	    }else{

		

		$qwe[$counter2][$ert_val] = '';

		

	    }

	    if($ert_val == "post_title"){

		$directory = $wer[$ert_val];

		if (!file_exists('product-images/'.$category.'/'.$directory)) {

		    mkdir('product-images/'.$category.'/'.$directory, 0777, true);

		}

	    }

	    //if($ert_val == "images"){

		//copy("uploadedfiles/default-image.jpg", str_replace("http://grassefragrance.com/wordpress/wp-content/product-images/".$category.'/'.$directory, 'product-images/'.$category.'/'.$directory, $wer[$ert_val]));

	    //}

	}

	$counter2++;

    }

    $asdf = array_count_values($arr_count);

    $ff = count($products_arr);

    $another_array = array();

    $counter1 = 0;

    foreach($products_arr as $key1 => $value1){

	if (!in_array($value1, $another_array)) {

	    array_push($another_array,$value1);

	}

    }

    foreach($qwe as $key1 => $val1){

	foreach($val1 as $key2 => $val2){

	    foreach($another_array as $key3 => $val3){

		foreach($val3 as $key4 => $val4){

		    if($key4 == 'post_title' && $val4 == $val2){

			if($another_array[$key3]['images'] == ''){

			    $another_array[$key3]['images'] = $qwe[$key1]['images'];

			}else{

			    $another_array[$key3]['images'] = $another_array[$key3]['images'].'|'.$qwe[$key1]['images'];

			    $explode_images = explode('|',$another_array[$key3]['images']);

			    $unique_images = array_unique($explode_images);

			    $another_array[$key3]['images'] = implode('|',$unique_images);

			}

			if($another_array[$key3]['attribute:pa_cap'] == ''){

			    $another_array[$key3]['attribute:pa_cap'] = $qwe[$key1]['attribute:pa_cap'];

			}else{

			    $another_array[$key3]['attribute:pa_cap'] = $another_array[$key3]['attribute:pa_cap'].'|'.$qwe[$key1]['attribute:pa_cap'];

			    $explode_cap = explode('|',$another_array[$key3]['attribute:pa_cap']);

			    $unique_cap = array_unique($explode_cap);

			    $another_array[$key3]['attribute:pa_cap'] = implode('|',$unique_cap);

			    $another_array[$key3]['attribute_data:pa_cap'] = '1|1|1';

			}

			if($another_array[$key3]['attribute:pa_size'] == ''){

			    $another_array[$key3]['attribute:pa_size'] = $qwe[$key1]['attribute:pa_size'];

			}else{

			    $another_array[$key3]['attribute:pa_size'] = $another_array[$key3]['attribute:pa_size'].'|'.$qwe[$key1]['attribute:pa_size'];

			    $explode_size = explode('|',$another_array[$key3]['attribute:pa_size']);

			    $unique_size = array_unique($explode_size);

			    $another_array[$key3]['attribute:pa_size'] = implode('|',$unique_size);

			    $another_array[$key3]['attribute_data:pa_size'] = '0|1|1';

			}

			if($another_array[$key3]['attribute:pa_size-ml'] == ''){

			    $another_array[$key3]['attribute:pa_size-ml'] = $qwe[$key1]['attribute:pa_size-ml'];

			}else{

			    $another_array[$key3]['attribute:pa_size-ml'] = $another_array[$key3]['attribute:pa_size-ml'].'|'.$qwe[$key1]['attribute:pa_size-ml'];

			    $explode_size_ml = explode('|',$another_array[$key3]['attribute:pa_size-ml']);

			    $unique_size_ml = array_unique($explode_size_ml);

			    $another_array[$key3]['attribute:pa_size-ml'] = implode('|',$unique_size_ml);

			    $another_array[$key3]['attribute_data:pa_size-ml'] = '0|1|1';

			}

			if($another_array[$key3]['attribute:pa_size-g'] == ''){

			    $another_array[$key3]['attribute:pa_size-g'] = $qwe[$key1]['attribute:pa_size-g'];

			}else{

			    $another_array[$key3]['attribute:pa_size-g'] = $another_array[$key3]['attribute:pa_size-g'].'|'.$qwe[$key1]['attribute:pa_size-g'];

			    $explode_size_g = explode('|',$another_array[$key3]['attribute:pa_size-g']);

			    $unique_size_g = array_unique($explode_size_g);

			    $another_array[$key3]['attribute:pa_size-g'] = implode('|',$unique_size_g);

			    $another_array[$key3]['attribute_data:pa_size-g'] = '0|1|1';

			}

			if($another_array[$key3]['attribute:pa_fragrance-type'] == ''){

			    $another_array[$key3]['attribute:pa_fragrance-type'] = $qwe[$key1]['attribute:pa_fragrance-type'];

			}else{

			    $another_array[$key3]['attribute:pa_fragrance-type'] = $another_array[$key3]['attribute:pa_fragrance-type'].'|'.$qwe[$key1]['attribute:pa_fragrance-type'];

			    $explode_fragrance_type = explode('|',$another_array[$key3]['attribute:pa_fragrance-type']);

			    $unique_fragrance_type = array_unique($explode_fragrance_type);

			    $another_array[$key3]['attribute:pa_fragrance-type'] = implode('|',$unique_fragrance_type);

			    $another_array[$key3]['attribute_data:pa_fragrance-type'] = '1|1|1';

			}

		    }else{

			if($another_array[$key3]['attribute:pa_cap'] != ''){

			    $another_array[$key3]['attribute_data:pa_cap'] = '1|1|1';

			}

			if($another_array[$key3]['attribute:pa_size'] != ''){

			    $another_array[$key3]['attribute_data:pa_size'] = '0|1|1';

			}

			if($another_array[$key3]['attribute:pa_size-ml'] != ''){

			    $another_array[$key3]['attribute_data:pa_size-ml'] = '0|1|1';

			}

			if($another_array[$key3]['attribute:pa_size-g'] != ''){

			    $another_array[$key3]['attribute_data:pa_size-g'] = '0|1|1';

			}

			if($another_array[$key3]['attribute:pa_fragrance-type'] != ''){

			    $another_array[$key3]['attribute_data:pa_fragrance-type'] = '1|1|1';

			}

		    }

		}

	    }

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