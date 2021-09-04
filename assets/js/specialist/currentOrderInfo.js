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
  if (typeof data_polygons !== 'undefined') {
    pos = {
      lat: data_polygons[0][0][0],
      lng: data_polygons[0][0][1]
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

  function set_polygons(polygon_array) {
    let latlng_array;
    let polygons = [];
    for (let i = 0; i < polygon_array.length; i++) {
      latlng_array = [];
      for (let j = 0; j < polygon_array[i].length; j++) {
        latlng_array.push(new google.maps.LatLng(polygon_array[i][j][0], polygon_array[i][j][1]));
      }
      polygons.push(new google.maps.Polygon({
        paths: new google.maps.MVCArray(latlng_array),
        strokeOpacity: 0
      }));
    }
    for (let i = 0; i < polygons.length; i++) {
      polygons[i].setMap(map);
    }
  }

  if (typeof data_polygons !== 'undefined') {
    set_polygons(data_polygons);
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

function close_map() {
  document.getElementById('main_content').classList.remove('d-none');
  document.getElementById('map_content').classList.add('d-none');
}

function show_map() {
  document.getElementById('map_content').classList.remove('d-none');
  document.getElementById('main_content').classList.add('d-none');
}

/*
// Function to upload file
function uploadFile() {
  // Reject if the file input is empty & throw alert
  if ($('#formFile').val() == "") {
    alert('no file');
    return;
  }
  $('.progress').removeClass('d-none');

  // Create a new FormData instance
  var fileData = new FormData();

  // Create a XMLHTTPRequest instance
  var request = new XMLHttpRequest();

  // Get a reference to the file
  var file = $('#formFile').prop('files')[0];

  // Get a reference to the filename

  fileData.append("file", file);

  // request progress handler
  request.upload.addEventListener("progress", progressHandler, false);

  // request load handler (transfer complete)
  request.addEventListener("load", loadHandler, false);

  // request error handler
  request.addEventListener("error", errorHandler, false);

  // request abort handler
  request.addEventListener("abort", abortHandler, false);

  // Open and send the request
  request.open("POST", "php/currentOrderInfo.inc.php");
  request.send(fileData);

  //cancel_btn.addEventListener("click", function () {
  //request.abort();
  //})

};

function progressHandler(e) {

  // Get the loaded amount and total filesize (bytes)
  var loaded = e.loaded;
  var total = e.total;

  // Calculate percent uploaded
  var percentComplete = Math.round((loaded / total) * 100);

  // Update the progress text and progress bar

  $('#fileProgressBar').css({
    'aria-valuenow': percentComplete,
    'width': percentComplete + '%'
  });
  $('#fileProgressBar').text(percentComplete + '%');
};

function loadHandler(e) {
  resetProgressBar();
  $('#formFile').addClass('d-none');
  //$('#uploadButton').addClass('d-none');
  $('.progress').addClass('d-none');
  //$('#cancelUploadButton').removeClass('d-none');
  $('.alert').removeClass('d-none');
  $('#file_form').submit();
};

function errorHandler(e) {
  resetProgressBar();
  alert('errorFileJs');
};

function abortHandler(e) {
  resetProgressBar();
  alert('canceled');
};

function resetProgressBar() {
  $('#fileProgressBar').css({
    'aria-valuenow': 0,
    'width': '0%'
  });
  $('#fileProgressBar').text('0%');
};

function cancelUpload() {
  $('#formFile').removeClass('d-none');
  //$('#uploadButton').removeClass('d-none');
  //$('#cancelUploadButton').addClass('d-none');
  $('.alert').addClass('d-none');
};*/

function cancelOrder(){
  if (confirm("Are you sure you want to cancel the order?")) {
    $('#cancelButton').click();
  }
}

//gets the state of the chat
function checkChat(message_num) {
  $.ajax({
    type: "POST",
    url: "php/currentOrderInfo.inc.php",
    data: {
      'function': 'checkChat',
      'message_num': message_num
    },
    success: function (data) {
      if (data !== "no_new_messages") {
        data = $.parseJSON(data);
        data[0].forEach(function (arr) {
          let newMessage;
          if (arr[1] === 'specialist'){
            arr[1] === 'me';
            newMessage = "<div class='specialist-message-div bg-primary float-end mb-3 d-inline-block rounded-3'><p class='px-1 text-info'>me " + 
            arr[2].toString() + "</p><p class='px-1 text-white'>" + arr[0].toString() + "</p></div>";
          }else{
            newMessage = "<div class='client-message-div bg-secondary mb-3 d-inline-block rounded-3'><p class='px-1 text-warning'>client " + 
            arr[2].toString() + "</p><p class='px-1 text-white'>" + arr[0].toString() + "</p></div>";
          }
          $('#chatArea').append(newMessage);
          message_number = data[1];
        })
      }
    }
  });
}

//send the message
function sendMessage() {
  let message = $('#message_input').val();
  $('#message_input').val('');
  if (message.trim() != ""){
  $.ajax({
    type: "POST",
    url: "php/currentOrderInfo.inc.php",
    data: {
      'function': 'updateChat',
      'message': message
    },
    success: function (data) {
      checkChat(message_number);
    }
  });
}
}