var bodyHeight = $('body').height();
var headerHeight = $('header').height();
var footerHeight = $('footer').height();
$('.mainContentDiv').css('min-height', bodyHeight - headerHeight - footerHeight);
$('.mainContentBgDiv').css('height', $('.mainContentDiv').height());

var map;

// Create the script tag, set the appropriate attributes
var script = document.createElement('script');
script.src = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyClqTR4nli9shcBqpSbidT2JsKV6XRMlBY&callback=initMap';
script.async = true;

// Attach your callback function to the `window` object
window.initMap = function () {
  let pos;
  if (typeof client_data_polygons !== 'undefined' && typeof specialist_data_polygons !== 'undefined') {
    pos = {
      lat: client_data_polygons[0][0][0],
      lng: client_data_polygons[0][0][1]
    };
  } else {
    pos = {
      lat: 56.317061,
      lng: 43.999514
    };
  }
  let ops = {
    center: pos,
    zoom: 14,
    minZoom: 10,
    maxZoom: 20
    /*styles: [
        { elementType: "geometry", stylers: [{ color: "#242f3e" }] },
        { elementType: "labels.text.stroke", stylers: [{ color: "#242f3e" }] },
        { elementType: "labels.text.fill", stylers: [{ color: "#746855" }] },
        {
          featureType: "administrative.locality",
          elementType: "labels.text.fill",
          stylers: [{ color: "#d59563" }],
        },
        {
          featureType: "poi",
          elementType: "labels.text.fill",
          stylers: [{ color: "#d59563" }],
        },
        {
          featureType: "poi.park",
          elementType: "geometry",
          stylers: [{ color: "#263c3f" }],
        },
        {
          featureType: "poi.park",
          elementType: "labels.text.fill",
          stylers: [{ color: "#6b9a76" }],
        },
        {
          featureType: "road",
          elementType: "geometry",
          stylers: [{ color: "#38414e" }],
        },
        {
          featureType: "road",
          elementType: "geometry.stroke",
          stylers: [{ color: "#212a37" }],
        },
        {
          featureType: "road",
          elementType: "labels.text.fill",
          stylers: [{ color: "#9ca5b3" }],
        },
        {
          featureType: "road.highway",
          elementType: "geometry",
          stylers: [{ color: "#746855" }],
        },
        {
          featureType: "road.highway",
          elementType: "geometry.stroke",
          stylers: [{ color: "#1f2835" }],
        },
        {
          featureType: "road.highway",
          elementType: "labels.text.fill",
          stylers: [{ color: "#f3d19c" }],
        },
        {
          featureType: "transit",
          elementType: "geometry",
          stylers: [{ color: "#2f3948" }],
        },
        {
          featureType: "transit.station",
          elementType: "labels.text.fill",
          stylers: [{ color: "#d59563" }],
        },
        {
          featureType: "water",
          elementType: "geometry",
          stylers: [{ color: "#17263c" }],
        },
        {
          featureType: "water",
          elementType: "labels.text.fill",
          stylers: [{ color: "#515c6d" }],
        },
        {
          featureType: "water",
          elementType: "labels.text.stroke",
          stylers: [{ color: "#17263c" }],
        }
      ]*/
  };
  map = new google.maps.Map(document.getElementById("map"), ops);

  /*const iconBase = "https://maps.google.com/mapfiles/kml/shapes/";
  const icons = {
      parking: {
          name: "Parking",
          icon: iconBase + "red_colour.png",
      },
      library: {
          name: "Library",
          icon: iconBase + "library_maps.png",
      },
      info: {
          name: "Info",
          icon: iconBase + "info-i_maps.png",
      },
  };
  const legend = document.getElementById("legend");

  for (const key in icons) {
      const type = icons[key];
      const name = type.name;
      const icon = type.icon;
      const div = document.createElement("div");
      div.innerHTML = '<img src="' + icon + '"> ' + name;
      legend.appendChild(div);
  }
  map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(legend);
  */

  function set_polygons(polygon_array, color) {
    let latlng_array;
    let polygons = [];
    for (let i = 0; i < polygon_array.length; i++) {
      latlng_array = [];
      for (let j = 0; j < polygon_array[i].length; j++) {
        latlng_array.push(new google.maps.LatLng(polygon_array[i][j][0], polygon_array[i][j][1]));
      }
      polygons.push(new google.maps.Polygon({
        paths: new google.maps.MVCArray(latlng_array),
        strokeOpacity: 0,
        fillColor: color,
        fillOpacity: 0.3
      }));
    }
    for (let i = 0; i < polygons.length; i++) {
      polygons[i].setMap(map);
    }
    return polygons;
  }

  if (typeof client_data_polygons !== 'undefined' && typeof specialist_data_polygons !== 'undefined') {
    let client_polygon_color = "blue";
    let specialist_polygon_color = "red";
    set_polygons(client_data_polygons, client_polygon_color);
    set_polygons(specialist_data_polygons, specialist_polygon_color);
  }
};
// Append the 'script' element to 'head'
document.head.appendChild(script);

/*function intersect(x1, y1, x2, y2, x3, y3, x4, y4) {
    var a1, a2, b1, b2, c1, c2;
    var r1, r2, r3, r4;

    // Compute a1, b1, c1, where line joining points 1 and 2
    // is "a1 x + b1 y + c1 = 0".
    a1 = y2 - y1;
    b1 = x1 - x2;
    c1 = (x2 * y1) - (x1 * y2);

    // Compute r3 and r4.
    r3 = ((a1 * x3) + (b1 * y3) + c1);
    r4 = ((a1 * x4) + (b1 * y4) + c1);

    // Check signs of r3 and r4. If both point 3 and point 4 lie on
    // same side of line 1, the line segments do not intersect.
    if ((r3 !== 0) && (r4 !== 0) && sameSign(r3, r4)) {
        return 0; //return that they do not intersect
    }

    // Compute a2, b2, c2
    a2 = y4 - y3;
    b2 = x3 - x4;
    c2 = (x4 * y3) - (x3 * y4);

    // Compute r1 and r2
    r1 = (a2 * x1) + (b2 * y1) + c2;
    r2 = (a2 * x2) + (b2 * y2) + c2;

    // Check signs of r1 and r2. If both point 1 and point 2 lie
    // on same side of second line segment, the line segments do
    // not intersect.
    if ((r1 !== 0) && (r2 !== 0) && (sameSign(r1, r2))) {
        return 0; //return that they do not intersect
    }

    // lines_intersect
    return 1; //lines intersect, return true
}
*/