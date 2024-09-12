<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timeline Example</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .timeline {
            position: relative;
            max-width: 1200px;
            margin: 20px auto;
            padding: 40px 0;
        }

        .timeline::after {
            content: '';
            position: absolute;
            width: 6px;
            background-color: #00BCD4;
            top: 0;
            bottom: 0;
            left: 50%;
            margin-left: -3px;
        }

        .timeline-entry {
            padding: 10px 40px;
            position: relative;
            background-color: inherit;
            width: 50%;
            box-sizing: border-box;
        }

        .timeline-entry::after {
            content: '';
            position: absolute;
            width: 25px;
            height: 25px;
            background-color: #00BCD4;
            border: 4px solid #FFF;
            top: 15px;
            border-radius: 50%;
            z-index: 1;
        }

        .timeline-entry.left {
            left: 0;
        }

        .timeline-entry.right {
            left: 50%;
        }

        .timeline-entry.left::after {
            right: -17px;
        }

        .timeline-entry.right::after {
            left: -17px;
        }

        .timeline-entry-content {
            padding: 20px;
            background-color: #fff;
            position: relative;
            border-radius: 6px;
            border-left: 4px solid #00BCD4;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .timeline-entry.left .timeline-entry-content {
            border-left: 0;
            border-right: 4px solid #00BCD4;
        }

        .timeline-entry-content h2 {
            margin: 0 0 10px;
            font-size: 1.5em;
            color: #333;
        }

        .timeline-entry-content p {
            margin: 0;
            color: #666;
        }

        .timeline-date {
            position: absolute;
            top: 15px;
            width: 140px;
            padding: 10px;
            background: #00BCD4;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            font-size: 1.1em;
        }

        .timeline-entry.left .timeline-date {
            right: -180px;
        }

        .timeline-entry.right .timeline-date {
            left: -180px;
        }

        @media (max-width: 767px) {
            .timeline::after {
                left: 20px;
            }

            .timeline-entry {
                width: 100%;
                padding-left: 80px;
                padding-right: 25px;
            }

            .timeline-entry.left,
            .timeline-entry.right {
                left: 0;
            }

            .timeline-entry.left .timeline-date,
            .timeline-entry.right .timeline-date {
                left: 0;
                top: -50px;
                right: auto;
            }

            .timeline-entry::after {
                left: 15px;
                right: auto;
            }

            .timeline-entry.left .timeline-entry-content,
            .timeline-entry.right .timeline-entry-content {
                border-radius: 0;
                border-right: none;
                border-left: 4px solid #00BCD4;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>

<div class="timeline">
    <div class="timeline-entry left">
        <div class="timeline-date">19 Juli, 2024</div>
        <div class="timeline-entry-content">
            <h2>Gucklöcher von oben</h2>
            <p>Letzte Woche hat unser Team präzise Kernbohrungen für die Belüftungssysteme von Grow-Room-B durchgeführt...</p>
        </div>
    </div>
    <div class="timeline-entry right">
        <div class="timeline-date">10 Juli, 2024</div>
        <div class="timeline-entry-content">
            <h2>Keimlinge sind im Boden</h2>
            <p>Seit Dienstag ist unser Anbauteam damit beschäftigt, unsere Samen und Stecklinge einzupflanzen...</p>
        </div>
    </div>
</div>

</body>
</html>
