<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rimba Page</title>
    <style>
        /* Sets full-screen background using your local image path */
        body {
            background-color: rgb(0, 0, 0);
            background-image: url('pics/rimba.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;

            color: #ffffff;
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;

            /* Centers the main content box perfectly on the screen */
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            box-sizing: border-box;
            padding: 20px;

            /* NEW: Required so the floating box can position itself relative to the screen boundaries */
            position: relative;
        }

        /* Semi-transparent container box to ensure text readability over the image */
        .content-box {
            background-color: rgba(0, 0, 0, 0.6);
            max-width: 600px;
            width: 100%;
            padding: 40px;
            border-radius: 10px;
            box-sizing: border-box;

            /* Traps the box entirely within the screen height */
            max-height: 90vh;
            overflow-y: auto;
        }

        /* Clean scrolling behaviors for webkit browsers */
        .content-box::-webkit-scrollbar {
            width: 6px;
        }

        .content-box::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }

        h1 {
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        p {
            font-size: 0.95rem;
            line-height: 1.6;
        }

        /* Flex wrapper to automatically manage button rows and columns */
        .button-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
            justify-content: center;
            align-items: center;
        }

        /* Styled button links */
        .button-link {
            display: inline-block;
            background-color: #d8c8a5;
            color: #000000;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease;
            box-sizing: border-box;
            width: 100%;
        }

        .button-link:hover {
            background-color: #0056b3;
        }

        /* NEW: The floating text box (Hidden by default on small screens) */
        .floating-box {
            display: none;
            position: absolute;

            /* Premium jungle-inspired typography */
            font-family: "Cormorant Garamond", "Georgia", serif;
            color: #d8c8a5;
            /* warm bamboo/sand tone */

            background: rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(4px);

            padding: 20px 24px;
            border-left: 3px solid rgba(216, 200, 165, 0.5);

            font-size: 24px;
            text-align: left;
            max-width: 450px;
            line-height: 1.5;

            bottom: 40px;
            right: 40px;

            text-shadow:
                0 2px 8px rgba(0, 0, 0, 0.8);
        }

        /* RESPONSIVE BREAKPOINT */
        @media (min-width: 768px) {
            h1 {
                font-size: 2rem;
            }

            .button-container {
                flex-direction: row;
            }

            .button-link {
                width: auto;
            }

            .floating-box {
                display: block;
            }
        }
    </style>
</head>

<body>

    <!-- Main Centered Content Box -->
    <div class="content-box">
        <h1>The Industrial Nervous System for Factory</h1>
        <p>Designed for modern manufacturing and enterprise teams, RIMBA brings everything organization needs into one
            unified platform — enabling clarity, efficiency, and smarter decisions at every level.</p>

        <div class="button-container">
            <a href="{{ route('filament.staff.pages.dashboard') }}" class="button-link">Go to Login</a>
        </div>
    </div>

    <!-- NEW: Floating Text Box -->
    <div class="floating-box">
        <h2>RIMBA</h2>
        Reliable Information on Manufacturing & Business Activities
    </div>

</body>

</html>
