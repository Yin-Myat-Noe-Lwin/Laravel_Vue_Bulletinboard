export function handleImageUpload(event, profileError, userProfileImagePreviewUrl) {

  const imgFile = event.target.files[0];

  const imgSize = imgFile.size;

  const maxSize = 2 * 1024 * 1024;

  if (imgSize > maxSize) {

    profileError.value = "Image file size must be less than 2MB";

  } else {

    const reader = new FileReader();

    reader.onload = function (e) {

      userProfileImagePreviewUrl.value = e.target.result;

    };

    reader.readAsDataURL(imgFile);

  }
}
