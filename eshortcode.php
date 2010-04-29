<?php
/*
Plugin Name: eShortcode
Plugin URI: http://quirm.net/
Description: Adds a form to the page/post edit screen for adding an exisiting shortcode to the editor. Contains gallery and caption by default. Designed to be extended by plugin authors. Based on original work by Aaron D. Campbell http://xavisys.com/wordpress-25-shortcodes/
Version: 1.0
Author: Rich Pedley 
Author URI: http://quirm.net/

    Copyright 2010  R PEDLEY  (email : rich@quirm.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
  
*/
/*
====
Quick details
====
2 requirements - add a type and add the form
type:
add_filter('eshortcodesType', 'yourFunction');
format $values[type]='nice name for select box'

form:
enclosing table id must match type
id needs to match the type
all form id need to be of the format : yourType_shortcodeattribute
all form name need to be of the format : yourType[shortcodeattribute]

see example function eshortGalleryForm


*/
load_plugin_textdomain( 'eShortcodes', false, dirname( plugin_basename( __FILE__ ) ) ); 
function handleAdminMenu() {
    // You have to add one to the "post" writing/editing page, and one to the "page" writing/editing page
    add_meta_box('eshortcodesID', 'Shortcodes', 'eshortcodesinsertForm', 'post', 'normal');
    add_meta_box('eshortcodesID', 'Shortcodes', 'eshortcodesinsertForm', 'page', 'normal');
}

function eshortcodesinsertForm() {
?>
	<table class="form-table">
		<tr>
			<th><label for="wpeShortcodes_shortcode"><?php _e('Shortcode:','eShortcodes')?></label></th>
			<td><select id="wpeShortcodes_shortcode" name="wpeShortcodes_shortcode">
			<?php 
			$values=apply_filters('eshortcodesType', $values );
			foreach($values as $value=>$text){
				echo '<option value="'.$value.'">'.$text.'</option>';
			}
			?>
			</select>
			<p class="submit">
				<input type="button" onclick="return wpeShortcodesAdmin.sendToEditor(this.form);" value="<?php _e('Send Shortcode to Editor &raquo;','eShortcodes'); ?>" />
			</p>
			</td>
		</tr>
	</table>
	<div id="wpeShortcodes_div">
		<?php
		apply_filters('eshortcodesForm', $values );
		?>
	</div>
	<?php
}

function adminHead () {
    if ($GLOBALS['editing']) {
        wp_enqueue_script('wpeShortcodesAdmin', WP_PLUGIN_URL . '/eshortcode/builder.js', array('jquery'), '1.0.0');
    }
}

add_action('admin_menu', 'handleAdminMenu');
add_filter('admin_print_scripts', 'adminHead');
//example 2 requirements - add a type and add the form
add_filter('eshortcodesType', 'eshortTypes');

function eshortTypes($values){
/*
format $values[type]='nice name for select box'
*/
	$values['gallery']=__('Gallery','eShortcodes');
	$values['caption']=__('Caption','eShortcodes');
	return $values;
}

add_filter('eshortcodesForm', 'eshortGalleryForm');

function eshortGalleryForm($data){
/*
id needs to match the type
all form id need to be of the format : yourType_shortcodeattribute
all form name need to be of the format : yourType[shortcodeattribute]
*/
?>
	<table id="gallery" class="form-table">
		<tr>
			<th scope="row"><label for="gallery_content"><?php _e('Content:','eShortcodes')?></label></th>
			<td>
				<input type="text" size="40" style="width:95%;" name="gallery[content]" id="gallery_content" />
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="gallery_columns"><?php _e('Columns:','eShortcodes')?></label></th>
			<td>
			<select id="gallery_columns" name="gallery[columns]">
			<option value=""><?php _e('Default or choose')?></option>
			<?php
				for($i=0;$i<=10;$i++){
				?>
				 <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
				 <?php
				 }
			  ?>
			</select>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="gallery_id"><?php _e('Page/Post ID:','eShortcodes')?></label></th>
			<td>
				<input type="text" size="8" style="width:30%;" name="gallery[id]" id="gallery_id" />
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="gallery_size"><?php _e('Image Size:','eShortcodes')?></label></th>
			<td><select id="gallery_size" name="gallery[size]">
			<option value=""><?php _e('Default or choose')?></option>
			<option value="thumbnail"><?php _e('Thumbnail')?></option>
			<option value="medium"><?php _e('Medium')?></option>
			<option value="large"><?php _e('Large')?></option>
			<option value="full"><?php _e('Full')?></option>
			</select>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="gallery_orderby"><?php _e('Order By:','eShortcodes')?></label></th>
			<td><select id="gallery_orderby" name="gallery[orderby]">
			<option value=""><?php _e('Default or choose','eShortcodes')?></option>
			<option value="menu_order"><?php _e('Menu Order','eShortcodes')?></option>
			<option value="ID"><?php _e('ID','eShortcodes')?></option>
			<option value="RAND"><?php _e('Random','eShortcodes')?></option>
			</select>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="gallery_itemtag"><?php _e('Itemtag:','eShortcodes')?></label></th>
			<td>
				<input type="text" size="8" style="width:30%;" name="gallery[itemtag]" id="gallery_itemtag" />
			</td>
		</tr>
		<tr>
			 <th scope="row"><label for="gallery_icontag"><?php _e('Icontag:','eShortcodes')?></label></th>
			 <td>
				 <input type="text" size="8" style="width:30%;" name="gallery[icontag]" id="gallery_icontag" />
			 </td>
		</tr>
		<tr>
			 <th scope="row"><label for="gallery_captiontag"><?php _e('Captiontag:','eShortcodes')?></label></th>
			 <td>
				 <input type="text" size="8" style="width:30%;" name="gallery[captiontag]" id="gallery_captiontag" />
			 </td>
		</tr>
		<tr>
			<th scope="row"><label for="gallery_link"><?php _e('Link to:','eShortcodes')?></label></th>
			<td><select id="gallery_link" name="gallery[link]">
			<option value=""><?php _e('Default or choose','eShortcodes')?></option>
			<option value="file"><?php _e('File','eShortcodes')?></option>
			</select>
			</td>
		</tr>
		<tr>
			 <th scope="row"><label for="gallery_include"><?php _e('Include:','eShortcodes')?></label></th>
			 <td>
				 <input type="text" size="8" style="width:95%;" name="gallery[include]" id="gallery_include" />
			 </td>
		</tr>
		<tr>
			 <th scope="row"><label for="gallery_exclude"><?php _e('Exclude:','eShortcodes')?></label></th>
			 <td>
				 <input type="text" size="8" style="width:95%;" name="gallery[exclude]" id="gallery_exclude" />
			 </td>
		</tr>
	</table>
	
<?php
}

add_filter('eshortcodesForm', 'eshortCaptionForm');

function eshortCaptionForm($data){
/*
id needs to match the type
all form id need to be of the format : yourType_shortcodeattribute
all form name need to be of the format : yourType[shortcodeattribute]
*/
?>
	<table id="caption" class="form-table">
		<tr>
			<th scope="row"><label for="caption_content"><?php _e('Content:','eShortcodes')?></label></th>
			<td>
				<input type="text" size="40" style="width:95%;" name="caption[content]" id="caption_content" />
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="caption_id"><?php _e('Id:','eShortcodes')?></label></th>
			<td>
				<input type="text" size="40" style="width:95%;" name="caption[id]" id="caption_id" />
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="caption_align"><?php _e('Align:','eShortcodes')?></label></th>
			<td>
				<input type="text" size="40" style="width:95%;" name="caption[align]" id="caption_align" />
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="caption_width"><?php _e('Width:','eShortcodes')?></label></th>
			<td>
				<input type="text" size="40" style="width:30%;" name="caption[width]" id="caption_width" />
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="caption_caption"><?php _e('Caption:','eShortcodes')?></label></th>
			<td>
				<input type="text" size="40" style="width:95%;" name="caption[caption]" id="caption_caption" />
			</td>
		</tr>
	</table>
<?php
}
?>