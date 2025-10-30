<?php
// s'assurer que la session est démarrée sans provoquer d'erreur
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Nom à afficher par défaut
$displayName = 'Utilisateur';

// n'afficher le nom que pour un artisan
if (!empty($_SESSION['role']) && $_SESSION['role'] === 'artisan') {
    if (!empty($_SESSION['nom_user'])) {
        $displayName = $_SESSION['nom_user'];
    } elseif (!empty($_SESSION['id_user'])) {
        // optionnel : récupérer depuis la BDD si le nom n'est pas en session
        require_once __DIR__ . '/../admin/config.php';
        $uid = (int) $_SESSION['id_user'];
        $res = mysqli_query($conn, "SELECT nom_user FROM users WHERE id_user = $uid LIMIT 1");
        if ($res && $r = mysqli_fetch_assoc($res)) {
            $displayName = $r['nom_user'];
        }
    }
}
?>
<div class="sidebar">
				<div class="scrollbar-inner sidebar-wrapper">
					<div class="user">
						<div class="photo">
							<img src="assets/img/woman.png" alt="photo">
						</div>
						<div class="info">
							<a class="" data-toggle="collapse" href="#collapseExample" aria-expanded="true">
								<span>
									<strong><?php echo htmlspecialchars($displayName); ?></strong>
									<br>
								</span>
							</a>
							<div class="clearfix"></div>

							<div class="collapse in" id="collapseExample" aria-expanded="true" style="">
								<ul class="nav">
									<li>
										<a href="profile.php">
											<span class="link-collapse">Mon Profil</span>
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<ul class="nav">
						<li class="nav-item active">
							<a href="index.php">
								<i class="la la-dashboard"></i>
								<p>Tableau de bord</p>
								<span class="badge badge-count"></span>
							</a>
						</li>
						<li class="nav-item">
							<a href="clients.php">
								<i class="la la-users"></i>
								<p>Clients</p>
								<span class="badge badge-count"></span>
							</a>
						</li>
						<li class="nav-item">
							<a href="artisans.php">
								<i class="la la-users"></i>
								<p>Artisans</p>
								<span class="badge badge-count"></span>
							</a>
						</li>
						<li class="nav-item">
							<a href="livreurs.php">
								<i class="la la-users"></i>
								<p>Livreurs</p>
								<span class="badge badge-count"></span>
							</a>
						</li>
						<li class="nav-item">
							<a href="articles.php">
								<i class="la la-list-alt"></i>
								<p>Articles</p>
								<span class="badge badge-count"></span>
							</a>
						</li>
						<li class="nav-item">
							<a href="categories.php">
								<i class="la la-list"></i>
								<p>Catégories</p>
								<span class="badge badge-count"></span>
							</a>
						</li>
						<li class="nav-item">
							<a href="commandes.php">
								<i class="la la-calendar-check-o"></i>
								<p>Commandes</p>
								<span class="badge badge-count"></span>
							</a>
						</li>
						<li class="nav-item">
							<a href="revenus.php">
								<i class="la la-car"></i>
								<p>Livraisons</p>
								<span class="badge badge-count"></span>
							</a>
						</li>
						<li class="nav-item">
							<a href="notifications.php">
								<i class="la la-bell"></i>
								<p>Notifications</p>
								<span class="badge badge-success"></span>
							</a>
						</li>
						<li class="nav-item">
							<a href="commentaires.php">
								<i class="la la-weixin"></i>
								<p>Commentaires</p>
								<span class="badge badge-success"></span>
							</a>
						</li>
						<li class="nav-item">
							<a href="icons.php">
								<i class="la la-fonticons"></i>
								<p>Icons</p>
							</a>
						</li>
					</ul>
				</div>
			</div>