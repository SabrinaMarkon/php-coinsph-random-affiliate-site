<?php
# Prevent direct access to this file. Show browser's default 404 error instead.
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    exit;
}


require "control.php";
if (isset($show))
{
    echo $show;
}
$allpromotionals = new Promotional();
$promotionals = $allpromotionals->getAllPromotionals();
?>

<!-- tinyMCE -->
<script language="javascript" type="text/javascript" src="/../js/tinymce/tinymce.min.js"></script>
<script language="javascript" type="text/javascript">
    tinymce.init({
        setup : function(ed) {
            ed.on('init', function() {
                this.getDoc().body.style.fontSize = '1em';
                this.getDoc().body.style.fontFamily = 'Calibri';
                this.getDoc().body.style.backgroundColor = '#ffffff';
            });
        },
        selector: 'textarea',  // change this value according to your HTML
        body_id: 'elm1=message',
        height: 600,
        theme: 'modern',
        plugins: [
            'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen',
            'insertdatetime media nonbreaking save table contextmenu directionality',
            'emoticons template paste textcolor colorpicker textpattern imagetools'
        ],
        toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        toolbar2: 'print preview media | forecolor backcolor emoticons',
        image_advtab: true,
        templates: [
            { title: 'Test template 1', content: 'Test 1' },
            { title: 'Test template 2', content: 'Test 2' }
        ],
        content_css: [
//            '/../css/bootstrap.min.css',
//            '/../css/bootstrap-theme.min.css',
//            '/../css/custom.css'
        ]
    });
</script>
<!-- /tinyMCE -->

<div class="container">
    <div class="row">
        <div class="col-sm-12">

			<h1 class="ja-bottompadding">Add New Promotional Material</h1>

            <form action="/admin/promotional" method="post" accept-charset="utf-8" class="form" role="form">
                
                <div id="form-group">

                    <label for="name" class="ja-toppadding">Name:</label>
                    <input type="text" name="name" value="" class="form-control input-lg w-50" placeholder="Name" required>

                    <label for="type">Type:</label>
                    <select name="type" id="type" class="form-control w-50" onchange="setuppromotional(document.getElementById('type').value)">
                    <option value=""> - Select - </option><option value="banner">Banner</option><option value="email">Email</option></select>

                    <span name="promotionaloptionsfields" id="promotionaloptionsfields" style="visibility: hidden;"></span>
                    
                    <div class="ja-bottompadding"></div>

                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span name="previewfield" id="previewfield" style="visibility: hidden; display: none;">
                                    <button type="button" class="btn btn-lg btn-primary ja-bottompadding mr-2" data-toggle="modal" data-target="#bannerPreview" onclick="updateimg();">Preview</button>
                                </span>
                                <button class="btn btn-lg btn-primary ja-bottompadding" type="submit" name="addpromotional">Create</button>
                            </div>
                        </div>

                </div>
            </form>				
            <div class="modal" id="bannerPreview">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close btn btn-danger" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <a href="<?php echo $domain ?>" target="_blank"><img id="bannerimage" class="ja-promotionalimg img-responsive"></a>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                    </div>
                </div>
            </div>
            <script>
            // update the preview image's src with the value in promotionalimage field.
            function updateimg() {
                document.getElementById('bannerimage').src = document.getElementById('promotionalimage').value;
            }
            </script>

			<div class="ja-bottompadding mb-5"></div>

            <h1 class="ja-bottompadding">Promotional Material</h1>

            <div class="ja-bottompadding mb-4"></div>

                    <?php
                    foreach ($promotionals as $promotional) {

                        if ($promotional['type'] == "banner") {

                            ?>
                            <div class="table-responsive border border-dark mb-5">
                                <table class="table table-condensed table-bordered table-striped text-center">
                                    <tbody>
                                        <tr>
                                            <form action="/admin/promotional/<?php echo $promotional['id']; ?>" method="post" accept-charset="utf-8" class="form" role="form">
                                            <td class="table-active">
                                                <h4>BANNER</h4>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="ja-promotionalimg">
                                                    <a href="<?php echo $domain ?>" target="_blank"><img class="ja-promotionalimg" src="<?php echo $promotional['promotionalimage']; ?>"></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label for="name">Ad Name:</label>
                                                <input type="text" name="name" value="<?php echo $promotional['name']; ?>" class="form-control input-lg" size="40" placeholder="Ad Name" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label for="promotionalimage">Banner Image URL:</label>
                                                <input type="text" name="promotionalimage" value="<?php echo $promotional['promotionalimage']; ?>" class="form-control input-lg" size="40" placeholder="Banner Image URL" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <button class="btn btn-sm btn-primary mr-2" type="button" data-toggle="modal" data-target="#savedBannerPreview">Preview</button>
                                                        <input type="hidden" name="_method" value="PATCH">
                                                        <button class="btn btn-sm btn-primary mr-2" type="submit" name="savepromotional">SAVE</button>
                                                    </form>
                                                    <form action="/admin/promotional/<?php echo $promotional['id']; ?>" method="POST" accept-charset="utf-8" class="form" role="form">
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <input type="hidden" name="name" value="<?php echo $promotional['name']; ?>">
                                                            <button class="btn btn-sm btn-danger" type="submit" name="deletepromotional">DELETE</button>
                                                    </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal" id="savedBannerPreview">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                       <button type="button" class="close btn btn-danger" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <a href="<?php echo $domain ?>" target="_blank"><img class="ja-promotionalimg" src="<?php echo $promotional['promotionalimage']; ?>"></a>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        if ($promotional['type'] == "email") {

                            ?>
                            <div class="table-responsive border border-dark mb-5">
                                <table class="table table-condensed table-bordered table-striped text-center">
                                    <tbody>
                                        <tr>
                                            <form action="/admin/promotional/<?php echo $promotional['id']; ?>" method="post" accept-charset="utf-8" class="form" role="form">
                                            <td class="table-active">
                                                <h4>EMAIL</h4>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label for="name">Ad Name</label>
                                                <input type="text" name="name" value="<?php echo $promotional['name']; ?>" class="form-control input-lg" size="40" placeholder="Ad Name" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label for="promotionalsubject">Subject:</label>
                                                <input type="text" name="promotionalsubject" value="<?php echo $promotional['promotionalsubject']; ?>" class="form-control input-lg" size="40" placeholder="Subject" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label for="promotionaladbody<?php echo $promotional['id']; ?>">Message:</label>
                                                <textarea name="promotionaladbody<?php echo $promotional['id']; ?>" id="promotionaladbody<?php echo $promotional['id']; ?>" rows="20" cols="80"><?php echo $promotional['promotionaladbody']; ?></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <input type="hidden" name="_method" value="PATCH">
                                                        <button class="btn btn-sm btn-primary mr-2" type="submit" name="savepromotional">SAVE</button>
                                                        </form>
                                                        <form action="/admin/promotional/<?php echo $promotional['id']; ?>" method="POST" accept-charset="utf-8" class="form" role="form">
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <input type="hidden" name="name" value="<?php echo $promotional['name']; ?>">
                                                            <button class="btn btn-sm btn-danger" type="submit" name="deletepromotional">DELETE</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <?php
                            
                        }
                    }
                    ?>

            <div class="ja-bottompadding"></div>

        </div>
    </div>
</div>
