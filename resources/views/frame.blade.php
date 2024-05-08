<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo Frame Generator</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-semibold text-center mb-8">Photo Frame Generator</h1>
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
        </div>
    </div>

    <script>
        document.getElementById('uploadInput').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const reader = new FileReader();
 
            reader.onload = function(e) {
                document.getElementById('uploadedImage').src = e.target.result;
            };

            reader.readAsDataURL(file);
        });

        document.getElementById('frameSelect').addEventListener('change', function(event) {
            const selectedFrame = event.target.value;
            document.getElementById('selectedFrameImage').src = selectedFrame;
        });
        let isDragging = false;
        let offsetX, offsetY;

        document.getElementById('uploadedImage').addEventListener('mousedown', function(event) {
            isDragging = true;
            offsetX = event.offsetX;
            offsetY = event.offsetY;
        });

        document.addEventListener('mousemove', function(event) {
            if (isDragging) {
                const frameContainer = document.getElementById('frameContainer');
                const uploadedImage = document.getElementById('uploadedImage');
                const mouseX = event.clientX - frameContainer.offsetLeft;
                const mouseY = event.clientY - frameContainer.offsetTop;

                uploadedImage.style.left = mouseX - offsetX + 'px';
                uploadedImage.style.top = mouseY - offsetY + 'px';
            }
        });

        document.addEventListener('mouseup', function() {
            isDragging = false;
        });




        document.getElementById('generateBtn').addEventListener('click', function() {
            const uploadedImage = document.getElementById('uploadedImage');
            const selectedFrame = document.getElementById('frameSelect').value;
            const frameContainer = document.getElementById('frameContainer');

            if (uploadedImage.src && selectedFrame) {
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');

                canvas.width = frameContainer.offsetWidth;
                canvas.height = frameContainer.offsetHeight;

                ctx.drawImage(uploadedImage, 0, 0, canvas.width, canvas.height);

                const frameImg = new Image();
                frameImg.src = selectedFrame;
                frameImg.onload = function() {
                    ctx.drawImage(frameImg, 0, 0, canvas.width, canvas.height);

                    const dataUrl = canvas.toDataURL('image/png');
                    const link = document.createElement('a');
                    link.href = dataUrl;
                    link.download = 'generated_image.png';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                };
            } else {
                alert('Please upload an image and select a frame.');
            }
        });
    </script>
</body>

</html>
