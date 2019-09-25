<?php
    
require_once './model/ChromeOperator.php';
require_once './vendor/autoload.php';

// YouTubeから動画データーを取得する
function getYtItems( $pageToken ) {
	global $ytApiKey;
	global $playlistId;
	global $maxResults;
	
	$request = "https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&type=video&order=date&playlistId=$playlistId&maxResults=$maxResults&key=$ytApiKey";
	if ( $pageToken ) $request .= '&pageToken=' . $pageToken;
	$data = json_decode( file_get_contents( $request ) );
	
	return $data;
}

// APIキー
$ytApiKey = 'AIzaSyA2PftOIyPp6Isy09emsnNExx5EOum9ZWc';

// ユーザー名
$userName = 'UC67Wr_9pA4I0glIxDt_Cpyw';
$userName = 'UCeuiwRvd4UBoQXn1Ua54u1g';

// 取得最大数
$latest = isset( $_REQUEST['latest'] );
$maxResults = $latest ? 10 : 50;

// ユーザーのプレイリストIDを取得
$request = "https://www.googleapis.com/youtube/v3/channels?part=contentDetails&id=UCeuiwRvd4UBoQXn1Ua54u1g&key=AIzaSyA2PftOIyPp6Isy09emsnNExx5EOum9ZWc";
//$request = "https://www.googleapis.com/youtube/v3/channels?part=contentDetails&forUsername=$userName&key=$ytApiKey";
var_dump($request);
$data = json_decode( file_get_contents( $request ) );
$playlistId = $data->{'items'}[0]->{'contentDetails'}->{'relatedPlaylists'}->{'uploads'};

// ユーザーの動画リストを取得
$items = [];
$pageToken = NULL;
while ( $pageToken !== '' ) {
	$data = getYtItems( $pageToken );
	$pageToken = isset( $data->{'nextPageToken'} ) ? $data->{'nextPageToken'} : '';
	$items = array_merge( $items, $data->{'items'} );	
	if ( $latest ) break;
}

$videosData = [];
foreach ( $items as $item ) {
	$snippet = $item->{'snippet'};
	$videosData[] = [
		'title'       => $snippet->{'title'},
		'description' => $snippet->{'description'},
		'publishedAt' => strtotime( $snippet->{'publishedAt'} ),
		'thumbnails'  => $snippet->{'thumbnails'},
		'videoId'     => $snippet->{'resourceId'}->{'videoId'},
	];
}



//$start_url = 'https://www.e-reserve.jp/eap-rj/rsv_rj/Core_i/init.asp?KLCD=212019&SBT=1&Target=_Top&LCD=';
//$chromeOperator = new ChromeOperator('', 'https://downsub.com/', '');
//$court_condition_resercher = new FacilityConditionResercher('http://localhost:4444/wd/hub',$start_url,$frame_name, new DateTime('2019-09-28'), '境川緑道公園テニスコート');
    
//$chromeOperator = null;