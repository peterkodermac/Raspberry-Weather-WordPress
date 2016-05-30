<?php
/*
 * Plugin Name: Raspberry Weather
 * Plugin URI: www.raspberryweather.com
 * Description: Easily display temperatures and humidity taken by your Raspberry Pi.
 * Version: 1.4
 * Author: Peter Kodermac
 * Author URI: http://www.kodermac.com
 * License: GPL2
 */

 //Forgive me Wordpress gurus
 
global $wpdb;

add_filter('mce_external_plugins', "raspberryweather_register");
add_filter('mce_buttons', 'raspberryweather_add_button', 0);

function raspberryweather_add_button($buttons)
{
	array_push($buttons, "separator", "raspberryweather");
	return $buttons;
}

function raspberryweather_register($plugin_array)
{
	$url = plugins_url( 'editor_plugin.js', __FILE__ );
	$plugin_array['raspberryweather'] = $url;
	return $plugin_array;
}

//Add JS loading to head
function visualization_load_js()
{
	echo '<script type="text/javascript">';
	echo 'google.load(\'visualization\', \'1\', {packages: [\'corechart\']});';
	echo '</script>';
}

// Store the IDs of the generated graphs
$graph_ids = array();
// Create a DIV placeholder for the Visualization API

function visualization_new_div($id, $width, $height)
{
	return "<div id=\"" . $id . "\" style=\"width: " . $width . "; height: " . $height . ";\"></div>";
}

//Generate a line chart
function visualization_line_chart_shortcode($atts, $content = null)
{
	//use global variables
	global $graph_ids;
	global $wpdb;
        //setup of table name
        $tablename        = "temperatures";
        //make it convertable for blog switching
        $wpdb->tables[]   = $tablename;
        //prepare it for use in actual blog
        $wpdb->$tablename = $tablename; //$wpdb->get_blog_prefix() . $tablename;
	$options = shortcode_atts(array(
		'width' => "400px",
		'height' => "300px",
		'title' => "Graph",
		'day' => "Today",
		'display' => "Temperatures",
		'scale' => "Celsius",
		'h_title' => "",
		'v_title' => "",
		
		//By default give iterated id to the graph
		'id' => "graph_" . count($graph_ids)
	), $atts);
	
	//Register the graph ID
	$graph_ids[] = $options['id'];
	
	//The content that will replace the shortcode
	$graph_content = "";
	
	//Generate the div
	$graph_content .= visualization_new_div($options['id'], $options['width'], $options['height']);
	
	//Generate the Javascript for the graph
	$graph_draw_js = "";
	
	$graph_draw_js .= '<script type="text/javascript">';
	$graph_draw_js .= 'function draw_' . $options['id'] . '(){';
	
	//Create the graph
	$whereConditions	= "";
	$options[day]           = esc_sql($options[day]);
	if(strpos($options[day],"Week")!==false)  //current week
		$whereConditions= "datemeasured between '".date("Y-m-d", strtotime("-1 week"))."' and '".date('Y-m-d')."'";
	else if(strpos($options[day],"Month")!==false) //current month
		$whereConditions= "MONTH(datemeasured)='".date('m')."'";
	else
		$whereConditions= "datemeasured='".date('Y-m-d', esc_sql(strtotime($options[day])))."'"; //what day needs to be displayed?
	
	$temperatureMeasurement = esc_sql($options[temperatureMeasurement]); //celsius or fahrenheit?
	$display                = esc_sql($options[display]); //do we show only temp, only humidity or both?
	$displayMeasurement     = esc_sql($options[scale]);
	
	//check for all types of temperature
	if (strcasecmp($display, "Temperature") == 0 OR strcasecmp($display, "Temperatures") == 0 || strcasecmp($display, "Temp") == 0 || strcasecmp($display, "Temps") == 0)
		$display = "hourMeasured, temperature, dateMeasured";
	
	else if (strcasecmp($display, "Humidity") == 0 OR strcasecmp($display, "Hum") == 0)
		$display = "hourMeasured, humidity, dateMeasured";
	
	else
		$display = "*";
	
	//check for temperature measurement
	if (strcasecmp($displayMeasurement, "Celsius") == 0 || strcasecmp($displayMeasurement, "C") == 0 || strcasecmp($displayMeasurement, "Celzius") == 0)
		$displayMeasurement = "C";
	else
		$displayMeasurement = "F";
	
	$resultSet = $wpdb->get_results("SELECT " . $display . " FROM temperatures WHERE " . $whereConditions, ARRAY_A);
	
	
	$graph_draw_js .= 'var graph = new google.visualization.LineChart(document.getElementById(\'' . $options['id'] . '\'));';
	
	if (($wpdb->num_rows) == 0) //nothing in table 
		{
		$content = "['Sample Time','Sample Temperature [" . $displayMeasurement . "]','Sample Humidity [%]'],"; //tell the user he has empty table
		
		$content .= "
				['00:00',  10,      75],
				['00:30',  10,      73],
				['01:00',  10,       71],
				['01:30',  11,      71],
				['02:00',  11,      68],
				['02:30',  11,      67],
				['03:00',  12,       65],
				['03:30',  12,      65],
				['04:00',  13,      64],
				['04:30',  13,      62],
				['05:00',  14,       60],
				['05:30',  14,      60],
				['06:00',  14,      57],
				['06:30',  15,      54],
				['07:30',  15,       52],
				['08:30',  16,      47],
				['09:00',  16,      43],
				['09:30',  17,      43],
				['10:30',  17,       42],
				['11:00',  18,      44],
				['11:30',  19,      41],
				['12:00',  20,      40],
				['12:30',  21,      40],
				['13:00',  21,      40],
				['13:30',  22,      37],
				['14:00',  23,      40],
				['14:30',  22,      42],
				['15:00',  22,      44],
				['15:30',  22,      45],
				['16:00',  21,      47],
				['16:30',  20,      47],
				['17:00',  18,      48],
				['17:30',  18,      49],
				['18:00',  17,      50],
				['18:30',  17,      50],
				['19:00',  16,      51],
				['19:30',  16,      52],
				['20:00',  15,      53],
				['20:30',  15,      56],
				['21:00',  14,      57],
				['21:30',  13,      59],
				['22:00',  13,      62],
				['22:30',  12,      64],
				['23:00',  11,      65],
				['23:30',  10,      65],
		";
	}
	
	else //something is in the database for selected day
		{ //what needs to be displayed - temps or umidity or both?
		
		if (strcasecmp($display, "*") == 0) {
			if (strcmp($displayMeasurement, "C") == 0)
				$content = "['Time','Temperature [C]','Humidity [%]'],";
			else
				$content = "['Time','Temperature [F]','Humidity [%]'],";
			
		}
		//displaying only TEMPERATURE
		else if (strpos($display, "temperature") != 0) {
			if (strcmp($displayMeasurement, "F") == 0)
				$content = "['Time','Temperature [F]'],";
			else
				$content = "['Time','Temperature [C]'],";
		}
		//displaying only HUMIDITY
		else if (strpos($display, "humidity") != 0) {
			$content = "['Time','Humidity [%]'],";
		}
	}
	
	//graph content - readings from sensor
	foreach ($resultSet as $row) {
		$hourMeasured = $row['hourMeasured'];
		if (strcmp($displayMeasurement, "C") == 0)//display celsius
			$temperature = $row['temperature'];
		
		else//calculate to fahrenheit
			$temperature = $row['temperature'] * (9 / 5) + 32;
		
		//x axis - if weekly or monthly display selected, do not show hourMeasured but date
		if(strpos($options[day],"Month")!==false || strpos($options[day],"Week")!==false)
			$AxisX=date("d.m",  strtotime($row['dateMeasured']));
		else
			$AxisX=gmdate("H:i", ($hourMeasured * 60));

		if (strpos($display, "humidity") != 0) //for displaying humidity only
			$content .= "['" . $AxisX . "'," . $row['humidity'] . "],";
		else
			$content .= "['" . $AxisX . "'," . $temperature . "," . $row['humidity'] . "],";
	}
	
	//Populate the data
	$graph_draw_js .= 'var data = google.visualization.arrayToDataTable([';
	$graph_draw_js .= str_replace(array(
		'<br/>',
		'<br />'
	), '', $content);
	$graph_draw_js .= ']);';
	
	//Create the options
	$graph_draw_js .= 'var options = {';
	$graph_draw_js .= 'curveType: "function", ';

	if($wpdb->num_rows>90) //there are more than 4 days of readings that need to be displayed
        {
		 $graph_draw_js .= 'hAxis:{showTextEvery: 90}, '; //TODO
        }


	$graph_draw_js .= 'animation: {duration: 1200, easing:"in"}, ';
	$graph_draw_js .= 'title:"' . $options['title'] . '",';
	$graph_draw_js .= 'width:\'' . $options['width'] . '\',';
	$graph_draw_js .= 'height:\'' . $options['height'] . '\',';
	$graph_draw_js .= 'legend:\'bottom\','; //can be: bottom, top, in, none and right TODO
	$graph_draw_js .= 'backgroundColor: "transparent",';
	
	if (!empty($options['h_title']))
		$graph_draw_js .= 'hAxis: {title: "' . $options['h_title'] . '", slantedText:true},';
	
	
	if (!empty($options['v_title']))
	{
		$resultSet =$wpdb->get_results("SELECT temperature FROM temperatures WHERE " . $whereConditions . " ORDER BY temperature ASC LIMIT 1");//get lowest temperature  for chosen date
		$graph_draw_js .= 'vAxis: {title: "' . $options['v_title'] . '", viewWindow: {min:".$resultSet."}}';
	
	}
	else
		$graph_draw_js .= 'vAxis: {viewWindow: {min:-2}}';
	
	
	$graph_draw_js .= '};';
	$graph_draw_js .= 'graph.draw(data, options);';
	
	$graph_draw_js .= '}';
	$graph_draw_js .= '</script>';
	$graph_content .= $graph_draw_js;
	define("QUICK_CACHE_ALLOWED", false); //Quick Cache will not be caching the site displaying the measurements!
	return $graph_content;
}

//Filter to add JS to load all the graphs previously entered as shortcodes

function visualization_load_graphs_js($content)
{
	//use global variables
	global $graph_ids;
	
	if (count($graph_ids) > 0) {
		$graph_draw_js = "";
		$graph_draw_js .= '<script type="text/javascript">';
		$graph_draw_js .= 'function draw_visualization(){';
		
		foreach ($graph_ids as $graph)
			$graph_draw_js .= 'draw_' . $graph . '();';
		
		$graph_draw_js .= '}';
		$graph_draw_js .= 'google.setOnLoadCallback(draw_visualization);';
		$graph_draw_js .= '</script>';
		
		//Add the graph drawing JS to the content of the post
		$content .= $graph_draw_js;
	}
	return $content;
}

//Add hook for front-end <head></head>
wp_register_script('jsapi', 'http://www.google.com/jsapi');
wp_enqueue_script('jsapi');

add_action('wp_head', 'visualization_load_js');

//Add the short codes for the charts
add_shortcode('line_chart', 'visualization_line_chart_shortcode');

//Add filter to edit the contents of the post
add_filter('the_content', 'visualization_load_graphs_js', 1000);
?>
