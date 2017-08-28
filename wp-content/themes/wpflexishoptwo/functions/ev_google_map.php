<?php 

function ev_google_map(){

	$lat_x = 14.559348;
	$lat_y = 121.03127;
	$title = 'The Grasse Fragrance Company';
	$marker_icon = 'http://grassefragrance.com/wordpress/wp-content/themes/wpflexishoptwo/images/map_marker.png';
	$marer_icon_shdow = 'http://grassefragrance.com/wordpress/wp-content/themes/wpflexishoptwo/images/map_marker_shadow.png';
	$str = "	<script>
					var map;
					function initialize() {
						var myLatlng = new google.maps.LatLng({$lat_x}, {$lat_y});
						var mapOptions = {
							zoom: 14,
							center: myLatlng,
							disableDefaultUI: true,
							zoomControl: true,
							mapTypeId: google.maps.MapTypeId.ROADMAP
						};
						
						map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
						  
						var marker = new google.maps.Marker({
								position: myLatlng,
								map: map,
								title: '{$title}',
								icon: '{$marker_icon}',
								shadow: {
								  url: '{$marer_icon_shdow}',
								  anchor: new google.maps.Point(34, 60)
								},							  
						});		  
					}

					google.maps.event.addDomListener(window, 'load', initialize);
				</script>";
	echo $str;
}

?>