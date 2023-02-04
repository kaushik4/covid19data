<?php
/**

 * Plugin Name: Covid 19 India
 * Plugin URI:  
 * Description: WordPress Covid 19 india data
 * Version:     1.0.0
 * Author:      concettolabs
 * Author URI:   
 * License:     GPLv3
 * Text Domain: covid19india
 * Domain Path: /languages
 * Requires at elast: 1.0
 * Tested up to: 6.0
 * Requires PHP: 5.6
 */

class covid19india{
	private $covid19india_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'covid19india_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'covid19india_page_init' ) );
	}

	public function covid19india_add_plugin_page() {
		add_menu_page(
			'covid19india', // page_title
			'covid19india', // menu_title
			'manage_options', // capability
			'covid19india', // menu_slug
			array( $this, 'covid19india_admin_page' ), // function
			'dashicons-chart-line', // icon_url
			2 // position
		);
	}

	public function covid19india_admin_page() {
		 date_default_timezone_set('Asia/kolkata'); 
		 $file = fopen("https://data.covid19india.org/csv/latest/case_time_series.csv", "r");  ?>

		<div class="wrap">
			<h2>Covid19 India Data</h2>
			<p>select date in drop down and show data in table as per selection</p>
			
			<?php settings_errors(); ?>

			<form method="post" action="<?php echo $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];?>" width="70">
				<select name="covid19india_date" class="select-box">
				
				 <?php 
			$arr_year_month = array();
			$cnt = 0;
			while (($data = fgetcsv($file)) !== false) {
			if($cnt!=0){
					 $data = array_unique($data);
          //  var_dump($data[1]);
          //  var_dump($data); die;
					 $year =  date('Y',strtotime($data[1]));
					 $month = date('m',strtotime($data[1]));
					 $monthName = date('F',strtotime($data[1]));
           $YearMonth = $year.'-'.$month ;
           if(!in_array($YearMonth, $arr_year_month)){
            $arr_year_month[] = $YearMonth;
            ?>
            <option value="<?php echo $YearMonth ?>"><?php echo $year.'-'.$monthName;?></option>
            <?php 
            }
          }
          $cnt++;
        	}	//fclose($data); ?>
            </select>
			 
			<input type="submit" value="submit" class="submit-btn">
				<?php
					///settings_fields( 'covid19india_option_group' );
					//do_settings_sections( 'covid19india-admin' );
					//submit_button();	
				?>
			</form>
			<center>
        <h1>DISPLAY DATA PRESENT IN CSV</h1>
        <h3>Covid19 India</h3>
  
        <?php
			if(isset($_POST['covid19india_date'])){
            
           

        echo "<html><body><center><table border='1'><tr>
        <th>Date</th>
        <th>Date_YMD</th>
        <th>Daily Confirmed</th>
        <th>Total Confirmed</th>
        <th>Daily Recovered</th>
        <th>Total Recovered</th>
        <th>Daily Deceased</th>
        <th>Total Deceased</th>
		</tr>\n\n";
		//FILE
		
		
        // Open a file
        $file = fopen("https://data.covid19india.org/csv/latest/case_time_series.csv", "r"); 
		$datafile = fgetcsv($file);
        // Fetching data from csv file row by row
        while (($data = fgetcsv($file)) !== false) {
             $year =  date('Y',strtotime($data[1]));
             $month = date('m',strtotime($data[1]));
             $YearMonth = $year.'-'.$month ;
            if ( $YearMonth == $_POST['covid19india_date']) {
				
					// HTML tag for placing in row format
					echo "<tr>";
						foreach ($data as $i) {
							
							echo "<td>" . htmlspecialchars($i)."</td>";
						}
					echo "</tr> \n";
					$csv_json[] = array_combine($datafile, $data);
			}
			
        // else{
        //     echo "No Data Found";
        // }
		}
			// Closing the file
			fclose($file);
			//array_walk($csv_json[0], function (& $item) {
			//$item['TotalConfirmed'] = $item['Total Confirmed'];
			//unset($item['Total Confirmed']);
			//});

			//var_dump($a);
			$json_data = json_encode($csv_json);
			//echo '<pre>';
			//print_r($json_data);
		}else{
				echo "<tr><td>No Data Found</td></tr>";
			}//end isset
        echo "\n</table></center></body></html>";
			
        ?>
		<script>
  
		let data1 = '<?php echo $json_data; ?>';
		let data2 = JSON.parse(data1);
		console.log(data2);
		let data_array = [];
		for(var i=0;i<data2.length;i++){
			let total ='Total Confirmed';
			console.log(data2[i].Date);
		}
		
		</script>
    </center>
		</div>
		<figure class="highcharts-figure">
			<div id="container"></div>	
		</figure>
	<?php }
//for store data if reuired and create code
	public function covid19india_page_init() {
		/* register_setting(
			'covid19india_option_group', // option_group
			'covid19india_option_name', // option_name
		);

		add_settings_section(
			'covid19india_setting_section', // id
			'Settings', // title
			array( $this, 'covid19india_section_info' ), // callback
			'covid19india-admin' // page
		); */

		
	}

	

	public function covid19india_section_info() {
		
	}

	

	

}
if ( is_admin() )
	$covid19india = new covid19india();

/* 
 * Retrieve this value with:
 * $covid19india_options = get_option( 'covid19india_option_name' ); // Array of All Options
 * $header_code_0 = $covid19india_options['header_code_0']; // header code
 * $footer_code_1 = $covid19india_options['footer_code_1']; // footer code
 */
function covid19india_header_code(){ ?>
  <script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
  <style>
    table, td, tr {
  font-size: 16px;
  background-color: #fff;
  padding: 10px;
  border-collapse: collapse;
}
th{
	font-weight:bold;padding:15px;
}
.select-box {
  width: 220px;
  margin: 10px;
  background-color: #fff;
  height: 40px;
}
.submit-btn {
  background-color: #000;
  border: 1px solid #000;
  font-size: 16px;
  color: #fff;
  padding: 10px 25px;border-radius: 5px;
text-transform: capitalize;
}.submit-btn:hover {
  background-color: #ccc;
  color: #000;
}
    } 
  </style>
<?php }
add_action('admin_head','covid19india_header_code');
function covid19india_footer_code(){ ?>

<!-- partial -->
  <script>
  
 
 
  
  Highcharts.chart('container', {

  title: {
    text: 'Covid India',
    align: 'left'
  },

  subtitle: {
    text: '',
    align: 'left'
  },

  yAxis: {
    title: {
      text: 'Confirm'
    }
  },

  xAxis: {
    accessibility: {
      rangeDescription: 'Date'
    }
  },

  legend: {
    layout: 'vertical',
    align: 'right',
    verticalAlign: 'middle'
  },

  plotOptions: {
    series: {
      label: {
        connectorAllowed: false
      },
      pointStart: 2020
    }
  },

  series: [{
    name: 'Daily Confirmed',
    //data: [out]
	  data: [43934, 48656, 65165, 81827, 112143, 142383,171533, 165174, 155157, 161454, 154610]
  }],

  responsive: {
    rules: [{
      condition: {
        maxWidth: 500
      },
      chartOptions: {
        legend: {
          layout: 'horizontal',
          align: 'center',
          verticalAlign: 'bottom'
        }
      }
    }]
  }

});
  </script>
  <?php
}
add_action('admin_footer','covid19india_footer_code');
