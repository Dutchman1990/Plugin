<div class="container">
    <h2>Upload Dividend Data via CSV file.</h2>
    <div class="panel panel-default">
        <div class="panel-heading">
        </div>
        <div class="panel-body">
            <form action="<?php echo home_url('/'); ?>wp-admin/admin.php?page=dividend_upload" method="post" enctype="multipart/form-data" id="importFrm">
              	<input type="file" class="fle" name="file" />
                <!-- <div class="fileUpload btn btn-primary">
                    <span class="spn">Upload</span> 
                    <input type="file" class="upload" /> 
                </div> -->
                <input type="submit" class="button button-primary button-large" name="importSubmit" value="IMPORT">
            </form>
        </div>
    </div>
    <?php if(!empty($statusMsg)){
        echo '<div class="alert '.$statusMsgClass.'">'.$statusMsg.'</div>';
    } ?>
</div>
<?php
global $wpdb;
if(isset($_POST['importSubmit'])){
    
    //validate whether uploaded file is a csv file
    $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
    if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'],$csvMimes)){
        if(is_uploaded_file($_FILES['file']['tmp_name'])){
            
            //open uploaded csv file with read only mode
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
            
            //skip first line
            fgetcsv($csvFile);
            
            //parse data from csv file line by line
            while(($line = fgetcsv($csvFile)) !== FALSE){
                $prevQuery = $wpdb->get_var("SELECT * FROM dividend where TICKER='".$line[0]."' AND EXPAID='".$line[3]."' AND PAID='".$line[4]."'" );
                $getidd = $wpdb->get_results("SELECT ID FROM dividend where TICKER='".$line[0]."' AND EXPAID='".$line[3]."' AND PAID='".$line[4]."'");
                
                if($prevQuery > 0){
                    foreach ($getidd as $keys) {
                    	$tblid=$keys->ID;
                        $wpdb->query("UPDATE dividend SET TICKER = '".$line[0]."', DIVIDEND_PAID='".$line[1]."' , FRANKING='".$line[2]."' , EXPAID = '".$line[3]."', PAID = '".$line[4]."', WHERE ID = '".$tblid."'");
                    }
                }
                elseif($prevQuery==0){

                $wpdb->query("INSERT INTO dividend(TICKER,DIVIDEND_PAID,FRANKING,EXPAID,PAID) VALUES ('".$line[0]."','".$line[1]."','".$line[2]."','".$line[3]."','".$line[4]."')");
                }
                else{
                    $wpdb->query("INSERT INTO dividend(TICKER,DIVIDEND_PAID,FRANKING,EXPAID,PAID) VALUES ('".$line[0]."','".$line[1]."','".$line[2]."','".$line[3]."','".$line[4]."')");
                }
            }
            echo '<div class="imprted">Data imported.</div>';
            //close opened csv file
            fclose($csvFile);

            //$qstring = '?status=succ';
        }else{
            //$qstring = '?status=err';
            echo '<div class="erroccr">An error occured. Please try again.</div>';
        }
    }else{
        //$qstring = '?status=invalid_file';
        echo '<div class="invalidflie">Not a CSV File.</div>';
    }
}

?>