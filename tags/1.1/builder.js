/**
 * Handle: wpeShortcodesAdmin
 * Version: 0.0.1
 * Deps: jquery
 * Enqueue: true
 */

var wpeShortcodesAdmin = function () {}

wpeShortcodesAdmin.prototype = {
    options           : {},
    generateShortCode : function() {
        var content = this['options']['content'];
        delete this['options']['content'];
		var scodename = this['options']['shortcode'];
        delete this['options']['shortcode'];
        var attrs = '';
        jQuery.each(this['options'], function(name, value){
            if (value != '') {
                attrs += ' ' + name + '="' + value + '"';
            }
        });
        var myReturn = '';
        myReturn += '['+ scodename  + attrs + ']'
        if(content !=''){
        	myReturn += content + '[/' + scodename + ']';
        }
		return myReturn;

    },
    sendToEditor      : function(f) {
   		var myID = jQuery('#wpeShortcodes_shortcode').val();
        var collection = jQuery(f).find("select[id^=wpeShortcodes_shortcode],table[id^="+myID+"] select,table[id^="+myID+"] input:not(input:checkbox),table[id^="+myID+"] input:checkbox:checked");
        var $this = this;
        collection.each(function () {
        	if(this.name!='wpeShortcodes_shortcode'){
        		var thisid=myID.length+1;
            	var name = this.name.substring(thisid, this.name.length-1);
            	$this['options'][name] = this.value;
            }else{
            	$this['options']['shortcode'] = this.value;
            }
        });
        send_to_editor(this.generateShortCode());
        return false;
    }
    
}

var wpeShortcodesAdmin = new wpeShortcodesAdmin();

//hide where necessary
jQuery(document).ready(function(){
	jQuery("#wpeShortcodes_shortcode option").each(function(){
		//gets select values to hide where necessary
		var mehide = jQuery(this).val();
		jQuery('#' + mehide).hide();
	});
	jQuery("#wpeShortcodes_shortcode").change(function(){
		jQuery("#" + this.value).show().siblings().hide();
	});
	jQuery("#wpeShortcodes_shortcode").change();
});