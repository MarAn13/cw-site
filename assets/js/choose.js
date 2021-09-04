var map;
var geocoder;
var polygonObjects = [];
var redoArray = [];

// Create the script tag, set the appropriate attributes
var script = document.createElement('script');
script.src = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyClqTR4nli9shcBqpSbidT2JsKV6XRMlBY&callback=initMap&libraries=drawing';
script.async = true;

var pos;
navigator.permissions.query({
  name: 'geolocation'
}).then(function (result) {
  if (result.state == 'granted') {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        (position) => {
          pos = {
            lat: position.coords.latitude,
            lng: position.coords.longitude
          };
        },
      );
    } else {
      // Browser doesn't support Geolocation
      pos = {
        lat: 56.317061,
        lng: 43.999514
      };
    }
  } else if (result.state == 'prompt') {
    pos = {
      lat: 56.317061,
      lng: 43.999514
    };
  } else {
    pos = {
      lat: 56.317061,
      lng: 43.999514
    };
  }
});

// Attach your callback function to the `window` object
window.initMap = function () {
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
  geocoder = new google.maps.Geocoder();
  document.getElementById("search").addEventListener("click", () => {
    geocodeAddress(geocoder, map);
  });
  const drawingManager = new google.maps.drawing.DrawingManager({
    drawingMode: null,
    drawingControl: true,
    drawingControlOptions: {
      position: google.maps.ControlPosition.TOP_CENTER,
      drawingModes: [
        google.maps.drawing.OverlayType.POLYGON
      ],
    },
    polygonOptions: {
      draggable: false,
      editable: false,
      strokeOpacity: 0
    }
  });
  drawingManager.setMap(map);
  google.maps.event.addListener(drawingManager, "polygoncomplete", function (event) {
    addPolygon(event);
  });
};
// Append the 'script' element to 'head'
document.head.appendChild(script);

function addPolygon(polObj) {
  /*for (let i = 0; i < polygons.length; i++) {
      for (let j = 0; j < polygons[i].length; j++) {
          if (j + 1 != polygons[i].length) {
              intersect(polygons[i][j][0], polygons[i][j][1], polygons[i][j + 1][0], polygons[i][j + 1][1]);
          } else {
              intersect(polygons[i][j][0], polygons[i][j][1], polygons[i][0][0], polygons[i][j + 1][1]);
          }
      }
  }*/
  polygonObjects.push(polObj);
  redoArray = [];
  let undoButton = document.getElementById('undoButton');
  undoButton.classList.remove("text-secondary");
  undoButton.classList.add("text-white");
  let redoButton = document.getElementById('redoButton');
  redoButton.classList.remove("text-white");
  redoButton.classList.add("text-secondary");
};

function clearMap() {
  for (let i = 0; i < polygonObjects.length; i++) {
    polygonObjects[i].setMap(null);
  }
  polygonObjects = [];
  redoArray = [];
  let undoButton = document.getElementById('undoButton');
  let redoButton = document.getElementById('redoButton');
  undoButton.classList.remove("text-white");
  undoButton.classList.add("text-secondary");
  redoButton.classList.remove("text-white");
  redoButton.classList.add("text-secondary");
};

function undoDraw() {
  let undoPolygon = polygonObjects.pop();
  undoPolygon.setMap(null);
  redoArray.push(undoPolygon);
  let redoButton = document.getElementById('redoButton');
  redoButton.classList.remove("text-secondary");
  redoButton.classList.add("text-white");
  if (polygonObjects.length == 0) {
    let undoButton = document.getElementById('undoButton');
    undoButton.classList.remove("text-white");
    undoButton.classList.add("text-secondary");
  }
};

function redoDraw() {
  let redoPolygon = redoArray.pop();
  redoPolygon.setMap(map);
  polygonObjects.push(redoPolygon);
  let undoButton = document.getElementById('undoButton');
  undoButton.classList.remove("text-secondary");
  undoButton.classList.add("text-white");
  if (redoArray.length == 0) {
    let redoButton = document.getElementById('redoButton');
    redoButton.classList.remove("text-white");
    redoButton.classList.add("text-secondary");
  }
};

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

function geocodeAddress(geocoder, resultsMap) {
  let address = document.getElementById("address").value;
  geocoder.geocode({
    address: address
  }, (results, status) => {
    if (status === "OK") {
      resultsMap.setCenter(results[0].geometry.location);
    } else {
      alert("Geocode was not successful for the following reason: " + status);
    }
  });
}

function polygon_to_coords(polygonObjects){
  let polygons = [];
  for (let i = 0; i < polygonObjects.length; i++){
  let polygonArray = polygonObjects[i].getPath().getArray();
    let polygonCoords = []
    for (let i = 0; i < polygonArray.length; i++) {
      polygonCoords.push([polygonArray[i].lat(), polygonArray[i].lng()]);
    }
    polygons.push(polygonCoords);
  }
  return polygons;
}

function choosePlaceOfOperation() {
  if (polygonObjects.length != 0){
  let http = new XMLHttpRequest();
  let url = "php/choose.inc.php";
  let dataPolygons = polygon_to_coords(polygonObjects);
  dataPolygons = JSON.stringify(dataPolygons);
  http.open("POST", url, true);

  //Send the proper header information along with the request
  http.setRequestHeader("Content-Type","application/json")

  // Call a function when the state changes.
  http.onreadystatechange = function () {
    if (http.readyState == 4 && http.status == 200) {
      let response = http.responseText;
      if (response == "error"){
        alert("Error. Please choose again!");
        clearMap();
      }else if(response == "success"){
        window.location.href = "specialist/orderDesk.php";
      }else if(response == "client_success"){
        window.location.href = "client/php/order.inc.php";
      }
      console.log(response);
    }
  }
  http.send(dataPolygons);
}else{
  alert('No zones have been selected. Please select zones!');
}
}