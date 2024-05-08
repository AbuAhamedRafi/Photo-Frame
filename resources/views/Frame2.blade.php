<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo Frame Generator with Crop</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-semibold text-center mb-8">Photo Frame Generator with Crop</h1>
        <div class="flex justify-center mb-8">
            <div class="w-64">
                <!-- Dropdown for frame selection -->
                <p class="text-center"><strong>Choose Your frame Type</strong></p>
                <select id="frameSelect"
                    class="block w-full py-2 px-4 bg-gray-200 text-gray-800 rounded cursor-pointer focus:outline-none">
                    <option value="{{ asset('images/Champion.png') }}">Champion</option>
                    <option value="{{ asset('images/Member.png') }}">Member</option>
                    <option value="{{ asset('images/Sophists.png') }}">Sophists</option>
                    <option value="{{ asset('images/Tejas.png') }}">Tejas</option>
                    <option value="{{ asset('images/Winner.png') }}">Winner</option>
                </select>
            </div>
        </div>
        <div id="frameContainer" class="bg-white w-64 h-64 border border-gray-300 relative overflow-hidden mx-auto">
            <img id="uploadedImage" class="absolute top-0 left-0 w-full h-full object-cover" src=""
                alt="Uploaded Image">
            <!-- Display selected frame -->
            <img id="selectedFrameImage" src="" class="absolute top-0 left-0 w-full h-full object-cover"
                alt="Selected Frame">
        </div>
        <div class="flex justify-center mt-8">
            <input type="file" id="uploadInput" accept="image/*" class="hidden">
            <label for="uploadInput"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-300 ease-in-out mr-4 cursor-pointer">Upload
                Image</label>
            <button id="generateBtn"
                class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition duration-300 ease-in-out mr-4">Generate
                Image</button>
            <button id="cropBtn"
                class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition duration-300 ease-in-out mr-4">Crop</button>
        </div>
    </div>

    <script>
        let cropper;

        document.getElementById('uploadInput').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.onload = function() {
                    // Destroy previous cropper instance if exists
                    if (cropper) {
                        cropper.destroy();
                    }
                    // Initialize Cropper
                    cropper = new Cropper(img, {
                        aspectRatio: NaN, // Allow free aspect ratio
                        viewMode: 1, // Restricts the cropping box to always fit within the container
                        autoCropArea: 1, // Always create a crop box that fills the preview area
                        crop(event) {
                            const canvasData = cropper.getCanvasData();
                            const cropData = cropper.getCropBoxData();
                            console.log('Canvas Data:', canvasData);
                            console.log('Crop Box Data:', cropData);
                        }
                    });
                };
                document.getElementById('uploadedImage').src = e.target.result;
                document.getElementById('frameContainer').appendChild(img);
            };

            reader.readAsDataURL(file);
        });

        document.getElementById('frameSelect').addEventListener('change', function(event) {
            const selectedFrame = event.target.value;
            document.getElementById('selectedFrameImage').src = selectedFrame;
        });

        document.getElementById('generateBtn').addEventListener('click', function() {
            if (cropper) {
                const canvas = cropper.getCroppedCanvas();
                const selectedFrame = document.getElementById('frameSelect').value;
                const frameContainer = document.getElementById('frameContainer');

                if (canvas && selectedFrame) {
                    const ctx = canvas.getContext('2d');

                    // Draw the frame
                    const frameImg = new Image();
                    frameImg.src = selectedFrame;
                    frameImg.onload = function() {
                        ctx.drawImage(frameImg, 0, 0, canvas.width, canvas.height);
                        const dataUrl = canvas.toDataURL('image/png');

                        // Trigger download
                        const link = document.createElement('a');
                        link.href = dataUrl;
                        link.download = 'generated_image.png';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    };
                } else {
                    alert('Please select a frame.');
                }
            } else {
                alert('Please upload an image and crop it.');
            }
        });

        document.getElementById('cropBtn').addEventListener('click', function() {
            if (cropper) {
                cropper.crop();
            }
        });
    </script>
</body>

</html>
