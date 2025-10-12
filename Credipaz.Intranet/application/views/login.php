<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
?>
<!DOCTYPE html>
<html lang="<?php echo $language;?>">
<?php echo $header;?>
<body>
    <div class="container main">
        <div class="row">
            <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
                <div class="card card-signin mt-5 mb-2">
                    <div class="card-body">
                        <h3 class="card-title text-center" style="vertical-align:middle;"><?php echo $title;?></h3>
						<?php echo buildLogin($mode_login,"","",lang('p_username'));?>
                    </div>
                </div>
            </div>
        </div>
    </div>    
    <?php echo $footer;?>
</body>
</html>
