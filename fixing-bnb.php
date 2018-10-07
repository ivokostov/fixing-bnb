<?php
/*
Plugin Name: Fiksing BNB
Description: Валутни курсове на БНБ
Version: 1.0
Author: Xhats.com
Author URI: https://xhats.com/
*/


class XhatsFixingBnb extends WP_Widget {
  var $api = 'http://xhats.com/wp-json/fixing-bnb/v1/data/';
	var $currencies = array('AUD','BRL','CAD','CHF','CNY','CZK','DKK','GBP',
													'HKD','HRK','HUF','IDR','ILS','INR','ISK','JPY',
													'KRW','MXN','MYR','NOK','NZD','PHP','PLN','RON',
													'RUB','SEK','SGD','THB','TRY','USD','ZAR','XAU',
                          'EUR'
	);
	public function __construct() {
		$widget_options = array(
				'classname' => 'fixing_bnb',
			  'description' => 'Валутни курсове на БНБ'
			);
		parent::__construct('fixing_bnb', 'Фиксинг БНБ', $widget_options);
	}

	public function widget($args, $instance) {
    $plugin_url = plugin_dir_url(__FILE__);
		$title = apply_filters('widget_title', $instance['title']);
		echo $args['before_widget'] . $args['before_title'] . $title . $args['after_title'];
		$currencies = (array)$instance['currencies'];
		$data = file_get_contents($this->api);
		$data = json_decode($data, true);
    foreach ($currencies as $currencie) {
      $sorted_data[$currencie] = $data['data'][$currencie];
    }
		echo "<ul class=\"fixing-bnb\">";
		foreach($sorted_data as $key => $value) {
			if(in_array($key, $currencies)) {
        $icon = "<img src=\"{$plugin_url}/assets/icons/{$key}.png\">";
				echo "<li title=\"{$value[1]}\">{$value[3]} {$icon} {$key} <b>{$value[4]} лв.</b></li>";
			}
		}
		echo "</ul>";
		echo $args['after_widget'];
	}

	public function form($instance) {
		$title = ! empty($instance['title']) ? $instance['title'] : '';
		$currencies = (array)$instance['currencies'];
    $currencies_list = $this->sort_curency($currencies); ?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Заглавие:</label>
			<input class="widefat js-trigger-sortable" type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($title); ?>" />
		</p>
		<p><label for="<?php echo $this->get_field_id('currencies'); ?>">Избери валута:<br/></label></p>
    <ul class="sortable-bnb">
				<?
				foreach($currencies_list as $currency){
					echo '<li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><label style="cursor: pointer; width: 33.33%; float: left; line-height: 24px;"><input type="checkbox" name="'.$this->get_field_name('currencies').'[]" value="'.$currency.'" '.(in_array($currency,$currencies)?' checked="checked"':'').'/> '.$currency."</label></li>";
				}?>
			</ul>
		<div style="clear:both;"></div><?php
	}

	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['currencies'] = (array)$new_instance['currencies'];
		return $instance;
	}
	public function sort_curency($saved_currencies) {
		$currencies = $this->currencies;
    if(is_array($saved_currencies)) {
      $firsts_currencie = array();
      foreach ($saved_currencies as $saved_currencie) {
        if(in_array($saved_currencie, $currencies)) {
          if (($key = array_search($saved_currencie, $currencies)) !== false) {
              unset($currencies[$key]);
              $firsts_currencie[] = $saved_currencie;
          }
        }
      }
      $currencies = array_merge($firsts_currencie, $currencies);
    }
    return $currencies;
	}

}

function xhats_register_fixing_bnb() {
  register_widget('XhatsFixingBnb');
}
add_action('widgets_init', 'xhats_register_fixing_bnb');

function xhats_register_style_fixing_bnb() {
  wp_register_style('fixing-bnb-css', plugins_url('/assets/style.css',__FILE__ ));
  wp_enqueue_style('fixing-bnb-css');
}
add_action('wp_enqueue_scripts', 'xhats_register_style_fixing_bnb', 110);

function xhats_register_admin_style_fixing_bnb() {
  wp_enqueue_style('jquery-ui-css', 'http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
  wp_enqueue_style('fixing-bnb-admin-css', plugins_url('/assets/admin-style.css', __FILE__));
  wp_enqueue_script('jquery-ui-js', 'https://code.jquery.com/ui/1.12.1/jquery-ui.js' );
  wp_enqueue_script('fixing-bnb-admin-js', plugins_url('/assets/script.js', __FILE__));
}
add_action('admin_enqueue_scripts', 'xhats_register_admin_style_fixing_bnb');
