<?php
 if ( ! defined( 'ABSPATH' ) ) { exit; // Exit if accessed directly 
} 
 if ( ! class_exists( 'WP_List_Table' ) ) { 
	 require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php'; 
}


class Person_Table extends WP_List_Table{

	private $_items;

	function __construct($args = array())
	{
		parent::__construct($args);
	}

	function set_data($data){
		$this->_items = $data;
		
	}

	function get_columns()
	{
		return [
			'cb' => '<input type="checkbox">',
			'sex' => __("Gender","tabledata"),
			'name' => __('Name','tabledata'),
			'email' => __('Email','tabledata'),
			'age' => __("Age","tabledata"),

		];
	}

	function get_sortable_columns()
	{
		return [
			'age' => ['age',true],
			'name' => ['name',true]
		];
	}

	function column_cb($item)
	{
		return "<input type='checkbox' value='{$item['id']}'>";
	}

	function column_email($item){
		return "<strong>{$item['email']}</strong>";
	}

	function column_age($item){
		return "<em>{$item['age']}</em>";
	}

	function column_default($item, $column_name)
	{
		return $item[$column_name];
	}

	function extra_tablenav($which)
	{
		if('top' == $which) : 
		?>
		<div class="actions alignleft">
			<select name="filter_s" id="filter_s">
				<option value="all">All</option>
				<option value="M">Male</option>
				<option value="F">Female</option>
			</select>
			<?php submit_button(__("Filter","tabledata"),"Button","Submit",false);?>
		</div>
	<?php endif;}

	function prepare_items()
	{
		$paged = $_REQUEST['paged'] ?? 1;
		$per_page = 3;
		$total_items = count($this->_items);
		$this->_column_headers = array($this->get_columns(),array(),$this->get_sortable_columns());

		$data_chunk = array_chunk($this->_items,$per_page);
		$this->items = $data_chunk[$paged -1];
		$this->set_pagination_args([
			'total_items' => $total_items,
			'per_page' => $per_page,
			'total_pages' => ceil(count($this->_items) / $per_page)

		]);
	}
}