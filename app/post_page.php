<?php
/**
 * Created by PhpStorm.
 * User: mancr
 * Date: 19-Apr-17
 * Time: 17:50
 */

$user_name = $session->get(Properties::EMAIL);
$user_id = $session->get(Properties::ID);
$posts = Database::getPosts();

// Encrypt message
$iv = $session->get(Properties::IV);


function find($post_id) {
    global $posts;
    foreach ($posts as $post) {
        if ($post['post_id'] == $post_id) {
            return $post;
        }
    }
    return null;
}

function is_post_author($user_id, $post_id) {
    $post = find($post_id);
    if ($post && $user_id == $post['user_id']) {
        return true;
    } else {
        return false;
    }
}

if (isset($_POST['submit'])) {

    $content = $_POST['content'];
    Database::createPost($user_id, $content);
    navigate_to();

}

//TODO need post id
if (isset($_POST["delete"])) {
    $post_id = EncryptionManager::decrypt($_POST["id"], $iv);

    if (is_post_author($user_id, $post_id)) {
        Database::deletePost($post_id);
        navigate_to();

    }
}

//TODO: need post content, post id  HELP?
if (isset($_POST["edit"])) {

    $post_id = EncryptionManager::decrypt($_POST["id"], $iv);
    $content = $_POST["edit-content"];
    if (is_post_author($user_id, $post_id)) {
        Database::updatePost($post_id, $content);
        navigate_to();
    }
}


?>
<div class="container-fluid">

    <div class="col-md-offset-3 col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading"><?php echo htmlspecialchars($user_name) ?></div>
            <div class="panel-body">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" autocomplete="off">
                    <div class="form-group">
                    <textarea placeholder="Add a new post" name="content" class="form-control" rows="3"
                              id="textArea"></textarea>
                    </div>
                    <div class="form-group">
                        <button name="submit" type="submit" class="btn btn-primary">Post</button>
                    </div>
                </form>
            </div>
        </div>

        <?php


        $modal_counter = 0;
        foreach ($posts as $post) {
            $modal_counter++;
            $delete_button = ($user_name == $post['author']) ?
                '<form method="post" action="#">
                    <input type="hidden" name="id" value=' . EncryptionManager::encrypt($post['post_id'], $iv) . ' />

                    <button name="delete" type="submit" class="btn btn-danger pull-right btn-sm gap">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
      
                </form>' : "";

            $edit_button = ($user_name == $post['author']) ?
                '<a href="#edit-modal-' . $modal_counter . '" data-toggle="modal">' .
                '<button name="edit" class="btn btn-primary pull-right btn-sm">
                    <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                </button>' .
                '</a>' : "";

            $edit_post = ($user_name == $post['author']) ?
                '<div id="edit-modal-' . $modal_counter . '" class="modal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title">Edit Post</h4>
                            </div>                 
                            <div class="modal-body">            
                                <form action="#"
                                      method="post"
                                      enctype="multipart/form-data"
                                      class="form-vertical">

                                    <input type="hidden" name="id" value=' . EncryptionManager::encrypt($post['post_id'], $iv) . ' />
                                    <div class="form-group">
                                        <textarea name="edit-content" class="form-control" rows="3" id="edit-text-area">' . $post['content'] . '</textarea>
                                    </div>
                
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        <button type="submit" name="edit" class="btn btn-primary">Save</button>
                                    </div>                                    
                                </form>                
                            </div>
                
                
                        </div>
                    </div>
                </div>' : "";

            echo
                '<div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title col-xs-8 pull-left">' .
                htmlspecialchars($post['author']) . ' 
                        </h3>' .
                $delete_button . $edit_button .
                '<div class="clearfix"></div>   
                    </div>
                        
                    <div class="panel-body">' .
                htmlspecialchars($post['content']) . '<br />' .
                htmlspecialchars($post['date']) .
                $edit_post .
                '</div>
                </div>';

        } ?>
    </div>
</div>
