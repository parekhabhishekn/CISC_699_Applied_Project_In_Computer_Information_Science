<?php
namespace App\Http\Controllers;

// require '../../../../vendor/autoload.php'; 

use Illuminate\Http\Request; 
use App\User; 
use App\Integration; 
use Aws\CloudWatch\CloudWatchClient; 
use Aws\CloudWatchEvents\CloudWatchEventsClient; 
use Aws\CloudWatchLogs\CloudWatchLogsClient; 
use Aws\Exception\AwsException; 
use Aws\Lambda\LambdaClient; 
use Aws\S3\S3Client; 
use Aws\S3\Exception\S3Exceptions; 

class MonitorController extends Controller
{

	protected $redirectTo = 'monitor'; 
    protected $aws_key = ''; 
    protected $aws_secret = '';  

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $cloudWatchClient = new CloudWatchClient([
            'version'     => 'latest',
            'region'      => 'us-east-2',
            'credentials' => [
                'key'    => '',
                'secret' => '',
            ],
        ]); 
        try {
            $result_nt_in = $cloudWatchClient->getMetricWidgetImage([
                'MetricWidget' => '{
                    "region": "us-east-1",
                    "metrics": [
                        [ "AWS/EC2", "NetworkPacketsIn", "InstanceId", "", { "stat": "Average" } ]
                    ],
                    "title": "Incoming Traffic",
                    "copilot": true,
                    "legend": {
                        "position": "bottom"
                    },
                    "view": "timeSeries",
                    "width":360,
                    "height":300,
                    "period":300,
                    "start":"-PT5M",
                    "end":"now",
                    "stacked":false,
                    "yAxis": {
                        "left": {
                            "showUnits": true
                        }
                    },  
                    "stacked": true, 
                    "liveData": true
                    }']
            ); 
            $result_nt_out = $cloudWatchClient->getMetricWidgetImage([
                'MetricWidget' => '{
                    "region": "us-east-1",
                    "metrics": [
                        [ "AWS/EC2", "NetworkPacketsOut", "InstanceId", "", { "stat": "Average" } ]
                    ],
                    "title": "Outgoing Traffic",
                    "copilot": true,
                    "legend": {
                        "position": "bottom"
                    },
                    "view": "timeSeries",
                    "width":360,
                    "height":300,
                    "period":300,
                      "start":"-PT5M",
                      "end":"now",
                      "stacked":false,
                      "yAxis": {
                        "left": {
                            "showUnits": true
                        }
                    }, 
                    "stacked": true, 
                    "liveData": true
                    }']
            );  
            $result_cpu = $cloudWatchClient->getMetricWidgetImage([
                'MetricWidget' => '{
                    "region": "us-east-1",
                    "metrics": [ 
                        [ "AWS/EC2", "CPUUtilization", "InstanceId", "", { "stat": "Average" } ]
                    ],
                    "title": "CPU Load",
                    "copilot": true,
                    "legend": {
                        "position": "bottom"
                    },
                    "view": "timeSeries",
                    "width":360,
                    "height":300,
                    "period":300,
                      "start":"-PT5M",
                      "end":"now",
                      "stacked":false,
                      "yAxis": {
                        "left": {
                            "showUnits": true
                        }
                    }, 
                    "stacked": true, 
                    "liveData": true
                    }']
            ); 
            //echo get_class($result); 
            $image_nt_in = $result_nt_in->get('MetricWidgetImage'); 
            $image_nt_out = $result_nt_out->get('MetricWidgetImage');
            $image_cpu = $result_cpu->get('MetricWidgetImage');
            $metadata = $result_cpu->get('@metadata'); 
            $message = ''; 
            //echo '<img src="data:image/gif;base64,'.base64_encode($image).'" />';  
            $message .= 'For the effective URI at ' . 
            $metadata['effectiveUri'] . ":<br /><br />";
            $message .= "<br /><table><thead><th colspan='2'>Server Status</th></thead><tbody>";
            
            $message .= '<tr><td><img src="data:image/gif;base64,'.base64_encode($image_nt_in).'" /></td><td><img src="data:image/gif;base64,'.base64_encode($image_nt_out).'" /></td></tr>';  
            $message .= '<tr><td><img src="data:image/gif;base64,'.base64_encode($image_cpu).'" /></td></tr>';   
            return view('monitor/monitor')->with('data', $message);   
        } catch (AwsException $e) {
            return 'Error: ' . $e->getAwsErrorMessage();
        } 
        //return $result; 
        //return view('monitor/monitor')->with('data',"Some data");
    } 

    public function getCloudEvents() { 

        return null; 
    } 

    public function getAllIntegrations() {

    }  

}
