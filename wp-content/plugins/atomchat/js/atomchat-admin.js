
var atomchat_show_friends;
var atomchat_bp_group_sync;
var auth_key;
var api_key;
var atomchat_enable_mycred;
var mycred_url;

jQuery(document).ready(function() {

    jQuery(".tab-links").find("li").click(function(){
        jQuery(".menus").removeClass("active");
        jQuery(this).addClass("active");
        var rel=jQuery(this).data("rel");
        jQuery(".tab").hide();
        jQuery("#"+rel).show();
    });
    jQuery('#save').on('click', function(e) {
        atomchat_show_friends = jQuery("input.atomchat_show_friends[type=checkbox]:checked").val();

        if(atomchat_show_friends == '' || typeof(atomchat_show_friends) == 'undefined'){
            atomchat_show_friends = "false";
        }else{
            atomchat_show_friends = "true";
        }

        atomchat_bp_group_sync = jQuery("input.atomchat_bp_group_sync[type=checkbox]:checked").val();

        if(atomchat_bp_group_sync == '' || typeof(atomchat_bp_group_sync) == 'undefined'){
            atomchat_bp_group_sync = "false";
        }else{
            atomchat_bp_group_sync = "true";
        }

        data = {
            'action': 'atomchat_action',
            'api': 'atomchat_friend_ajax',
            'atomchat_show_friends': atomchat_show_friends,
            'atomchat_bp_group_sync': atomchat_bp_group_sync
        }

        jQuery.post(ajaxurl, data, function(response){
            jQuery("#success").html("<div class='updated'><p>Settings successfully saved!</p></div>");
            jQuery(".updated").fadeOut(3000);
        });
    });

    jQuery(".atomchat_enable_mycred").change(function(){
        if(jQuery(".atomchat_enable_mycred")[0].checked == true){
            jQuery("#atomchat_roles").show();
        }else{
            jQuery("#atomchat_roles").hide();
        }
    });

    jQuery("input.atomchat_auth_key[type=text], input.atomchat_api_key[type=text]").focus(function(){
        jQuery(this).parent().removeClass('invalid-input');
    });

    jQuery("[name=edit_credit]").on("click",function() {
        var id = this.id;
        var role = id.replace("atomchat_edit_credits_","");
        var creditToDeduct = jQuery("#creditToDeduct_"+role).val();
        var creditOnMessage = jQuery("#creditOnMessage_"+role).val();
        var creditToDeductAudio = jQuery("#creditToDeductAudio_"+role).val();
        var creditToDeductAudioOnMinutes = jQuery("#creditToDeductAudioOnMinutes_"+role).val();
        var creditToDeductVideo = jQuery("#creditToDeductVideo_"+role).val();
        var creditToDeductVideoOnMinutes = jQuery("#creditToDeductVideoOnMinutes_"+role).val();

        data = {
            'action': 'atomchat_action',
            'api' : 'atomchat_update_credeits',
            'role': role,
            'creditToDeduct' : creditToDeduct,
            'creditOnMessage' : creditOnMessage,
            'creditToDeductAudio' : creditToDeductAudio,
            'creditToDeductAudioOnMinutes' : creditToDeductAudioOnMinutes,
            'creditToDeductVideo' : creditToDeductVideo,
            'creditToDeductVideoOnMinutes' : creditToDeductVideoOnMinutes
        }

        jQuery.post(ajaxurl, data, function(response){
            jQuery("#atomchat_update_credeits_role_"+role).html("<div class='updated'><p>Settings successfully saved!</p></div>");
            jQuery(".updated").fadeOut(3000);
        });


    });

    jQuery("#atomchat_update_credeits").on('click',function(e){
        atomchat_enable_mycred = jQuery("input.atomchat_enable_mycred[type=checkbox]:checked").val();

        if(atomchat_enable_mycred == '' || typeof(atomchat_enable_mycred) == 'undefined'){
            atomchat_enable_mycred = "false";
        }else{
            atomchat_enable_mycred = "true";
        }
        if(atomchat_enable_mycred != "false"){
            mycred_url = location.href;
            var postion = mycred_url.search("wp-admin");
            mycred_url = mycred_url.slice(0,postion-1);
            mycred_url = mycred_url.replace("https://","");
        }else{
            mycred_url = "";
        }
        data = {
            'action': 'atomchat_action',
            'api': 'atomchat_mycred_setting',
            'atomchat_enable_mycred': atomchat_enable_mycred,
            'mycred_url' : mycred_url
        }

        jQuery.post(ajaxurl, data, function(response){
            jQuery("#success_mycred").html("<div class='updated'><p>Settings successfully saved!</p></div>");
            jQuery(".updated").fadeOut(3000);
        });

    });
    jQuery(".atomchat_role").on('click',function() {
        var id = this.id;
        if(jQuery("#atomchat_content_"+id).is(":visible")){
            jQuery("#atomchat_content_"+id).hide();
        }else{
            jQuery("#atomchat_content_"+id).show();
        }

    });

    jQuery('#update_auth_key').on('click', function(e) {
        var validate = true;
        auth_key = jQuery("input.atomchat_auth_key[type=text]").val();
        api_key  = jQuery("input.atomchat_api_key[type=text]").val();

        if(auth_key == '') {
            jQuery("input.atomchat_auth_key[type=text]").parent().addClass('invalid-input');
            validate = false;
        }
        if(api_key == '') {
            jQuery("input.atomchat_api_key[type=text]").parent().addClass('invalid-input');
            validate = false;
        }
        if(!validate) {
            return;
        }
        data = {
            'action': 'atomchat_action',
            'api': 'atomchat_update_auth_ajax',
            'atomchat_auth_key': auth_key,
            'atomchat_api_key': api_key
        }
        jQuery.post(ajaxurl, data, function(response){
            jQuery("#success_auth").html("<div class='updated'><p>Settings updated successfully!</p></div>");
            jQuery(".updated").fadeOut(3000);
        });
        if(jQuery(this).attr('level') == 'init') {
            cometGoSettings();
        }
    });

    jQuery('#update_layout_setting').on('click', function(e) {
        show_docked_layout_on_all_pages = jQuery("input.show_docked_layout_on_all_pages[type=checkbox]:checked").val();

        show_name_in_chat = jQuery("input.show_name_in_chat[name=chat_username]:checked").val();

        if(show_docked_layout_on_all_pages == '' || typeof(show_docked_layout_on_all_pages) == 'undefined'){
            show_docked_layout_on_all_pages = "false";
        }else{
            show_docked_layout_on_all_pages = "true";
        }

        data = {
            'action': 'atomchat_action',
            'api': 'atomchat_update_layout_ajax',
            'show_docked_layout_on_all_pages': show_docked_layout_on_all_pages,
            'show_name_in_chat': show_name_in_chat
        }

        jQuery.post(ajaxurl, data, function(response){
            jQuery("#success_layout").html("<div class='updated'><p>Settings successfully saved!</p></div>");
            jQuery(".updated").fadeOut(3000);
        });
    });

    /* Start: Copying the docked layout shortcode */
    var clipboard = new ClipboardJS('.copy', {
        target: function(trigger) {
            return trigger.previousSibling;
        }
    });
    clipboard.on('success', function(e) {
        e.trigger.innerText = 'Copied!';
        setTimeout(function() {e.trigger.innerText = 'Copy';},1000);
        e.clearSelection();
    });
    /* End: Copying the docked layout shortcode */

});
