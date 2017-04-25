<?php

/**
 * Created by PhpStorm.
 * User: mancr
 * Date: 22-Apr-17
 * Time: 11:18
 */



$profile = db_get_profile($_SESSION['user_id']);

$img = $profile->profile_picture_name;


?>
<div class="container-fluid nopadding cover">


    <div class="col-md-3">
        <div class="panel panel-default profile">
            <div class="panel-body">
                <a href="#modal" data-toggle="modal"><img src="<?php echo $img;?>" alt=""></a>
            </div>
        </div>
    </div>

</div>

<div id="modal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Upload profile</h4>
            </div>


            <div class="modal-body">
                <p>Select an image from your computer</p>

                <form action='app/profile_upload.php'
                      method='post'
                      enctype='multipart/form-data'
                      class='form-horizontal'>

                    <div class="form-group">

                        <input type="file" name="profile" class="form-control" id="profile"/>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>

            </div>


        </div>
    </div>
</div>