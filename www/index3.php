<html>
<head>
<?php
function createPublicFolder($service, $folderName) {
  $file = new Google_DriveFile();
  $file->setTitle($folderName);
  $file->setMimeType('application/vnd.google-apps.folder');

  $createdFile = $service->files->insert($file, array(
      'mimeType' => 'application/vnd.google-apps.folder',
  ));

  $permission = new Google_Permission();
  $permission->setValue('');
  $permission->setType('anyone');
  $permission->setRole('reader');

 $service->permissions->insert(
      $createdFile->getId(), $permission);

  return $file;
}

?>
<script>
  var clientId = '80569418227-d8l5u7k5iquguatrpga8u9saqeaf4a87.apps.googleusercontent.com';
  var developerKey = 'AIzaSyAcrJTmYWy-eLELWyMlcc_SvQwxCohWsCk';
  var accessToken;
  function onApiLoad() {
    gapi.load('auth', authenticateWithGoogle);
    gapi.load('picker');
  }
  function authenticateWithGoogle() {
    window.gapi.auth.authorize({
      'client_id': clientId,
      'scope': ['https://www.googleapis.com/auth/drive']
    }, handleAuthentication);
  }
  function handleAuthentication(result) {
    if(result && !result.error) {
      accessToken = result.access_token;
      authPicker();
    }
    else{
      alert('authentication failed')
    }
  }
  function authPicker() {
	var pickit = new google.picker.PickerBuilder()
	.addView(new google.picker.DocsUploadView())
	.setOAuthToken(accessToken)
	.setDeveloperKey(developerkey)
	.setCallback(myCallback)
	.build();
  }
  function setupPicker() {
    var picker = new google.picker.PickerBuilder()
    .addView(new google.picker.DocsUploadView().setParent('0B87ieV4WOmcBcWkzVERwTDViXzQ'))
    .addView(new google.picker.DocsView().setIncludeFolders(true))
      .setOAuthToken(accessToken)
      .setDeveloperKey(developerKey)
      .setCallback(myCallback)
      .build();
    picker.setVisible(true);
  }
  function myCallback(data) {
    if (data.action == google.picker.Action.PICKED) {
      alert('Sucessfully upload the file named' + data.docs[0].name);
    } else if (data.action == google.picker.Action.CANCEL) {
      alert('goodbye');
    }
  }

</script>
</head>

<body>
<input id="uploadImage" type="button" value="Upload Image" onclick="setupPicker();" />
<input id="uploadImage" type="button" value="Upload Image" onclick="setupPicker();" />
<input id="uploadImage" type="button" value="Upload Image" onclick="setupPicker();" />
<input id="uploadImage" type="button" value="Upload Image" onclick="setupPicker();" />
<input id="uploadImage" type="button" value="Upload Image" onclick="setupPicker();" />
<input id="uploadImage" type="button" value="Upload Image" onclick="setupPicker();" />
<input id="uploadImage" type="button" value="Upload Image" onclick="setupPicker();" />
<input id="uploadImage" type="button" value="Upload Image" onclick="setupPicker();" />
<input id="uploadImage" type="button" value="Upload Image" onclick="setupPicker();" />
<img src="https://docs.google.com/file/d/0B87ieV4WOmcBNEFPM0xuTjEzYTQ" alt="Smiley face" height="42" width="42">
</body>

<script src="https://apis.google.com/js/api.js?onload=onApiLoad"></script>
</html>