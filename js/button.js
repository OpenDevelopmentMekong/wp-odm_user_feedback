jQuery(document).ready(function($) {
  var feedback_position = $(".hide-feedbackbuttom").offset().top +"px";
      $('.show-feedbackbuttom').css('top', feedback_position);

    $("a#user_feedback_form").click(function(e) {
        $("div#wrap-feedback").after('<div id="overlay-div"></div>');
        $("div#overlay-div").after('<div id="loading-form"></div>');
        $('#thanks-msg').hide();
        $('#get-involve').show();
        $('span.needed').hide();
        $('#user_feedback_form-form').find("input[type=text], textarea").removeAttr( 'style' );
        $('div#process-state').css({'display':'none'});
        $('#user_feedback_form-form').find("input[type=text], textarea").val("");
        $("div#user_feedback_form_fix_left").fadeIn(500);
        $("div#user_feedback_form_fix_left form").fadeIn(500);
    });
    $('.hide-feedbackbuttom').click(function(){
      $(".wrap-feedback_fix_left").hide("slide", { direction: "left" }, 400);
      $('.show-feedbackbuttom').show("slide", { direction: "left" }, 700);
    });
    $('.show-feedbackbuttom').click(function(){
      $('.show-feedbackbuttom').hide("slide", { direction: "left" }, 400);
        $(".wrap-feedback_fix_left").show("slide", { direction: "left" }, 700);
    });

    $(window).scroll(function () {
       $('.show-feedbackbuttom').css('top', feedback_position);
    });
});
