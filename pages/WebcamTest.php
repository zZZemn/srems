<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webcam Capture</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div>
        <video id="webcam" autoplay playsinline width="320" height="240" style="border: 1px solid black;"></video>
        <br />
        <button id="capture">Capture Image</button>
        <canvas id="canvas" style="display: none;"></canvas>
        <br />
        <img id="imagePreview" alt="Captured Image" style="display: none; max-width: 320px; border: 1px solid black;" />
        <br />
        <input type="file" id="fileInput" style="display: none;" />
    </div>

    <script>
        $(document).ready(function() {
            const video = $('#webcam')[0];
            const canvas = $('#canvas')[0];
            const imgPreview = $('#imagePreview');
            const fileInput = $('#fileInput');

            // Access the webcam
            navigator.mediaDevices.getUserMedia({
                    video: true
                })
                .then((stream) => {
                    video.srcObject = stream;
                })
                .catch((err) => {
                    console.error('Failed to access webcam:', err);
                    alert('Could not access webcam. Make sure you have granted permissions.');
                });

            // Capture image
            $('#capture').click(() => {
                const context = canvas.getContext('2d');

                // Set canvas size to match video dimensions
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;

                // Draw the video frame onto the canvas
                context.drawImage(video, 0, 0, canvas.width, canvas.height);

                // Convert the canvas to a data URL
                const imageDataURL = canvas.toDataURL('image/png');

                // Show the captured image in the imgPreview
                imgPreview.attr('src', imageDataURL).show();

                // Convert the canvas image to a File object
                canvas.toBlob((blob) => {
                    const file = new File([blob], 'captured-image.png', {
                        type: 'image/png'
                    });

                    // Assign the File object to the file input using DataTransfer
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    fileInput[0].files = dataTransfer.files;

                    console.log('Captured image set to file input:', fileInput[0].files[0]);
                });
            });
        });
    </script>
</body>

</html>