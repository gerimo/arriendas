<!DOCTYPE HTML>
<html>
<head>
 <link rel="stylesheet" type="text/css" href="http://s3-sa-east-1.amazonaws.com/arriendas.assets/css/uploader/styles.css" />
 <script>
    
    function createCORSRequest(method, url) 
{
  var xhr = new XMLHttpRequest();
  if ("withCredentials" in xhr) 
  {
    xhr.open(method, url, true);
  } 
  else if (typeof XDomainRequest != "undefined") 
  {
    xhr = new XDomainRequest();
    xhr.open(method, url);
  } 
  else
  {
    xhr = null;
  }
  return xhr;
}
 
function handleFileSelect(evt) 
{

    var ctx = document.getElementById('myCanvas').getContext('2d'),
        reader = new FileReader;
    reader.onload = function(event) {
        var img = new Image;

        img.onload = function() {
            ctx.drawImage(img, 0,0,140,140);
           
        };

        img.src = event.target.result;
    };

    reader.readAsDataURL(evt.target.files[0]);
    document.getElementById("foto").style.display="none";
    document.getElementById("fotoMarco_Imagen").style.display="none";
    document.getElementById("myCanvas").style.display="block";
    document.getElementById("fotoMarco_Canvas").style.display="block";
        
  setProgress(0, 'Cargando Archivo');
 
  var files = evt.target.files; 
 
  var output = [];
  for (var i = 0, f; f = files[i]; i++) 
  {

   uploadFile(f);
  }
}
 
/**
 * Execute the given callback with the signed response.
 */
function executeOnSignedUrl(file, callback)
{
  //Mostramos la div con la barra de progreso
  document.getElementById("barra_progreso_widget").style.visibility="visible";
    
  var xhr = new XMLHttpRequest();
  xhr.open('GET', '<?php echo url_for("main/signput") ?>?name=' + file.name + '&type=' + file.type, true);
 
  // Hack to pass bytes through unprocessed.
  xhr.overrideMimeType('text/plain; charset=x-user-defined');
 
  xhr.onreadystatechange = function(e) 
  {
    if (this.readyState == 4 && this.status == 200) 
    {
      callback(decodeURIComponent(this.responseText));
    }
    else if(this.readyState == 4 && this.status != 200)
    {
      setProgress(0, 'Could not contact signing script. Status = ' + this.status);
    }
  };
 
  xhr.send();
}
 
function uploadFile(file)
{
  executeOnSignedUrl(file, function(signedURL) 
  {
    uploadToS3(file, signedURL);
  });
}
 
/**
 * Use a CORS call to upload the given file to S3. Assumes the url
 * parameter has been signed and is accessable for upload.
 */
function uploadToS3(file, url)
{
  var xhr = createCORSRequest('PUT', url);
  if (!xhr) 
  {
    setProgress(0, 'CORS not supported');
  }
  else
  {
    xhr.onload = function() 
    {
      if(xhr.status == 200)
      {
        setProgress(100, 'Archivo Cargado');
        //Quitamos la barra de progreso
        document.getElementById("barra_progreso_widget").style.visibility="hidden";
        //Llamamos al callback para informar a la p‡gina madre que el archivo est‡ cargado
        window.parent.uploaderReady('<?php echo $idElemento; ?>',url.split("?")[0]);
      }
      else
      {
        setProgress(0, 'Upload error: ' + xhr.status);
      }
    };
 
    xhr.onerror = function() 
    {
     console.log(xhr.statusText);
      setProgress(0, 'XHR error.');
    };
 
    xhr.upload.onprogress = function(e) 
    {
      if (e.lengthComputable) 
      {
        var percentLoaded = Math.round((e.loaded / e.total) * 100);
        setProgress(percentLoaded, percentLoaded == 100 ? 'Finalizado.' : 'Subiendo.');
      }
    };
 
    xhr.setRequestHeader('Content-Type', file.type);
    xhr.setRequestHeader('x-amz-acl', 'public-read');
 
    xhr.send(file);
  }
}
 
function setProgress(percent, statusLabel)
{
  var progress = document.querySelector('.percent');
  progress.style.width = percent + '%';
  progress.textContent = percent + '%';
  document.getElementById('progress_bar').className = 'loading';
 
  document.getElementById('status').innerText = statusLabel;
}
    
 </script>

</head>
 
<body style="height: 186px; width: 400px; float:left; margin: 0;">
<div id="subir_foto_widget">
 <div style="width:186px; float: left;">
    <div id="fotoMarco_Imagen" style="width: 146px;height: 146px;padding-left: 5px;padding-top: 5px; margin-top: 5px;margin-left: 5px; -webkit-box-shadow: 0px 0px 9px rgba(50, 50, 50, 0.32); -moz-box-shadow: 0px 0px 9px rgba(50, 50, 50, 0.32); box-shadow: 0px 0px 9px rgba(50, 50, 50, 0.32);"><img src="<?php echo $urlFotoDefecto; ?>" id="foto" style="width:140px; height: 140px;"/></div><div id="fotoMarco_Canvas" style="width: 146px;height: 146px;padding-left: 5px;padding-top: 5px; margin-top: 5px;margin-left: 5px; -webkit-box-shadow: 0px 0px 9px rgba(50, 50, 50, 0.32); -moz-box-shadow: 0px 0px 9px rgba(50, 50, 50, 0.32); box-shadow: 0px 0px 9px rgba(50, 50, 50, 0.32); display: none;"><canvas id="myCanvas" width="140" height="140" style="display: none;"></canvas></div>
 </div>
 <div style="width:150px; float: left; top: 20px; position: relative;">
    <div style="font-family:'Lucida Grande',Helvetica; font-size: 16px; color: #00aeef;border-bottom: 2px dotted #BCBEB0;padding-bottom: 4px;width: 250px;margin-bottom: 10px;"><?php echo $titulo; ?></div>
    <div><input type="file" id="files" name="files[]" style="width:150px;"/></div>
 </div>
</div>
<div id="barra_progreso_widget" style=" width: 300px; height: 156px;
opacity: 0.7; position: absolute; background-color: #FFF ;clear:left; padding-top: 30px; padding-left: 100px; visibility: hidden;">
    <div>Progreso: <div id="progress_bar"><div class="percent">0%</div></div></div>
    <div>Status: <span id="status"></span></div>
</div>
  <script type="text/javascript">
    document.getElementById('files').addEventListener('change', handleFileSelect, false);
    setProgress(0, 'Esperando archivo.');
  </script>
 
</body>
 
</html>