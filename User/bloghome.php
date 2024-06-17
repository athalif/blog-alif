<?php
include '../connection.php'; // Include the connection file

// Pagination setup
$limit = 3; // Number of articles per page
$page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page
$start = ($page - 1) * $limit; // Offset

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Query to fetch articles based on the current page, limit, and search term
if (!empty($search)) {
    $query = "SELECT artikel.*, kategori.nama_kategori 
              FROM artikel 
              JOIN kategori ON artikel.id_kategori = kategori.id_kategori 
              WHERE artikel.judul LIKE '%$search%' 
              ORDER BY artikel.tanggal DESC 
              LIMIT $start, $limit";
} else {
    $query = "SELECT artikel.*, kategori.nama_kategori 
              FROM artikel 
              JOIN kategori ON artikel.id_kategori = kategori.id_kategori 
              ORDER BY artikel.tanggal DESC 
              LIMIT $start, $limit";
}

$result = $conn->query($query);

$total_result = $conn->query("SELECT COUNT(*) AS total FROM artikel WHERE artikel.judul LIKE '%$search%'")->fetch_assoc();
$total = $total_result['total'];

// Calculate total pages
$total_pages = ceil($total / $limit);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Basketball Buzz</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
    <style>
        .navbar-orange {
            background-color: #FFA500; 
        }
    </style>
    <style>
        .custom-container {
            background-color: black; 
            padding: 20px;  
        }
    </style>
</head>

<body>
    <!-- Responsive navbar-->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-orange">
        <div class="container d-flex justify-content-between">
            <a class="navbar-brand mx-auto" href="#!">BasketballBuzz</a>
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
    <!-- Page header with logo and tagline-->
    <header class="py-4 bg-dark border-bottom mb-4">
        <div class="container">
            <div class="text-center my-4" style="font-family: Arial, sans-serif;">
                <h1 class="fw-bolder text-white">Hai,Basketball Fans!</h1>
                <p class="lead mb-0 text-white">Jelajahi Konten Basket yang Seru dan Informatif Disini</p>
            </div>
        </div>
    </header>
    <!-- Page content-->
    <div class="container custom-container">
        <div class="row">
            <!-- Blog entries-->
            <div class="col-lg-8">
                <?php
                while ($row = $result->fetch_assoc()):
                ?>
                    <!-- Blog post-->
                    <div class="card mb-4">
                        <a href="blogpost.php?id=<?= $row['id_artikel'] ?>"><img class="card-img-top"
                                src="data:image/jpeg;base64,<?= base64_encode($row['gambar']) ?>" alt="..." width="850"
                                height="350" /></a>
                        <div class="card-body">
                            <div class="small text-muted"><?= ($row['tanggal']) ?></div>
                            <h2 class="card-title"><?= $row['judul'] ?></h2>
                            <a class="btn btn-primary" href="blogpost.php?id=<?= $row['id_artikel'] ?>">Lihat selengkapnya →</a>
                        </div>
                    </div>
                <?php
                endwhile;
                ?>
                <!-- Pagination-->
                <nav aria-label="Pagination">
                    <ul class="pagination justify-content-center my-4">
                        <?php if($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= ($page - 1); ?>&search=<?= $search ?>" tabindex="-1" aria-disabled="true">Previous</a>
                        </li>
                        <?php endif; ?>
                        <?php for($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php if($page == $i) { echo 'active'; } ?>">
                                <a class="page-link" href="?page=<?= $i; ?>&search=<?= $search ?>"><?= $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        <?php if($page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= ($page + 1); ?>&search=<?= $search ?>">Next</a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
            <!-- Side widgets-->
            <div class="col-lg-4">
                <!-- Search widget-->
                <div class="card mb-4 bg-warning">
                    <div class="card-header">Search</div>
                    <div class="card-body">
                        <form method="GET" action="bloghome.php">
                            <div class="input-group">
                                <input class="form-control" type="text" name="search" placeholder="cari.." aria-label="Cari Artikel..." aria-describedby="button-search" value="<?= $search ?>" />
                                <button class="btn btn-primary" id="button-search" type="submit">GO</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Categories widget-->
                <div class="card mb-4 bg-warning">
                    <div class="card-header">Kategori</div>
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
                    <div class="card-header">For Your Information</div>
                    <div class="card-body" style="line-height: 1.6;"> Basketball juga telah menjadi bagian penting dari budaya populer, dengan banyak pemain dan pertandingan yang mendapat perhatian luas di media. Selain itu, olahraga ini telah menjadi platform bagi banyak atlet untuk mencapai kesuksesan dan terkenal di seluruh dunia.</div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>
</body>

</html>
