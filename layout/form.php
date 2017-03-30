<?php
function user_feedback_form_creation( $atts = array()){
    $org_name = get_bloginfo() ;
    $discription["ask-question"] = __("Do you have a question that ", 'odi') . $org_name ." ". __("can help answer? We will gladly help you.", 'odi');
    $discription["report-problem"] = __("Have you found a technical problem or issue on the ", 'odi'). $org_name." ".__("website?", 'odi');
    $discription["share-idea"] = __("Do you have a new idea that could help transform the ", 'odi'). $org_name." ".__("website? We will be glad to hear it.", 'odi');
    $discription["send-feedback"] = __("Tell us how we're doing.", 'odi');
    $discription["submit-resource"] = __("Do you have resources that could help expand the ", 'odi'). $org_name." ".__("website? We will review any map data, laws, articles, and documents that we do not yet have and see if we can implement them into our site. Please make sure the resources are in the public domain or fall under a <a class='a-normal' target='_blank' href='http://creativecommons.org/'>Creative Commons</a> license.", 'odi');

    $placeholder["ask-question"] = __("Ask us anything about the ", 'odi'). $org_name." ". __("website or  open data.", 'odi');
    $placeholder["report-problem"] = __("Tell us about what you have found.", 'odi');
    $placeholder["share-idea"] = __("Describe your idea here.", 'odi');
    $placeholder["send-feedback"] = __("Do you have suggestions on how ", 'odi').$org_name." ". __("can be improved?", 'odi');
    $placeholder["submit-resource"] = __("Tell us about the resources you're sharing with us.", 'odi');

    $show_form = isset($atts['show_form'])? $atts['show_form']: "all";
    $get_formname = array_keys($discription);
    $get_formindex = array_search($show_form, $get_formname);
    $get_index = $get_formindex? "-" .$get_formindex : null;
    //$form_index = $atts['is_popup_form']? $get_index : null;
    $form_index = ($atts['is_popup_form']) ? null : "-".$get_formindex;
    ?>
    <div id="user_feedback_form_container" class="user_feedback_form_<?php echo  odm_language_manager()->get_current_language();?>">
    	<?php if ($atts['is_popup_form']){ ?>
        	<div id="close-button"></div>
        	<h2><?php _e(isset($atts['title'])? $atts['title']:"Contact us", 'odi');?></h2>
    	<?php } ?>
            <div id="wrapper">
            	<div id="long-all<?php echo $form_index; ?>" class="long-all">
                    <div id="get-involve<?php echo $form_index; ?>">
                    <form id="user_feedback_form-form<?php echo $form_index; ?>" class="user_feedback_form-form" action="admin-ajax.php?action=UploadFeedbackFile" method="post" enctype="multipart/form-data">
                    <div id="tabs<?php echo $form_index; ?>" class="tabs">
                    <?php if ($show_form == "all"){ ?>
                      <ul id="choice">
                        <li id="ask-question"><a href="#involve"><span><?php _e("Ask Question", 'odi');?></span></a></li>
                        <li id="report-problem"><a href="#involve"><span><?php _e("Report Problem", 'odi');?></span></a></li>
                        <li id="share-idea"><a href="#involve"><span><?php _e("Share Idea", 'odi');?></span></a></li>
                        <li id="send-feedback"><a href="#involve"><span><?php _e("Send Feedback", 'odi');?></span></a></li>
                        <li id="submit-resource"><a href="#involve"><span><?php _e("Submit Resources", 'odi');?></span></a></li>
                      </ul>
                       <?php } ?>
                      <div id="involve" class="involve" <?php if ($show_form == "all"){ echo "style='border-top:none'" ;}?>>
                        <div class="involve-content">
                        <?php
                        if(isset($discription)):
                          if(in_array($show_form, $discription)): ?>
                            <p id="involve-desc" class="<?php echo $show_form; ?>">
                              <?php echo isset($discription[$show_form])? $discription[$show_form] : $discription["ask-question"]; ?>
                            </p>
                            <?php
                          else:
                            foreach ($discription as $form_name => $form_dis) :?>
                              <p id="involve-desc" class="<?php echo $form_name; ?>">
                                <?php echo $discription[$form_name]; ?>
                              </p>
                            <?php
                            endforeach;
                          endif;
                        endif;
                        ?>
                        <textarea id="question-textarea<?php echo $form_index; ?>" class="question-textarea" rows="10" placeholder="<?php echo isset($placeholder[$show_form])? $placeholder[$show_form] : $placeholder["ask-question"]; ?>"></textarea>
                        <input id="file-upload<?php echo $form_index; ?>" class="file-upload" type="file" name="fileupload"/>
                        <input id="fake-text<?php echo $form_index; ?>" class="fake-text" type="text" placeholder="<?php _e('Attach file (supported type: jpg, png, pdf, doc(x), xls(x), zip).', 'odi'); ?>" />
                        <input id="fake-browse<?php echo $form_index; ?>" class="fake-browse" type="button" value="<?php _e('Browse', 'odi');?>" />

                        <div id="view_upload_status<?php echo $form_index; ?>" class="view_upload_status">
                          <div class="successful_status">
                            <a href="<?php echo site_url(); ?>/#view" target="_blank" id="view_uploaded<?php echo $form_index; ?>"><?php _e("View", 'odi');?></a> <span>|</span>
                            <a id="delete_upload<?php echo $form_index; ?>" href="<?php echo site_url(); ?>/#delete"><?php _e("Delete", 'odi');?></a>
                          </div>
                          <div id="deleted-status<?php echo $form_index; ?>" class="deleted-status"><?php _e("File was deleted", 'odi');?></div>
                          <div id="error-upload<?php echo $form_index; ?>" class="error-upload"><?php _e("ERROR!", 'odi');?></div>
                        </div>


                        <div id="process-state<?php echo $form_index; ?>" class="process-state"></div>

                        <input id="email<?php echo $form_index; ?>" class="email" type="text" placeholder="<?php _e("Your Email (Will not be published)", 'odi');?>" />
                      </div>
                      <div id="disclaimer" class="submit-resource <?php echo ($show_form != "submit-resource")? "hide" : null;?>">
                      	<p class="disclaimer-p" id="disclaimer-p<?php echo $form_index; ?>"><?php echo  __("Disclaimer: ", 'odi').$org_name." ".__("will thoroughly review all submitted resources for integrity and relevancy before the resources are hosted. All hosted resources will be in the public domain, or licensed under Creative Commons. We thank you for your support.", 'odi');?></p>
                      </div>
                      </div>
                      <div id="submit-div<?php echo $form_index; ?>" class="submit-div">
                        <input id="submit-button<?php echo $form_index; ?>" class="submit-button" type="submit" value="<?php _e("Submit", 'odi')?>"/>
                        <div id="process-state-submit<?php echo $form_index; ?>" class="process-state-submit"></div>
                        <span class='needed question-needed' id='question-needed<?php echo $form_index; ?>'><?php _e("* The idea box couldn't be blank!", 'odi');?></span>
                        <span class='needed email-invalid' id='email-invalid<?php echo $form_index; ?>'><?php _e("* The email address is not valid!", 'odi');?></span>
                        <span id="submit-error<?php echo $form_index; ?>" class="submit-error"><?php _e("Something's gone wrong, Please Resubmit the form!", 'odi');?></span>
                      </div>
                    </div>
                     </form>
                    <script type="text/javascript">
                    jQuery( "#tabs<?php echo $form_index; ?>" ).tabs();
                    </script>
                    </div>
                    <div id="thanks-msg">
                        <p>
                        <h2><?php _e("Thank you for taking the time to get in contact!", 'odi'); ?></h2>
                        </p>
                    </div>
    			    </div>
            </div>
    </div>
    <?php user_feedback_form_script( $atts['is_popup_form'], $form_index, $placeholder, $show_form); ?>
<?php } //end function ?>
<?php
function user_feedback_form_script( $is_popup_form = true, $form_index = null, $placeholder=null, $show_form=null){
 ?>
  <link rel="stylesheet" href="<?php echo plugins_url("wp-odm_user_feedback"); ?>/style/upload/style.css" />
  <link href="<?php echo plugins_url("wp-odm_user_feedback"); ?>/style/form.css" rel="stylesheet" type="text/css"/>
  <script type="text/javascript">
  jQuery(document).ready(function($) {
    var hostname = "<?php echo(site_url()); ?>";
    var clicked = true;
    var fileupload = '';
    var uploadfile = false;
    $("input#fake-text<?php echo $form_index; ?>").click(browse);
    $("input#fake-browse<?php echo $form_index; ?>").click(function(){ browse(); });
      function browse(){
    	$("#file-upload<?php echo $form_index; ?>").click();
    }
    $("input#file-upload<?php echo $form_index; ?>").change(function(e) {
        $('div#deleted-status<?php echo $form_index; ?>').hide();
        $("input#fake-text<?php echo $form_index; ?>").val($("input#file-upload<?php echo $form_index; ?>").val().split('\\').pop());
    	  uploadfile = true;
      	if($("input#file-upload<?php echo $form_index; ?>").val() != ''){
      		$("input#submit-button<?php echo $form_index; ?>").click();
      	}
    });
    $("#close-button").click(function(e) {
    	  closeWindow();
    });
    $("#closeform").click(function(e) {
        closeWindow();
    });

    $("#question-textarea<?php echo $form_index; ?>").focus(function(e) {
        if($("span#question-needed<?php echo $form_index; ?>").css('display') != 'none'){
    		$("span#question-needed<?php echo $form_index; ?>").css({'display':'none'});
    		$("#question-textarea<?php echo $form_index; ?>").css({'border':'none'});
    		}
    });

    $('.ask-question').show();

    $('#choice li').click(function(e) {
      $("p#involve-desc").hide();
      $(".hide").hide();
      var placeholder = <?php echo  json_encode($placeholder) ?>;
    	var li = $(this);
      var li_id = li.attr('id');
      var li_class = "."+li.attr('id');
      $(li_class).show();
    	$('#question-textarea<?php echo $form_index; ?>').attr('placeholder',placeholder[li_id]);
    });

    $(document).keydown(function(e) {
      if(e.keyCode==27){
    		closeWindow();
    	}
    });
    function isValidEmailAddress(emailAddress) {
        var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
        return pattern.test(emailAddress);
    };

    function check(){
      if(clicked == true){
      	closeWindow();
    	}
    }
    function closeWindow(){
    	var overlay = $(window.parent.document.getElementById('overlay-div'));
    	$("div#user_feedback_form_fix_left").hide();
        $("div#user_feedback_form_fix_left form").hide();
    	overlay.next().remove();
    	overlay.remove();
    }

    $('#user_feedback_form<?php echo $form_index; ?>').c

    $('#user_feedback_form-form<?php echo $form_index; ?>').submit(function(e) {
      e.preventDefault();
      $('#error-upload<?php echo $form_index; ?>').hide();
      if(uploadfile){
        var ext = $('input#file-upload<?php echo $form_index; ?>').val().split('.').pop().toLowerCase();
        if($.inArray(ext, ['gif','png','jpg','jpeg','pdf','doc','docx','xls','xlsx','zip','rar']) == -1) {
        	$('input#file-upload<?php echo $form_index; ?>').val('');
        	$("input#fake-text<?php echo $form_index; ?>").val('Invalid file type');
          $('#error-upload<?php echo $form_index; ?>').show();
      		$('#error-upload<?php echo $form_index; ?>').html('*Invalid file type!');
        	return;
        }

        $('div#process-state<?php echo $form_index; ?>').css({'display':'block'});

        var formObj = $(this);
        var formURL = formObj.attr("action");
        var formURL = '/wp-admin/'+formURL;
        var formData = new FormData(this);
        $.ajax({
        	url: formURL,
        	type: 'POST',
        	data: formData,
        	mimeType:"multipart/form-data",
        	contentType: false,
        	cache: false,
        	processData:false,
        	success: function(data, textStatus, jqXHR)
        	{
        		if(data.indexOf('img_uploaded:')){
        			$('div#process-state<?php echo $form_index; ?>').removeClass('process-state');
        			$('div#process-state<?php echo $form_index; ?>').addClass('process-state-done');

        			var f = data.trim().replace('img_uploaded:','');
        			fileupload = f;
        			$("input#file-upload<?php echo $form_index; ?>").val('');
        			$("input#file-upload<?php echo $form_index; ?>").attr('disabled','disabled');
        			$("input#fake-text<?php echo $form_index; ?>").val(f);
              <?php $updir = wp_upload_dir(); ?>
        			var href = '<?php echo($updir['baseurl']);?>/wp-odm_user_feedback/temp/';
              $('.successful_status').show();
        			$('#view_uploaded<?php echo $form_index; ?>').attr('href',href + f);
        			$('#delete_upload<?php echo $form_index; ?>').attr('href',href + f);

        		}
        		else{
        			$("input#file-upload<?php echo $form_index; ?>").val('');
        			$("input#fake-text<?php echo $form_index; ?>").val('');
        			$('#error-upload<?php echo $form_index; ?>').show();
        			$('#error-upload<?php echo $form_index; ?>').html(data);
        			$('div#process-state<?php echo $form_index; ?>').css({'display':'none'});
        			}
        	},
        	error: function(jqXHR, textStatus, errorThrown){}
         }).done(function( data ) {
           });
        uploadfile = false;
        return ;
      }

    	if($("#question-textarea<?php echo $form_index; ?>").val().trim()==""){
    		$("span#question-needed<?php echo $form_index; ?>").css({'display':'inherit'});
    		$("#question-textarea<?php echo $form_index; ?>").css({'border':'1px solid #F00'});
    		return false;
    	}
      else{
        $("span#question-needed<?php echo $form_index; ?>").css({'display':'none'});
      }
    	var email_add = $("#email<?php echo $form_index; ?>").val().trim();
    	if(email_add.length != 0){
        if(!isValidEmailAddress(email_add)){
      		$("span#email-invalid<?php echo $form_index; ?>").css({'display':'inherit'});
      		$("#email<?php echo $form_index; ?>").css({'border':'1px solid #F00'});
        	return false;
        }
      }

    	$("input:submit").attr("disabled","disabled");
      var active_form = ($("li.ui-state-active").length)? $("li.ui-state-active").attr("id") : "<?php echo ($show_form != "all")? $show_form : '' ?>";

      jQuery.ajax({
        type: 'POST',
        url: hostname +'/wp-admin/admin-ajax.php',
        data: {
          action: 'FeedbackSubmission',
          question_text: $("#question-textarea<?php echo $form_index; ?>").val(),
          file_name:   $("#fake-text<?php echo $form_index; ?>").val(),
          email: $("#email<?php echo $form_index; ?>").val(),
          question_type:active_form
        },
        beforeSend: function(jqXHR, settings) {
          $('#process-state-submit<?php echo $form_index; ?>').css({'display':'inherit'});
        },
        success: function(data, textStatus, XMLHttpRequest){
          $("input:submit").removeAttr("disabled");
          if(data.trim() == "Successful"){
            $("#get-involve").hide();
            $("#thanks-msg").fadeIn();
            $("#user_feedback_form_container").children("h2").hide();
            $('#process-state-submit<?php echo $form_index; ?>').hide();
          }else {
              $('#submit-error<?php echo $form_index; ?>').show();
              return ;
          }
        },
        error: function(MLHttpRequest, textStatus, errorThrown){
          $('#submit-error<?php echo $form_index; ?>').show();
        }
      }).done(function( data ) {});
    });


    $('#delete_upload<?php echo $form_index; ?>').click(function(e) {
    	e.preventDefault();
    		jQuery.ajax({
      	  type: 'POST',
      	  url: hostname +'/wp-admin/admin-ajax.php',
      	  data: {
        	  action: 'DeleteUploadedFile',
        	  uploadedfile: fileupload,
      	  },
          beforeSend:function(){
            $('.successful_status').hide();
            $('div#process-state<?php echo $form_index; ?>').removeClass('process-state-done');
            $('div#process-state<?php echo $form_index; ?>').addClass('process-state');
          },
      	  success: function(data, textStatus, XMLHttpRequest){
      	    if(data.trim() == "Successful"){
              $('div#process-state<?php echo $form_index; ?>').removeClass('process-state');
        		  $('div#deleted-status<?php echo $form_index; ?>').show();
      		  }else{
        		  $('#error-upload<?php echo $form_index; ?>').show();
        		  $('#error-upload<?php echo $form_index; ?>').html(data);
        		  $('div#process-state<?php echo $form_index; ?>').removeClass('process-state');
            }
      		  $("input#fake-text<?php echo $form_index; ?>").val('');
      		  $("input#file-upload<?php echo $form_index; ?>").removeAttr('disabled');
      		  $('div#process-state<?php echo $form_index; ?>').removeClass('process-state-done');
      		  $('div#process-state<?php echo $form_index; ?>').removeClass('process-state');
      		  $('div#process-state<?php echo $form_index; ?>').addClass('process-state');
      		  $('div#process-state<?php echo $form_index; ?>').css({'display':'none'});
      	  },
      	  error: function(MLHttpRequest, textStatus, errorThrown){
      	  	$('#error-upload<?php echo $form_index; ?>').show();
      		  $('#error-upload<?php echo $form_index; ?>').html("Something's gone wrong, Please try again!");
      	  }
    	  }).done(function( data ) {});
      });

});
</script>
<?php } ?>
