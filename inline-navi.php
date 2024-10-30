<?php
/*
Plugin Name: Inline Navi
Plugin URI: http://conchanental.jp/
Description: This plug-in displays the contents of the child page on a parent page with a tab.
Author: Conchanental Planning
Version: 1.2.1
Author URI: http://conchanental.jp/
*/

/*  Copyright 2013 Conchanental Planning (email : info@conchanental.jp)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**********************
 ページ内タブの設置
**********************/
if(!is_admin()):
	$name = 'inline-navi';
	$js_path = '/js/inline-navi.js';
	$css_path = '/css/inline-navi.css';
	$js_url = plugins_url($js_path, __FILE__);
	$css_url = plugins_url($css_path, __FILE__);
	
	// js、css登録
	wp_register_script($name, $js_url, array('jquery'));
	wp_register_style($name, $css_url);
	
	// js、css出力
	wp_enqueue_script($name);
	wp_enqueue_style($name);
	add_action('wp_print_scripts', 'inlinenavi_javascript');
endif;

// wp_ajaxアクションフック登録
add_action('wp_ajax_inline_navi_ajax', 'inline_navi_ajax');
add_action('wp_ajax_nopriv_inline_navi_ajax', 'inline_navi_ajax');


// ページ読込時動作
function change_inline_navi($atts){

	extract(shortcode_atts(array( // 引数デフォルト値
		'ajax' => 'off',
		'position' => 'top',
		), $atts));

	$id = get_the_id(); // 現在のIDを取得
	$children = get_pages('sort_column=menu_order&child_of='.$id); // 子孫ページ情報取得
	$class_position = ' top';
	if($position == 'bottom'): // position='bottom'
		$class_position = ' bottom';
	endif;

// タブ、コンテンツ設置
	$str_tab = '<div class="inline_navi'.$class_position.'"><ul>'."\n";
	$str_contents = '';
	$current = 0;

	foreach($children as $data){

		if($data->post_parent == $id): // 子or孫以下の振り分け
			if($current == 0):
				$str_tab .= '<li class="inline_current"><a class="subjectcolor btn_inline_navi">'.$data->post_title.'</a></li>'."\n"; // タブ
				$str_contents .= '<div class="inline_navi_win">'.do_shortcode($data->post_content).'</div>'."\n"; // コンテンツ
			else:
				if($ajax == 'on') : // ajax='on'
					$str_tab .= '<li><a id="'.$data->ID.'" class="subjectcolor btn_inline_navi btn_ajax">'.$data->post_title.'</a></li>'."\n"; // タブ
					$str_contents .= '<div class="inline_navi_win none">Loading...</div>'."\n"; // コンテンツ
				else : // ajax='off'
					$str_tab .= '<li><a class="subjectcolor btn_inline_navi">'.$data->post_title.'</a></li>'."\n"; // タブ
					$str_contents .= '<div class="inline_navi_win none">'.do_shortcode($data->post_content).'</div>'."\n"; // コンテンツ
				endif;
			endif;
			$current++;
		endif;

	}

	$str_tab .= '</ul>'."\n".'</div>'."\n";
	$str_return = $str_tab.$str_contents;
	if($position == 'bottom'): // position='bottom'
		$str_return = $str_contents.$str_tab;
	endif;
	return $str_return;
}
// ショートコード登録
	add_shortcode('inline_navi', 'change_inline_navi');

// ajaxリクエスト送信用javascript
function inlinenavi_javascript() {

?>
	<script type="text/javascript" >
		function sendAjaxRequests(pid, pindex){
			var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
			jQuery(document).ready(function($) {
				var data = {
					action: 'inline_navi_ajax',
					pID: pid
				};
				jQuery.post(ajaxurl,
					data,
					function(response) {
						jQuery('div.inline_navi_win:eq(' + pindex +')').html(response);
					}
				);
			});
		}
	</script>
<?php
}

// ajax返信
function inline_navi_ajax() {
	$pid = $_POST['pID'];

	$post_data = get_post($pid);
	echo do_shortcode($post_data->post_content);

	die();
}
?>