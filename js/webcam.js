(function() {

  var streaming = false,
      video        = document.querySelector('#video'),
      cover        = document.querySelector('#cover'),
      canvas       = document.querySelector('#canvas'),
      photo        = document.querySelector('#photo'),
      startbutton  = document.querySelector('#startbutton'),
      width = 300,
      height = 0;

      navigator.getMedia = ( navigator.getUserMedia ||
                             navigator.webkitGetUserMedia ||
                             navigator.mozGetUserMedia ||
                             navigator.msGetUserMedia);

      if (navigator.mozGetUserMedia) {
        navigator.mediaDevices.getUserMedia({ audio: false, video: true }).then(function(stream) {
        video.src = window.URL.createObjectURL(stream);
        video.onloadedmetadata = function(e) {
          video.play();
      };
    })
      .catch(function(err) {
        console.log(err.name + ": " + err.message);
    });
      } else {
        navigator.getMedia(
      {
      video: true,
      audio: false
    },
    function(stream) {
      if (navigator.getUserMedia) {
        video.mozSrcObject = stream;
      } else {
        var vendorURL = window.URL || window.webkitURL;
        video.src = vendorURL.createObjectURL(stream);
      }
      video.play();

    },
    function(err) {
      console.log("An error occured! " + err);
    });
      }
  video.addEventListener('canplay', function(ev){
    if (!streaming) {
      height = video.videoHeight / (video.videoWidth/width);
      video.setAttribute('width', width);
      video.setAttribute('height', height);
      canvas.setAttribute('width', width);
      canvas.setAttribute('height', height);
      streaming = true;
    }
  }, false);

  function  takepicture() {
      var c = document.getElementById('canvas');
      c.width = width;
      c.height = height;
      var context = c.getContext('2d');
      context.drawImage(video, 0, 0, width, height);

      var imageObj1 = new Image();
      var imageObj2 = new Image();
      var frame1 = document.getElementById('frame1');
      var frame2 = document.getElementById('frame2');
      var frame3 = document.getElementById('frame3');

      var x = 0;
      var y = 0;

      imageObj1.src = 'img/ini/42.png';
      imageObj1.onload = function() {
         context.drawImage(video, 0, 0, width, height);
         if (frame1.checked) {
          imageObj2.src = frame1.value;
          x = 116;
          y = 6;
         }
         else if (frame2.checked) {
          imageObj2.src = frame2.value;
          x = 116;
          y = 66;
         }
          else if (frame3.checked) {
          imageObj2.src = frame3.value;
          x = 116;
          y = 106;
         }
         else {
          frame.value = '';
         }
          imageObj2.onload = function() {
            context.drawImage(imageObj2, x, y, 100, 100);
            var img = c.toDataURL('image/png');
         }
      };
      var data = c.toDataURL('image/png');
      photo.setAttribute('src', data);
      
      var form = document.getElementById('form');
      form.value = data;
  }

     radio = document.getElementById('radio_selection').addEventListener('click', function() {
      function  checked_radio(frame1, frame2, frame3) {
        var result = null;

        if (frame1.checked) {
          result = 'hat';
        } else if (frame2.checked) {
          result = 'glasses';
        } else if (frame3.checked) {
          result = 'mustache'
        }
        return (result);
      }

      var v = document.getElementById('montage_frame');
      v.diplay = 'auto';
      var result = checked_radio(document.getElementById('frame1'), document.getElementById('frame2'), document.getElementById('frame3'));
      
      if (result == 'hat') {
        v.src = '/img/icons/hat5.png';
        v.className = "frame_hat";
      } else if (result == 'glasses') {
        v.src = '/img/icons/glasses5.png';
         v.className = "frame_glasses";
      } else if (result == 'mustache') {
        v.src = '/img/icons/mustache5.png';
        v.className = "frame_mustache";
      } else {
        v.src = null;
      }
    });

  startbutton.addEventListener('click', function(ev){
      takepicture();
    ev.preventDefault();
  }, false);

})();