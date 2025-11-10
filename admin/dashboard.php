<?php include "config.php"; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tog'Artisans - Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f5f7fa;
    }

    /* Sidebar */
    .sidebar {
      position: fixed;
      height: 100vh;
      width: 250px;
      background: linear-gradient(180deg, #0d3b2e, #146b4d);
      color: #fff;
      padding: 20px 0;
      box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }

    .sidebar h4 {
      text-align: center;
      font-weight: 600;
      margin-bottom: 30px;
    }

    .sidebar a {
      display: flex;
      align-items: center;
      padding: 12px 25px;
      color: #e9ecef;
      text-decoration: none;
      font-size: 15px;
      transition: background 0.3s ease;
    }

    .sidebar a i {
      margin-right: 10px;
      font-size: 18px;
    }

    .sidebar a:hover,
    .sidebar a.active {
      background-color: rgba(255,255,255,0.15);
      color: #fff;
    }

    .logout {
      position: absolute;
      bottom: 20px;
      width: 100%;
    }

    /* Main content */
    .content {
      margin-left: 250px;
      padding: 30px;
    }

    .topbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
    }

    .profile-box {
      display: flex;
      align-items: center;
      background: #fff;
      padding: 10px 20px;
      border-radius: 15px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .profile-box img {
      width: 45px;
      height: 45px;
      border-radius: 50%;
      margin-right: 10px;
    }

    .card {
      border: none;
      border-radius: 15px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    }

    .card h5 {
      font-weight: 600;
    }

    .btn-custom {
      background-color: #146b4d;
      color: white;
      border-radius: 10px;
      padding: 8px 18px;
    }

    .btn-custom:hover {
      background-color: #0d3b2e;
      color: #fff;
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <h4><i class="bi bi-shield-lock"></i> Admin</h4>
    <a href="dashboard.php" class="active"><i class="bi bi-speedometer2"></i> Tableau de bord</a>
    <a href="gestions.php"><i class="bi bi-gear"></i> Règles de gestion</a>
    <a href="save.php"><i class="bi bi-save"></i> Sauvegarde des données</a>
    <a href="rules.php"><i class="bi bi-people"></i> Gestion des rôles</a>
    <a href="stat.php"><i class="bi bi-bar-chart"></i> Statistiques</a>
    <a href="profile.php"><i class="bi bi-person-circle"></i> Profil</a>
    <div class="logout">
      <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
    </div>
  </div>

  <!-- Main content -->
  <div class="content">
    <div class="topbar">
      <h3 class="fw-bold text-success">TABLEAU DE BORD ADMINISTRATEUR Tog'Artisans</h3>
      <div class="profile-box">
        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Profil admin">
        <div>
          <strong>Admin Tog'Artisans</strong><br>
          <small>Superviseur système</small>
        </div>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-md-3">
        <div class="card p-3 text-center">
          <i class="bi bi-people fs-2 text-success"></i>
          <h5>Utilisateurs</h5>
					<h4><?php $sql = "SELECT COUNT(*) AS total FROM users WHERE role = 'client'";
					$result = mysqli_query($link, $sql);
					$row = mysqli_fetch_assoc($result);
					$total_users = $row['total'];
					echo $total_users; ?></h4>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card p-3 text-center">
          <i class="bi bi-truck fs-2 text-success"></i>
          <h5>Livraisons</h5>
				<h4><?php $sql = "SELECT COUNT(*) AS total FROM livraisons WHERE statut = 'livrée'";
					$result = mysqli_query($link, $sql);
						$row = mysqli_fetch_assoc($result);
							$total_livraisons = $row['total'];
								echo $total_livraisons; ?></h4>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card p-3 text-center">
          <i class="bi bi-shop fs-2 text-success"></i>
          <h5>Les boutiques</h5>
			<h4><?php $sql = "SELECT COUNT(*) AS total FROM shop ";
				$result = mysqli_query($link, $sql);
					$row = mysqli_fetch_assoc($result);
						$total_shop = $row['total'];
							echo $total_shop; ?></h4>

        </div>
      </div>
      <div class="col-md-3">
        <div class="card p-3 text-center">
          <i class="bi bi-cash-coin fs-2 text-success"></i>
          <h5>Revenus</h5>
            <h4><?php $sql = "SELECT SUM(montant) AS total_revenus FROM paiements ";
              $result = mysqli_query($link, $sql);
                $row = mysqli_fetch_assoc($result);
                  $total_revenus = $row['total_revenus'];
                    echo number_format($total_revenus, 0, ',', ' ') . ' FCFA'; ?></h4>
        </div>
      </div>
    </div>

    <!-- Graphiques -->
    <div class="card mt-5 p-4">
      <h5 class="mb-3">Statistiques des activités</h5>
      <canvas id="adminChart"></canvas>
    </div>

  <script>
    // Graphique admin
    const ctx = document.getElementById('adminChart');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
        datasets: [{
          label: 'Activité mensuelle',
          data: [120, 180, 150, 220, 300, 250],
          borderColor: '#146b4d',
          tension: 0.3,
          fill: true,
          backgroundColor: 'rgba(20,107,77,0.1)',
          pointBackgroundColor: '#146b4d'
        }]
      },
      options: {
        scales: {
          y: { beginAtZero: true }
        }
      }
    });
  </script>

</body>
</html>
