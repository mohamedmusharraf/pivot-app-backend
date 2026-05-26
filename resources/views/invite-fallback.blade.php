<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Pivot</title>
    <script>
        window.onload = function() {
            var userAgent = navigator.userAgent || navigator.vendor || window.opera;

            // iOS Detection (iPhone, iPad, iPod)
            if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
                // Replace with your actual Apple App Store Link
                window.location.replace("https://apps.apple.com/app/6760004980");
                return;
            }

            // Android Detection
            if (/android/i.test(userAgent)) {
                // Replace with your actual Google Play Store Link
                window.location.replace("https://play.google.com/store/apps/details?id=com.pivotirl.mobile");
                return;
            }

            // Fallback for Desktop Users (Optional: redirect to your homepage)
            window.location.replace("https://api.pivotirl.com.au/");
        };
    </script>
    <style>
        /* Optional: A simple loading screen while the redirect happens */
        body {
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
            margin: 0;
        }
        .message {
            text-align: center;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="message">
        <h2>Redirecting to the app store...</h2>
        <p>If you are not redirected automatically, <a href="https://api.pivotirl.com.au">click here</a>.</p>
    </div>
</body>
</html>