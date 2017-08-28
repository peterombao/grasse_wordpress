<!DOCTYPE html>
<html>
    <head>
        <script src="jquery-1.7.2.js"></script>
    </head>
    <body>
        <?php
            if (!file_exists('uploadedfiles')) {
                mkdir('uploadedfiles', 0777, true);
            }
            //$mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
            //if(in_array($_FILES['file']['type'],$mimes)){
                $fileName = $_FILES["file"]["name"]; 
                $fileTmpLoc = $_FILES["file"]["tmp_name"];
                $pathAndName = "uploadedfiles/".$fileName;
                $moveResult = move_uploaded_file($fileTmpLoc, $pathAndName);
                
                $fileNames = $_FILES["files"]["name"]; 
                $fileTmpLocs = $_FILES["files"]["tmp_name"];
                $pathAndNames = "uploadedfiles/".$fileNames;
                $moveResult = move_uploaded_file($fileTmpLocs, $pathAndNames);
            //}else{
                //echo 'not csv file';
            //}
            $row = 1;
            $asd = array();
            if (($handle = fopen($pathAndName, "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $num = count($data);
                    //echo "<br/>$num fields in line $row:";
                    //echo '<br/>--------------<br/>';
                    $row++;
                    $arr = array();
                    for ($c=0; $c<$num; $c++) {
                            array_push($arr,$data[$c]);
                    }
                    //echo $arr;
                    array_push($asd,$arr);
                }
                fclose($handle);
            }
            $rows = 1;
            $asds = array();
            if (($handles = fopen($pathAndNames, "r")) !== FALSE) {
                while (($datas = fgetcsv($handles, 1000, ",")) !== FALSE) {
                    $nums = count($datas);
                    //echo "<br/>$num fields in line $row:";
                    //echo '<br/>--------------<br/>';
                    $rows++;
                    $arrs = array();
                    for ($d=0; $d<$nums; $d++) {
                            array_push($arrs,$datas[$d]);
                    }
                    //echo $arr;
                    array_push($asds,$arrs);
                }
                fclose($handles);
            }
            //echo '<pre>';
            //print_r($asd);
        ?>
        <form action="converted_variables.php" method="post">
            <input type="hidden" name="csv-filename" value="<?php echo $fileName; ?>"/>
            <input type="hidden" name="csv-filenames" value="<?php echo $fileNames; ?>"/>
            <table>
                <tbody>
                    <tr>
                        <td><label>category</label></td>
                        <td>
                            <select name="category">
                                <option value="pet bottles">pet bottles</option>
                                <option value="glass bottles">glass bottles</option>
                                <option value="jars">jars</option>
                                <option value="fragrances">fragrances</option>
                                <option value="compounds">compounds</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label>Parent</label></td>
                        <td>
                            <select name="Parent">
                                <option value=""></option>
                            <?php
                                for($i=0;$i<$num;$i++){
                            ?>
                                <option value="<?php echo $asd[0][$i]; ?>"><?php echo $asd[0][$i]; ?></option>
                            <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label>post_parent</label></td>
                        <input type="hidden" name="woo_post_title" value="post_title"/>
                        <td>
                            <select name="post_parent">
                                <option value=""></option>
                            <?php
                                for($i=0;$i<$nums;$i++){
                            ?>
                                <option value="<?php echo $asds[0][$i]; ?>"><?php echo $asds[0][$i]; ?></option>
                            <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label>sku</label></td>
                        <td>
                            <select name="sku">
                                <option value=""></option>
                            <?php
                                for($i=0;$i<$num;$i++){
                            ?>
                                <option value="<?php echo $asd[0][$i]; ?>"><?php echo $asd[0][$i]; ?></option>
                            <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label>regular_price</label></td>
                        <td>
                            <select name="regular_price">
                                <option value=""></option>
                            <?php
                                for($i=0;$i<$num;$i++){
                            ?>
                                <option value="<?php echo $asd[0][$i]; ?>"><?php echo $asd[0][$i]; ?></option>
                            <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label>images</label></td>
                        <td>
                            <input type="hidden" name="images"/>
                            <select name="images1">
                                <option value=""></option>
                            <?php
                                for($i=0;$i<$num;$i++){
                            ?>
                                <option value="<?php echo $asd[0][$i]; ?>"><?php echo $asd[0][$i]; ?></option>
                            <?php } ?>
                            </select>
                            <select name="images2">
                                <option value=""></option>
                            <?php
                                for($i=0;$i<$num;$i++){
                            ?>
                                <option value="<?php echo $asd[0][$i]; ?>"><?php echo $asd[0][$i]; ?></option>
                            <?php } ?>
                            </select>
                            <select name="images3">
                                <option value=""></option>
                            <?php
                                for($i=0;$i<$num;$i++){
                            ?>
                                <option value="<?php echo $asd[0][$i]; ?>"><?php echo $asd[0][$i]; ?></option>
                            <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label>meta:attribute_pa_cap</label></td>
                        <td>
                            <select name="meta_attribute_pa_cap">
                                <option value=""></option>
                            <?php
                                for($i=0;$i<$num;$i++){
                            ?>
                                <option value="<?php echo $asd[0][$i]; ?>"><?php echo $asd[0][$i]; ?></option>
                            <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label>meta:attribute_pa_size</label></td>
                        <td>
                            <select name="meta_attribute_pa_size">
                                <option value=""></option>
                            <?php
                                for($i=0;$i<$num;$i++){
                            ?>
                                <option value="<?php echo $asd[0][$i]; ?>"><?php echo $asd[0][$i]; ?></option>
                            <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label>meta:attribute_pa_size-ml</label></td>
                        <td>
                            <select name="meta_attribute_pa_size-ml">
                                <option value=""></option>
                            <?php
                                for($i=0;$i<$num;$i++){
                            ?>
                                <option value="<?php echo $asd[0][$i]; ?>"><?php echo $asd[0][$i]; ?></option>
                            <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label>meta:attribute_pa_size-g</label></td>
                        <td>
                            <select name="meta_attribute_pa_size-g">
                                <option value=""></option>
                            <?php
                                for($i=0;$i<$num;$i++){
                            ?>
                                <option value="<?php echo $asd[0][$i]; ?>"><?php echo $asd[0][$i]; ?></option>
                            <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label>meta:attribute_pa_fragrance-type</label></td>
                        <td>
                            <select name="meta_attribute_fragrance-type">
                                <option value=""></option>
                            <?php
                                for($i=0;$i<$num;$i++){
                            ?>
                                <option value="<?php echo $asd[0][$i]; ?>"><?php echo $asd[0][$i]; ?></option>
                            <?php } ?>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
            <input type="submit" value="submit"/>
        </form>
        <script>
            //alert($('tbody tr').length)
            /*$('select[name="post_title1"]').change(function(){
                alert($(this).val())
            });*/
        </script>
    </body>
</html>