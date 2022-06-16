/* global document */
var loader_html	= '';
jQuery(document).ready(function () {
    "use strict";
	var dir_latitude	 	= scripts_vars.dir_latitude;
	var dir_longitude		= scripts_vars.dir_longitude;
	var dir_map_type	 	= scripts_vars.dir_map_type;
	var dir_close_marker	= scripts_vars.dir_close_marker;
	var dir_cluster_marker	= scripts_vars.dir_cluster_marker;
	var dir_map_marker		= scripts_vars.dir_map_marker;
	var dir_cluster_color	= scripts_vars.dir_cluster_color;
	var dir_zoom			= scripts_vars.dir_zoom;
	var dir_map_scroll		= scripts_vars.dir_map_scroll;
	
	loader_html	= '<div class="provider-site-wrap"><div class="provider-loader"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div>';

});

//Init Map Scripts
function workreap_init_map_script( _data_list ){
	var dir_latitude	 = scripts_vars.dir_latitude;
	var dir_longitude	 = scripts_vars.dir_longitude;
	var dir_map_type	 = scripts_vars.dir_map_type;
	var dir_close_marker		  = scripts_vars.dir_close_marker;
	var dir_cluster_marker		  = scripts_vars.dir_cluster_marker;
	var dir_map_marker			  = scripts_vars.dir_map_marker;
	var dir_cluster_color		  = scripts_vars.dir_cluster_color;
	var dir_zoom				  = scripts_vars.dir_zoom;
	var dir_map_scroll			  = scripts_vars.dir_map_scroll;
	var gmap_norecod			  = scripts_vars.gmap_norecod;
	var map_styles			      = scripts_vars.map_styles;


	if( _data_list.status == 'found' ){
		var response_data	= _data_list.project_list;
		if( _data_list.lat && _data_list.long ){
			var dir_latitude    = _data_list.lat;
			var dir_longitude	= _data_list.long;
		}
	} else{
		if( _data_list.lat && _data_list.long ){
			var dir_latitude    = _data_list.lat;
			var dir_longitude	= _data_list.long;
		} else{
			var dir_latitude    = dir_latitude;
			var dir_longitude	= dir_longitude;
		}
	}
	
	var location_center = new google.maps.LatLng(dir_latitude,dir_longitude);
	
	if(dir_map_type == 'ROADMAP'){
		var map_id = google.maps.MapTypeId.ROADMAP;
	} else if(dir_map_type == 'SATELLITE'){
		var map_id = google.maps.MapTypeId.SATELLITE;
	} else if(dir_map_type == 'HYBRID'){
		var map_id = google.maps.MapTypeId.HYBRID;
	} else if(dir_map_type == 'TERRAIN'){
		var map_id = google.maps.MapTypeId.TERRAIN;
	} else {
		var map_id = google.maps.MapTypeId.ROADMAP;
	}
	
	var scrollwheel	   = true;
	var lock		   = 'lock';
	
	if( dir_map_scroll == 'false' ){
		scrollwheel		= false;
		lock		    = 'unlock';
	}

	var mapOptions = {
		center: location_center,
		zoom: parseInt(dir_zoom),
		mapTypeId: map_id,
		gestureHandling: 'cooperative',
		scaleControl: true,
		scrollwheel: true,
		disableDefaultUI: false,
		draggable:scrollwheel,
		fullscreenControl:true
	}
	
	var map = new google.maps.Map(document.getElementById("sp-search-map"), mapOptions);
	
	var styles = workreap_get_map_styles(map_styles);
	
	if(styles != ''){
		var styledMap = new google.maps.StyledMapType(styles, {name: 'Styled Map'});
		map.mapTypes.set('map_style', styledMap);
		map.setMapTypeId('map_style');
	}
		
	var bounds = new google.maps.LatLngBounds();

	jQuery(document).on("click",'.wt-sidebarvtwo .wt-btnmapview', function(e){
		jQuery('#sp-search-map div.gm-style button[title="Toggle fullscreen view"]').trigger('click');
    });
	
	//Lock Map
	if( document.getElementById('doc-lock') ){ 
		google.maps.event.addDomListener(document.getElementById('doc-lock'), 'click', function () {
			if(lock == 'lock'){
				map.setOptions({ 
						scrollwheel: false,
						draggable: false 
					}
				);
				
				jQuery("#doc-lock").html('<i class="fa fa-lock" aria-hidden="true"></i>');
				lock = 'unlock';
			}else if(lock == 'unlock'){
				map.setOptions({ 
						scrollwheel: false,
						draggable: true 
					}
				);
				
				jQuery("#doc-lock").html('<i class="fa fa-unlock" aria-hidden="true"></i>');
				lock = 'lock';
			}
		});
	}
	
	if( _data_list.status == 'found' && typeof(response_data) != "undefined" && response_data !== null ){
		jQuery('#gmap-noresult').html('').hide(); //Hide No Result Div
		var markers = new Array();
		var info_windows = new Array();
		var clusterMarker = [];

		var spiderConfig = {
			 markersWontMove: true, 
			 markersWontHide: true, 
			 keepSpiderfied: true, 
			 circleSpiralSwitchover: 40 
		};
		
		// Create OverlappingMarkerSpiderfier instsance
		var markerSpiderfier = new OverlappingMarkerSpiderfier(map, spiderConfig);
		map.active_window = false;
		for (var i=0; i < response_data.length; i++) {
			
			markers[i] = new google.maps.Marker({
				position: new google.maps.LatLng(response_data[i].latitude,response_data[i].longitude),
				map: map,
				icon: response_data[i].icon,
				title: response_data[i].project_name,
				animation: google.maps.Animation.DROP,
				visible: true
			});
			
			bounds.extend(markers[i].getPosition());
			var boxText = document.createElement("div");
			boxText.className = 'directory-detail';
			var innerHTML = "";
			boxText.innerHTML += response_data[i].html.content;
			
			var myOptions = {
				content: boxText,
				disableAutoPan: true,
				maxWidth: 0,
				alignBottom: true,
				pixelOffset: new google.maps.Size( -125, -65 ),
				zIndex: null,
				closeBoxMargin: "0 0 -16px -16px",
				closeBoxURL: dir_close_marker,
				infoBoxClearance: new google.maps.Size( 1, 1 ),
				isHidden: false,
				pane: "floatPane",
				enableEventPropagation: false
			};
		
			var ib = new InfoBox( myOptions );
			attachInfoBoxToMarker( map, markers[i], ib );
			markerSpiderfier.addMarker(markers[i]);  // adds the marker to the spiderfier
		}
		
		var markerClustererOptions = {
			ignoreHidden: true,
			maxZoom: 15,
			styles: [{
				textColor: scripts_vars.dir_cluster_color,
				url: scripts_vars.dir_cluster_marker,
				height: 48,
				width: 48
			}]
		};
		
		// Create cluster	
		new MarkerClusterer(map, markers, markerClustererOptions);
		
	} else{
		jQuery('#gmap-noresult').html(gmap_norecod).show();
	}
}

function workreap_init_map_single_page_script(latitude,longitude){
	var dir_map_type	 = scripts_vars.dir_map_type;
	var dir_map_marker			  = scripts_vars.dir_map_marker;
	var dir_cluster_color		  = scripts_vars.dir_cluster_color;
	var dir_zoom				  = scripts_vars.dir_zoom;
	var dir_map_scroll			  = scripts_vars.dir_map_scroll;
	var gmap_norecod			  = scripts_vars.gmap_norecod;
	var map_styles			      = scripts_vars.map_styles;

	var dir_latitude    = latitude;
	var dir_longitude	= longitude;

	var location_center = new google.maps.LatLng(dir_latitude,dir_longitude);

	if(dir_map_type == 'ROADMAP'){
		var map_id = google.maps.MapTypeId.ROADMAP;
	} else if(dir_map_type == 'SATELLITE'){
		var map_id = google.maps.MapTypeId.SATELLITE;
	} else if(dir_map_type == 'HYBRID'){
		var map_id = google.maps.MapTypeId.HYBRID;
	} else if(dir_map_type == 'TERRAIN'){
		var map_id = google.maps.MapTypeId.TERRAIN;
	} else {
		var map_id = google.maps.MapTypeId.ROADMAP;
	}

	var scrollwheel	   = true;
	var lock		   = 'lock';

	var mapOptions = {
		center: location_center,
		zoom: parseInt(dir_zoom),
		mapTypeId: map_id,
		gestureHandling: 'cooperative',
		scaleControl: true,
		scrollwheel: true,
		disableDefaultUI: false,
		draggable:scrollwheel,
		fullscreenControl:true
	}

	var map = new google.maps.Map(document.getElementById("wt-map-pin"), mapOptions);
	var styles = workreap_get_map_styles(map_styles);
	
	if(styles != ''){
		var styledMap = new google.maps.StyledMapType(styles, {name: 'Styled Map'});
		map.mapTypes.set('map_style', styledMap);
		map.setMapTypeId('map_style');
	}
	
	//set marker
	const marker = new google.maps.Marker({
	  position: location_center,
	  map: map,
	});
}
//Assign Info window to marker
function attachInfoBoxToMarker( map, marker, infoBox ){
	google.maps.event.addListener( marker, 'spider_click', function(){
		var scale = Math.pow( 2, map.getZoom() );
		var offsety = ( (100/scale) || 0 );
		var projection = map.getProjection();
		var markerPosition = marker.getPosition();
		var markerScreenPosition = projection.fromLatLngToPoint( markerPosition );
		var pointHalfScreenAbove = new google.maps.Point( markerScreenPosition.x, markerScreenPosition.y - offsety );
		var aboveMarkerLatLng = projection.fromPointToLatLng( pointHalfScreenAbove );
		
		// close it
		if (map.active_window != false) {
		  map.active_window.infoBox.close(map,map.active_window.marker);
		}
		
		infoBox.open(map,marker);
		// then add the active window to this prop
		map.active_window = {infoBox: infoBox , marker: marker};
		map.setCenter( aboveMarkerLatLng );
		//infoBox.open( map, marker );
	});
}

//Set Spiderfy Markers
function MapSpiderfyMarkers(markers){
    for (var i = 0; i < markers.length; i++) {
        if(typeof oms !== 'undefined'){
           oms.addMarker(markers[i]);
        }
    }
}