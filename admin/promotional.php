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
                this.getDoc().body.style.fontSize = '22px';
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

                    <label for="type" class="ja-toppadding">Type:</label>
                    <select name="type" id="type" class="form-control w-50" onchange="setuppromotional(document.getElementById('type').value)">
                    <option value=""> - Select - </option><option value="banner">Banner</option><option value="email">Email</option></select>

                    <span name="promotionaloptionstext" id="promotionaloptionstext" style="visibility: hidden;"></span>
                    <span name="promotionaloptionsfields" id="promotionaloptionsfields" style="visibility: hidden;"></span>
                    
                    <div class="ja-bottompadding"></div>

                    <span name="previewfield" id="previewfield" style="visibility: hidden; display: none;">
                        <script language="JavaScript">
                        function previewbannerad(bannerurl,targeturl)
                        {
                        var win
                        win = window.open("", "win", "height=68,width=500,toolbar=no,directories=no,menubar=no,scrollbars=yes,resizable=yes,dependent=yes'");
                        win.document.clear();
                        win.document.write('<a href="'+targeturl+'"><img src="'+bannerurl+'"></a>');
                        win.focus();
                        win.document.close();
                        }
                        </script>
                        <button type="button" class="btn btn-lg btn-primary ja-toppadding ja-bottompadding" 
                            onclick="previewbannerad(document.getElementById('promotionalimage').value,'<?php echo $domain ?>')">Preview</button>
                    </span>
                    <button class="btn btn-lg btn-primary ja-toppadding ja-bottompadding" type="submit" name="addpromotional">Create</button>

                </div>
            </form>				

			<div class="ja-bottompadding mb-4"></div>

            <h1 class="ja-bottompadding">Promotional Material</h1>

                    <?php
                    foreach ($promotionals as $promotional) {

                        if ($promotional['type'] == "banner") {

                            ?>
                            <div class="table-responsive">
                                <table class="table table-condensed table-bordered table-striped table-hover text-center table-sm">
                                    <tbody>
                                        <tr>
                                            <form action="/admin/promotional/<?php echo $promotional['id']; ?>" method="post" accept-charset="utf-8" class="form" role="form">
                                            <td class="large">
                                                BANNER
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
                                                <input type="text" name="name" value="<?php echo $promotional['name']; ?>" class="form-control input-lg" size="40" placeholder="Ad Name" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="text" name="promotionalimage" value="<?php echo $promotional['promotionalimage']; ?>" class="form-control input-lg" size="40" placeholder="Banner Image URL" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <button class="btn btn-sm btnprimary" type="button" onClick="previewad(promotionalimage.value,'<?php echo $domain ?>')">Preview</button>
                                                <input type="hidden" name="_method" value="PATCH">
                                                <button class="btn btn-sm btn-primary" type="submit" name="savepromotional">SAVE</button>
                                            </td>
                                        </tr>
                                            </form>
                                        <tr>
                                            <td>
                                                <form action="/admin/promotional/<?php echo $promotional['id']; ?>" method="POST" accept-charset="utf-8" class="form" role="form">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="name" value="<?php echo $promotional['name']; ?>">
                                                    <button class="btn btn-sm btn-primary" type="submit" name="deletepromotional">DELETE</button>
                                                </form>
                                            </td>
                                        </tr>
                                        </tbody>
                                </table>
                            </div>
                            <?php

                        }
                        if ($promotional['type'] == "email") {

                            ?>
                                <table class="table table-condensed table-bordered table-striped table-hover text-center table-sm">
                                    <tbody>
                                        <tr>
                                            <form action="/admin/promotional/<?php echo $promotional['id']; ?>" method="post" accept-charset="utf-8" class="form" role="form">
                                            <td class="large">
                                                EMAIL
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="text" name="name" value="<?php echo $promotional['name']; ?>" class="form-control input-lg" size="40" placeholder="Ad Name" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="text" name="promotionalsubject" value="<?php echo $promotional['promotionalsubject']; ?>" class="form-control input-lg" size="40" placeholder="Subject" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <textarea name="promotionaladbody<?php echo $promotional['id']; ?>" id="promotionaladbody<?php echo $promotional['id']; ?>" rows="20" cols="80"><?php echo $promotional['promotionaladbody']; ?></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="hidden" name="_method" value="PATCH">
                                                <button class="btn btn-sm btn-primary" type="submit" name="savepromotional">SAVE</button>
                                            </td>
                                        </tr>
                                            </form>
                                        <tr>
                                            <td>
                                                <form action="/admin/promotional/<?php echo $promotional['id']; ?>" method="POST" accept-charset="utf-8" class="form" role="form">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="name" value="<?php echo $promotional['name']; ?>">
                                                    <button class="btn btn-sm btn-primary" type="submit" name="deletepromotional">DELETE</button>
                                                </form>
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
