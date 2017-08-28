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
            $mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
            if(in_array($_FILES['file']['type'],$mimes)){
                $fileName = $_FILES["file"]["name"]; 
                $fileTmpLoc = $_FILES["file"]["tmp_name"];
                $pathAndName = "uploadedfiles/".$fileName;
                $moveResult = move_uploaded_file($fileTmpLoc, $pathAndName);
            }else{
                echo 'not csv file';
            }
            $row = 1;
            $asd = array();
            if (($handle = fopen($pathAndName, "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $num = count($data);
                    //echo "<br/>$num fields in line $row:";
                    //echo '<br/>--------------<br/>';
                    $row++;
                    $arr = array();
                    for ($c=0; $c < $num; $c++) {
                            array_push($arr,$data[$c]);
                    }
                    //echo $arr;
                    array_push($asd,$arr);
                }
                fclose($handle);
            }
            //echo '<pre>';
            //print_r($asd);
        ?>
        <form action="converted.php" method="post">
            <input type="hidden" name="csv-filename" value="<?php echo $fileName; ?>"/>
            <table>
                <tbody>
                    <tr>
                        <td><label>post_title*</label></td>
                        <td>
                            <input type="hidden" name="post_title"/>
                            <select name="post_title1">
                                <option value=""></option>
                            <?php
                                for($i=0;$i<$num;$i++){
                            ?>
                                <option value="<?php echo $asd[0][$i]; ?>"><?php echo $asd[0][$i]; ?></option>
                            <?php } ?>
                            </select>
                            <select name="post_title2">
                                <option value=""></option>
                            <?php
                                for($i=0;$i<$num;$i++){
                            ?>
                                <option value="<?php echo $asd[0][$i]; ?>"><?php echo $asd[0][$i]; ?></option>
                            <?php } ?>
                            </select>
                            <select name="post_title3">
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
                        <td><label>post_content</label></td>
                        <td>
                            <select name="post_content">
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
                        <td><label>tax:product_cat</label></td>
                        <td>
                            <select name="tax_product_cat">
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
                        <td><label>attribute:pa_cap</label></td>
                        <td>
                            <select name="attribute_pa_cap">
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
                        <td><label>attribute:pa_size</label></td>
                        <td>
                            <select name="attribute_pa_size">
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
                        <td><label>attribute:pa_size-ml</label></td>
                        <td>
                            <select name="attribute_pa_size-ml">
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