<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ProBioHanf - Cannabis Klub Osnabrück</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <!-- Preloader -->
        <div id="preloader">
            <div class="spinner"></div>
        </div>

        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">ProBioHanf.de</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="#mainpage">Startseite</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#aboutus">Über uns</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#team">Team</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#current-products">Aktuelles Angebot</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#timeline">Zeitplan</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Main layout with sidebar for contact info -->
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar (Contact info) -->
                <aside class="col-lg-3 bg-light sticky-top py-4">
                    <div class="contact-info text-center">
                        <h2>Kontakt</h2>
                        <p>✉️ andre.mohrmann@web.de</p>
                        <?php
                            $logotype = isset($_GET['logotype']) ? (int) $_GET['logotype'] : 1;
                            $logocolor = isset($_GET['logocolor']) ? (int) $_GET['logocolor'] : 1;
                            $imagePath = "./images/logo" . $logotype . "_" . $logocolor . ".png";
                            echo '<img id="logo" src="' . htmlspecialchars($imagePath, ENT_QUOTES, 'UTF-8') . '" alt="ProBioHanf Logo" class="img-fluid">';
                        ?>
                    </div>
                </aside>

                <!-- Main Content -->
                <main class="col-lg-9">
                    <section id="mainpage" class="py-5">
                        <h1>Willkommen bei ProBioHanf.de</h1>
                        <h2>Dein Cannabis Klub in Osnabrück!</h2>
                        <p>Wir legen besonderen Wert auf nachhaltigen Anbau und setzen auf das innovative <strong>Living Soil</strong>-System...</p>
                    </section>

                    <section id="aboutus" class="py-5">
                        <h2>Über uns</h2>
                        <p>Erfahre mehr über unsere Philosophie und den nachhaltigen Anbau...</p>
                    </section>

                    <section id="team" class="py-5">
                        <h2>Unser Team</h2>
                        <p>Lerne die engagierten Menschen hinter ProBioHanf kennen...</p>
                    </section>

                    <section id="current-products" class="py-5">
                        <h2>Aktuelles Angebot</h2>
                        <p>Hier findest du unser aktuelles Sortiment...</p>
                    </section>

                    <section id="timeline" class="py-5">
                        <h2>Zeitplan</h2>
                        <p>Unser Fortschritt und zukünftige Meilensteine...</p>
                    </section>
                </main>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-dark text-light text-center py-3">
            <nav>
                <ul class="list-unstyled d-flex justify-content-center">
                    <li class="mx-3"><a href="#mainpage" class="text-light">Kontakt</a></li>
                    <li class="mx-3"><a href="#aboutus" class="text-light">Über uns</a></li>
                    <li class="mx-3 grayed"><a href="#current-products" class="text-light">Aktuelles Angebot</a></li>
                </ul>
            </nav>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="scripts.js"></script>
    </body>
</html>
