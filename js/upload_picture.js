function	upload_picture() {
	var canvas = document.getElementById('canvas');
	var ctx = canvas.getContext('2d');
    var reader = new FileReader();
    reader.onload = function(event){
        var img = new Image();
        img.onload = function(){
            canvas.width = img.width;
            canvas.height = img.height;
            ctx.drawImage(img, 0, 0);
        }
        img.src = event.target.result;
    }
    reader.readAsDataURL(fileToUpload.files[0]);

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

    document.getElementById('uploadbutton').addEventListener('click', function() {
	    var data = canvas.toDataURL('image/png');
	    photo.setAttribute('src', data);

	    var form = document.getElementById('form');
	    form.value = data;
	});
}