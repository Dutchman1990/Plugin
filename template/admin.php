<div class="container">
    <h2>Import CSV File Data into MySQL Database using PHP</h2>
    <div class="panel panel-default">
        <div class="panel-heading">
        </div>
        <div class="panel-body">
            <form action="<?php echo home_url('/'); ?>wp-admin/admin.php?page=shareprice_upload" method="post" enctype="multipart/form-data" id="importFrm">
                <input type="file" name="file" />
                <input type="submit" class="btn btn-primary" name="importSubmit" value="IMPORT">
            </form>
        </div>
    </div>
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
                $prevQuery = $wpdb->get_var("SELECT * FROM shareprice where TICKER='".$line[0]."' AND DATE='".$line[1]."'" );
                $getidd = $wpdb->get_results("SELECT ID FROM shareprice where TICKER='".$line[0]."' AND DATE='".$line[1]."'");
                if($prevQuery > 0){
                    foreach ($getidd as $keys) {
                    	$tblid=$keys->ID;
                        $wpdb->query("UPDATE shareprice SET TICKER = '".$line[0]."', DATE = '".$line[1]."', OPEN = '".$line[2]."', HIGH = '".$line[3]."', LOW = '".$line[4]."', CLOSE = '".$line[5]."',VOLUME = '".$line[6]."',ADJCLOSE = '".$line[7]."', WHERE ID = '".$tblid."'");
                    }
                }
                elseif($prevQuery==0){

                $wpdb->query("INSERT INTO shareprice(TICKER,DATE,OPEN,HIGH,LOW,CLOSE,VOLUME,ADJCLOSE) VALUES ('".$line[0]."','".$line[1]."','".$line[2]."','".$line[3]."','".$line[4]."','".$line[5]."','".$line[6]."','".$line[7]."')");
                }
                else{
                    $wpdb->query("INSERT INTO shareprice(TICKER,DATE,OPEN,HIGH,LOW,CLOSE,VOLUME,ADJCLOSE) VALUES ('".$line[0]."','".$line[1]."','".$line[2]."','".$line[3]."','".$line[4]."','".$line[5]."','".$line[6]."','".$line[7]."')");
                }
            }
            
            //close opened csv file
            fclose($csvFile);

            $qstring = '?status=succ';
        }else{
            $qstring = '?status=err';
        }
    }else{
        $qstring = '?status=invalid_file';
    }
}

?>