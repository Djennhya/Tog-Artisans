<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tog'Artisans - Livreur</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Poppins', sans-serif;
    }
    .sidebar {
      height: 100vh;
      background-color: #fff;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      position: fixed;
      width: 230px;
    }
    .sidebar a {
      display: block;
      padding: 14px 20px;
      color: #333;
      text-decoration: none;
      font-weight: 500;
    }
    .sidebar a:hover {
      background-color: #f1f1f1;
      color: #198754;
    }
    .logout {
      position: absolute;
      bottom: 20px;
      width: 100%;
    }
    .content {
      margin-left: 230px;
      padding: 20px 40px;
    }
    .topbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 25px;
    }
    .search-bar {
      width: 60%;
    }
    .card {
      border-radius: 15px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    }
  </style>
</head>
<body>
  <div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar p-3">
      <h4 class="text-center mb-4"><i class="bi bi-truck"></i> Livreur</h4>
      <a href="livraison_assignee.php"><i class="bi bi-box2"></i> Livraisons assignées</a>
      <a href="status.php"><i class="bi bi-clipboard2-check"></i> Statuts</a>
      <a href="historique.php"><i class="bi bi-clock-history"></i> Historique</a>

      <div class="logout">
        <a href="#"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
      </div>
    </div>

    <!-- Main content -->
    <div class="content flex-grow-1">
      <div class="topbar">
        <input type="text" class="form-control search-bar" placeholder="Rechercher une livraison...">
        <div class="d-flex align-items-center">
          <span class="me-2 fw-semibold">Ali Livreur</span>
          <img src="https://cdn-icons-png.flaticon.com/512/147/147144.png" alt="profil" width="40" class="rounded-circle">
        </div>
      </div>

      <h3 class="mb-4">Bienvenue 🚚</h3>

      <div class="row g-3">
        <div class="col-md-4">
          <div class="card p-3">
            <!-- de maniere dynamique -->
            <h5>Livraisons assignées</h5>
            <h2>15</h2>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card p-3">
            <h5>Livraisons réussies</h5>
            <h2>12</h2>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card p-3">
            <h5>Livraisons en attente</h5>
            <h2>3</h2>
          </div>
        </div>
      </div>

      <div class="card mt-4 p-4">
        <h5>Suivi des livraisons</h5>
        <canvas id="livraisonChart"></canvas>
      </div>
    </div>
  </div>

  <script>
    const ctx = document.getElementById('livraisonChart');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
        datasets: [{
          label: 'Livraisons effectuées',
          data: [2, 3, 1, 4, 3, 2],
          backgroundColor: '#198754'
        }]
      }
    });
  </script>
</body>
</html>
