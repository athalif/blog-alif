<?php
include '../connection.php'; 

// Get category from URL
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';

// Get search term from URL
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Tentukan jumlah artikel per halaman (1 untuk semua kategori)
$limit = 1;

// Tentukan halaman saat ini
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;

$start = ($page - 1) * $limit;

// Query to get articles for the specified category with pagination and search
if (!empty($search)) {
    $query = $conn->prepare("SELECT artikel.*, kategori.nama_kategori 
                             FROM artikel 
                             JOIN kategori ON artikel.id_kategori = kategori.id_kategori 
                             WHERE artikel.judul LIKE ? 
                             ORDER BY artikel.tanggal DESC
                             LIMIT ?, ?");
    $search_param = "%" . $search . "%";
    $query->bind_param("sii", $search_param, $start, $limit);
} else {
    $query = $conn->prepare("SELECT artikel.*, kategori.nama_kategori 
                             FROM artikel 
                             JOIN kategori ON artikel.id_kategori = kategori.id_kategori 
                             WHERE kategori.nama_kategori = ? 
                             ORDER BY artikel.tanggal DESC
                             LIMIT ?, ?");
    $query->bind_param("sii", $kategori, $start, $limit);
}
$query->execute();
$result = $query->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Basketball Buzz - <?= htmlspecialchars($kategori) ?></title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
    <style>
        .navbar-orange {
            background-color: #FFA500; 
        }
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
            <a class="navbar-brand mx-auto" href="index.php">BasketballBuzz</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="bloghome.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.html">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.html">Contact</a></li>
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="index.php">Blog</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Page header with logo and tagline-->
    <header class="py-4 bg-dark border-bottom mb-4">
        <div class="container">
            <div class="text-center my-4" style="font-family: Arial, sans-serif;">
                <h1 class="fw-bolder text-white">Kategori <?= htmlspecialchars($kategori) ?></h1>
            </div>
        </div>
    </header>
    <!-- Page content-->
    <div class="container custom-container">
        <div class="row">
            <!-- Blog entries-->
            <div class="col-lg-8">
                <?php
                if ($result->num_rows > 0):
                    $row = $result->fetch_assoc(); // Hanya mengambil satu artikel pada halaman kategori
                ?>
                <!-- Featured blog post-->
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
                endif;
                ?>
            </div>
            <!-- Side widgets-->
            <div class="col-lg-4">
                <!-- Search widget-->
                <div class="card mb-4 bg-warning">
                    <div class="card-header">Search</div>
                    <div class="card-body">
                        <form action="bloghome.php" method="get">
                            <div class="input-group">
                                <input class="form-control" type="text" name="search" placeholder="cari..."
                                    aria-label="cari..." aria-describedby="button-search" />
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
                <!-- Pagination-->
                <nav aria-label="Pagination">
                    <hr class="my-0" />
                    <ul class="pagination justify-content-center my-4">
                        <?php
                        // Adjusted query to count total articles based on search or category
                        if (!empty($search)) {
                            $query_count = $conn->prepare("SELECT COUNT(*) AS total FROM artikel WHERE judul LIKE ?");
                            $query_count->bind_param("s", $search_param);
                        } else {
                            $query_count = $conn->prepare("SELECT COUNT(*) AS total FROM artikel WHERE id_kategori = (SELECT id_kategori FROM kategori WHERE nama_kategori = ?)");
                            $query_count->bind_param("s", $kategori);
                        }
                        $query_count->execute();
                        $result_count = $query_count->get_result();
                        $row_count = $result_count->fetch_assoc();
                        $total_pages = ceil($row_count['total'] / $limit);

                        if ($page > 1) {
                            echo '<li class="page-item"><a class="page-link" href="category.php?kategori=' . $kategori . '&search=' . $search . '&page=' . ($page - 1) . '">Previous</a></li>';
                        }

                        for ($i = 1; $i <= $total_pages; $i++) {
                            if ($i == $page) {
                                echo '<li class="page-item active" aria-current="page"><a class="page-link" href="#">' . $i . '</a></li>';
                            } else {
                                echo '<li class="page-item"><a class="page-link" href="category.php?kategori=' . $kategori . '&search=' . $search . '&page=' . $i . '">' . $i . '</a></li>';
                            }
                        }

                        if ($page < $total_pages) {
                            echo '<li class="page-item"><a class="page-link" href="category.php?kategori=' . $kategori . '&search=' . $search . '&page=' . ($page + 1) . '">Next</a></li>';
                        }
                        ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</body>
</html>
