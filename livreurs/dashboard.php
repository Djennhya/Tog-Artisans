<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tog'Artisans - Tableau de bord Livreur</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      background-color: #f5f6fa;
      font-family: 'Poppins', sans-serif;
    }

    /* --- Sidebar --- */
    .sidebar {
      height: 100vh;
      width: 240px;
      background: linear-gradient(180deg, #198754, #38b000);
      position: fixed;
      color: white;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      padding-top: 20px;
    }

    .sidebar h4 {
      text-align: center;
      margin-bottom: 30px;
      font-weight: 600;
    }

    .sidebar a {
      color: white;
      text-decoration: none;
      padding: 14px 20px;
      display: flex;
      align-items: center;
      gap: 10px;
      transition: background 0.3s;
    }

    .sidebar a:hover, .sidebar a.active {
      background: rgba(255,255,255,0.15);
      border-left: 4px solid #fff;
    }

    .logout {
      background: rgba(0,0,0,0.15);
      text-align: center;
      padding: 12px 0;
      border-top: 1px solid rgba(255,255,255,0.2);
    }

    /* --- Contenu --- */
    .content {
      margin-left: 240px;
      padding: 30px 40px;
    }

    .topbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 25px;
    }

    .search-bar {
      width: 55%;
    }

    .card {
      border: none;
      border-radius: 15px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.07);
    }

    .card h5 {
      color: #198754;
      font-weight: 600;
    }

    /* --- Profil --- */
    .profile-section {
      display: none;
      background: white;
      border-radius: 15px;
      padding: 30px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .profile-section.active {
      display: block;
    }

    .profile-pic {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      object-fit: cover;
      border: 4px solid #198754;
    }

    .btn-save {
      background: linear-gradient(135deg, #198754, #38b000);
      color: white;
      font-weight: 500;
      border: none;
    }

    .btn-save:hover {
      opacity: 0.9;
    }
  </style>
</head>

<body>
  <div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar">
      <div>
        <h4><i class="bi bi-truck"></i> Livreur</h4>
        <a href="dashboard.php" class="active" onclick="showSection('dashboard')"><i class="bi bi-speedometer2"></i> Tableau de bord</a>
        <a href="profile.php" onclick="showSection('profile')"><i class="bi bi-person-circle"></i> Profil</a>
        <a href="livraison_assignee.php"><i class="bi bi-box-seam"></i> Livraisons assignées</a>
        <a href="historique.php"><i class="bi bi-clock-history"></i> Historique</a>
      </div>
      <div class="logout">
        <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
      </div>
    </div>

    <!-- Contenu principal -->
    <div class="content flex-grow-1">
      <div class="topbar">
        <input type="text" class="form-control search-bar" placeholder="Rechercher une livraison...">
        <div class="d-flex align-items-center">
          <span class="me-2 fw-semibold text-success">Ali Livreur</span>
          <img src="https://cdn-icons-png.flaticon.com/512/147/147144.png" alt="profil" width="45" class="rounded-circle">
        </div>
      </div>

      <!-- Tableau de bord -->
      <div id="dashboard" class="dashboard-section">
        <h3 class="mb-4 fw-bold text-success">Bienvenue, Ali 🚚</h3>

        <div class="row g-3">
          <div class="col-md-4">
            <div class="card p-3 text-center">
              <h5>Livraisons assignées</h5>
              <h2>15</h2>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card p-3 text-center">
              <h5>Livraisons réussies</h5>
              <h2>12</h2>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card p-3 text-center">
              <h5>Livraisons en attente</h5>
              <h2>3</h2>
            </div>
          </div>
        </div>
        <br>
        <div class="col-md-4">
            <div class="card p-3 text-center">
              <h5>Mes revenus</h5>
              <h2>30.000 FCFA</h2>
            </div>
          </div>
        </div>

        <div class="card mt-4 p-4">
          <h5>📊 Suivi des livraisons</h5>
          <canvas id="livraisonChart"></canvas>
        </div>
      </div>

      <!-- Profil Livreur -->
      <div id="profile" class="profile-section">
        <h4 class="mb-3 text-success"><i class="bi bi-person-circle"></i> Mon profil</h4>
        <div class="text-center mb-4">
          <img src="https://cdn-icons-png.flaticon.com/512/147/147144.png" alt="profil" class="profile-pic mb-3">
          <br>
          <button class="btn btn-outline-success btn-sm">Changer la photo</button>
        </div>
        <form>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Nom complet</label>
              <input type="text" class="form-control" value="Ali Livreur">
            </div>
            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" value="ali.livreur@togartisans.com">
            </div>
            <div class="col-md-6">
              <label class="form-label">Téléphone</label>
              <input type="text" class="form-control" value="+236 70000000">
            </div>
            <div class="col-md-6">
              <label class="form-label">Mot de passe</label>
              <input type="password" class="form-control" placeholder="********">
            </div>
          </div>

          <div class="text-center mt-4">
            <button type="button" class="btn btn-save px-4 py-2"><i class="bi bi-save"></i> Enregistrer les modifications</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Chart -->
  <script>
    const ctx = document.getElementById('livraisonChart');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
        datasets: [{
          label: 'Livraisons effectuées',
          data: [2, 3, 1, 4, 3, 2],
          backgroundColor: '#198754',
          borderRadius: 8
        }]
      },
      options: {
        scales: {
          y: { beginAtZero: true }
        }
      }
    });

    // Gestion de l'affichage des sections
    function showSection(section) {
      document.querySelectorAll('.dashboard-section, .profile-section').forEach(el => el.classList.remove('active'));
      document.getElementById(section).classList.add('active');
      document.querySelectorAll('.sidebar a').forEach(a => a.classList.remove('active'));
      event.target.classList.add('active');
    }
  </script>
</body>
</html>
