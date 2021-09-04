
// choose.js

var map;
var geocoder;
var polygonObjects = [];
var redoArray = [];

// Create the script tag, set the appropriate attributes
var script = document.createElement('script');
script.src = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyClqTR4nli9shcBqpSbidT2JsKV6XRMlBY&callback=initMap&libraries=drawing';
script.async = true;

var pos;
navigator.permissions.query({ // Nivigator API
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
  };
  map = new google.maps.Map(document.getElementById("map"), ops);
  geocoder = new google.maps.Geocoder(); // Geocoder API
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
    }
  }
  http.send(dataPolygons);
}else{
  alert('No zones have been selected. Please select zones!');
}
}