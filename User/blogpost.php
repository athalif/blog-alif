<?php
include '../connection.php'; 

// Handle search functionality
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$search_query = "";
if (!empty($search)) {
    $search_query = " AND artikel.judul LIKE '%$search%'";
}

// Get the article ID from the URL
$id_artikel = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query to get the article
$query = "SELECT artikel.*, kategori.nama_kategori 
          FROM artikel 
          JOIN kategori ON artikel.id_kategori = kategori.id_kategori 
          WHERE artikel.id_artikel = ? $search_query";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_artikel);
$stmt->execute();
$result = $stmt->get_result();
$article = $result->fetch_assoc();
$stmt->close();

if (!$article) {
    die("Artikel tidak ditemukan.");
}

// Query to get comments
$query = "SELECT * FROM comments WHERE id_artikel = ? ORDER BY tanggal DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_artikel);
$stmt->execute();
$result = $stmt->get_result();
$comments = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title><?= htmlspecialchars($article['judul']) ?> - Blog Post</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
    <style>
        .bg-orange {
            background-color: orange !important;
        }
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        body {
            background-color: black;
            color: white;
        }
        .post-content {
            background-color: #111;
            padding: 20px;
            border-radius: 10px;
        }
        <style>
    .post-content img {
        max-width: 100%;
        height: auto;
    }
</style>

    </style>
</head>

<body>
    <!-- Responsive navbar-->
    <nav class="navbar navbar-expand-lg navbar-dark bg-orange">
        <div class="container">
            <a class="navbar-brand" href="bloghome.php">Basketball Buzz</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="bloghome.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.html">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.html">Contact</a></li>
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="#">Blog</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Page content-->
    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-8">
                <!-- Post content-->
                <article>
                    <!-- Post header-->
                    <header class="mb-4">
                        <!-- Post title-->
                        <h1 class="fw-bolder mb-1"><?= htmlspecialchars($article['judul']) ?></h1>
                        <!-- Post meta content-->
                        <div class="text-muted fst-italic mb-2">
                            <?= htmlspecialchars($article['tanggal']) ?> oleh
                            <?= htmlspecialchars($article['penulis']) ?>
                        </div>
                        <!-- Post categories-->
                        <a class="badge bg-secondary text-decoration-none link-light"
                            href="#!"><?= htmlspecialchars($article['nama_kategori']) ?></a>
                    </header>
                    <!-- Preview image figure-->
                    <figure class="mb-4">
                    <img class="img-fluid rounded" src="data:image/jpeg;base64,<?= base64_encode($article['gambar']) ?>" alt="..." width="100%" /></figure>
                    <!-- Post content-->
                    <section class="mb-5">
                        <p class="fs-5 mb-4"><?= nl2br(htmlspecialchars($article['isi'])) ?></p>
                    </section>
                </article>
                <!-- Comments section-->
                <section class="mb-5">
                    <div class="card bg-light">
                        <div class="card-body text-dark">
                            <!-- Comment form-->
                            <form class="mb-4" action="submit_comment.php" method="post">
                                <input type="hidden" name="id_artikel" value="<?= $id_artikel ?>">
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama</label>
                                    <input type="text" class="form-control" id="nama" name="nama" required>
                                </div>
                                <div class="mb-3">
                                    <label for="komentar" class="form-label">Komentar</label>
                                    <textarea class="form-control" id="komentar" name="komentar" rows="3" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Kirim</button>
                            </form>
                            <!-- Display comments-->
                            <?php foreach ($comments as $comment): ?>
                                <div class="d-flex mb-4 text-dark">
                                    <div class="flex-shrink-0"><img class="rounded-circle"
                                            src="https://dummyimage.com/50x50/ced4da/6c757d.jpg" alt="..." /></div>
                                    <div class="ms-3">
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($comment['nama']) ?></div>
                                        <div><?= nl2br(htmlspecialchars($comment['komentar'])) ?></div>
                                        <div class="text-muted fst-italic"><?= htmlspecialchars($comment['tanggal']) ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </section>
            </div>
            <!-- Side widgets-->
            <div class="col-lg-4">
                <!-- Search widget-->
                <div class="card mb-4 bg-warning">
                    <div class="card-header text-dark">Search</div>
                    <div class="card-body">
                        <form action="bloghome.php" method="get">
                            <div class="input-group">
                                <input class="form-control" type="text" name="search" placeholder="cari..."
                                    aria-label="cari..." aria-describedby="button-search" />
                                <button class="btn btn-primary" id="button-search" type="submit">Go!</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Categories widget-->
                <div class="card mb-4 bg-warning">
                    <div class="card-header text-dark">Kategori</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <ul class="list-unstyled mb-0">
                                    <li><a href="category.php?kategori=Basket Putra">Basket Putra</a></li>
                                    <li><a href="category.php?kategori=Basket Putri">Basket Putri</a></li>
                                </ul>
                            </div>
                            <div class="col-sm-6">
                                <ul class="list-unstyled mb-0">
                                    <li><a href="category.php?kategori=Profil Pemain">Profil Pemain</a></li>
                                    <li><a href="category.php?kategori=Profil Tim">Profil Tim</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Side widget-->
                <div class="card mb-4 bg-warning">
                    <div class="card-header text-dark">For Your Information</div>
                    <div class="card-body text-dark" style="line-height: 1.6;">Salah satu pertandingan basket paling terkenal adalah pertandingan yang terjadi antara Philadelphia Warriors dan New York Knicks pada 2 Maret 1962. Pada pertandingan itu, pemain legendaris NBA, Wilt Chamberlain, mencetak rekor sepanjang masa dengan mencetak 100 poin dalam satu pertandingan.</div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer-->
    <footer class="py-5 bg-orange">
        <div class="container">
            <p class="m-0 text-center text-white">Copyright&copy;BasketballBuzz 2024</p>
        </div>
    </footer>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>
</body>

</html>
