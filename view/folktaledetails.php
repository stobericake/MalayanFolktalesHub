


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Folktales List</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .comments-section {
            max-height: 400px;
            overflow-y: auto;
            
        }
        .comment-box {
            resize: none;
            
        }
        .folktale-header {
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
        }
        .tabs {
            margin-top: 5px;
        }
        .nav-tabs .nav-link {
            color: black;
            background-color: #f8f9fa;
            border-bottom: 1px solid #BF2C24;
            margin-right: 2px;
            border-radius: 5px;
        }
        .nav-tabs .nav-link.active {
            color: #BF2C24;
            background-color: #fff;
            border-color: #BF2C24;
            border-bottom: 2px solid #BF2C24;
        }
        .nav-tabs .nav-link:hover {
            color: #BF2C24;
            background-color: #e2e6ea;
        }
        .btn-custom {
            background-color: #EFEFF4;
            border-color: transparent;
            border-radius: 0;
            padding: 10px;
            max-height: 50px;
            width: 150px;
        }
        .profile-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?> 
<div id="content" class="d-flex">
    <div class="container flex-grow-1">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Folktales</a></li>
                <li class="breadcrumb-item active" aria-current="page">Anak Yang Taat</li>
            </ol>
        </nav>

        <!-- Folktale Header** -->
        <div class="folktale-header">
            <h2>Anak Yang Taat</h2>
            <p><i class="bi bi-bookmark"></i> Teladan | <i class="bi bi-geo-alt"></i> Perak</p>
        </div>

        <!-- Folktale Statistics **-->
        <div class="row">
            <div class="col-md-4">
                <p><i class="bi bi-eye-fill"></i><strong> 5.6M</strong> Views</p>
            </div>
            <div class="col-md-4">
                <p><i class="bi bi-star-fill"></i><strong> 4.8</strong> Bookmarks</p>
            </div>  
        </div>
        <div class="row mb-3">
            <div class="col-md-2">
                <button class="btn-custom"><i class="bi bi-box-arrow-down"></i> Download</button>
            </div>
            <div class="col-md-2">
                <button class="btn-custom"><i class="bi bi-chat-quote"></i> Comments</button>
            </div>
            <div class="col-md-2">
                <button class="btn-custom"><i class="bi bi-bookmark-star"></i> Bookmark</button>
            </div>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-tabs tabs mb-3">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#introduction">Introduction</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#bookmarks">Bookmarks (100)</a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="introduction">
                <h4>Description</h4>
                <p>
                    Cerita "Anak yang Taat" mengisahkan tentang seorang budak lelaki bernama Jasa yang sangat baik dan taat kepada ibunya.
                    Setelah kehilangan ayahnya dan dalam keadaan miskin akibat peperangan, Jasa berusaha mencari buah-buahan untuk dijadikan makanan.
                </p>
                <p>
                    Ketika ditangkap oleh perompak, sikapnya yang baik dan pengorbanan untuk ibunya membuatkan perompak insaf.
                    Mereka memberikan wang kepada Jasa, tetapi dia menolak dan menuntut agar mereka mengembalikan harta yang dicuri. 
                    Jasa pulang ke rumah dengan penuh maruah dan ketaatan kepada ibunya.
                </p>
            </div>
            <div class="tab-pane fade" id="bookmarks">
                <p>Bookmark content here...</p>
            </div>
        </div>
    </div>

    <!-- Comments Section with Profile Picture **-->
    <div class="comments-section mt-5 p-4 border rounded">
    <h5 class="mb-4">Comments <span class="badge bg-secondary">14</span></h5>
    <div class="comment-list mb-4">
        <div class="comment-item d-flex align-items-start mb-4">
            <img src="profile1.jpg" alt="User Profile" class="rounded-circle me-3" width="50">
            <div>
                <strong>Sharifah Nur Ain</strong>
                <p>GREAT STORY. LOVED THE CHARACTERS. HIGHLY RECOMMENDED.</p>
            </div>
        </div>
        <div class="comment-item d-flex align-items-start mb-4">
            <img src="profile2.jpg" alt="User Profile" class="rounded-circle me-3" width="50">
            <div>
                <strong>Sharifah Nur Ain</strong>
                <p>GREAT STORY. LOVED THE CHARACTERS. HIGHLY RECOMMENDED.</p>
            </div>
        </div>
    </div>
    <div class="comment-form" style="background-color:rgb(255, 240, 249); border-radius: 8px; padding: 15px;">
        <div class="d-flex align-items-start mb-3">
            <img src="user-profile.jpg" alt="Your Profile" class="rounded-circle me-3" width="50">
            <textarea class="form-control" placeholder="Leave a comment" style="background-color: #fafafa; border-color: #ccc;"></textarea>
        </div>
        <button class="btn btn-primary">Post</button>
    </div>
</div>



</div>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
