<?php include 'header.php'; ?>

<!-- Ajoutez ces scripts dans le header -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/AR-js-org/AR.js@3.4.0/aframe/build/aframe-ar.js"></script>



<style>

.product-item {
  flex: 1 1 calc(33.333% - 20px); /* 3 par ligne sur desktop */
  box-sizing: border-box;
  background: #fff;
  border-radius: 10px;
  padding: 10px;
  text-align: center;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.pi-img-wrapper {
  height: 300px; /* hauteur fixe pour aligner les images */
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  border-radius: 8px;
  background: #f9f9f9;
}

.modal-3d {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.8);
}
.modal-3d-content {
    background-color: #fefefe;
    margin: 2% auto;
    padding: 20px;
    border-radius: 15px;
    width: 90%;
    max-width: 1000px;
    max-height: 90vh;
    overflow-y: auto;
}
.close-modal {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}
.close-modal:hover {
    color: #000;
}
#viewer3d-modal {
    width: 100%;
    height: 500px;
    background: #f0f0f0;
    border-radius: 10px;
}
.btn-3d-view {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white !important;
}
.btn-3d-view:hover {
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    color: white !important;
}
</style>

<body class="ecommerce">
    <div class="title-wrapper">
        <div class="container"><div class="container-inner">
            <h1><span>Nos</span>Produits</h1>
            <em>Découvrez nos produits artisanaux</em>
        </div></div>
    </div>
    
    <div class="main">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="index.html">Accueil</a></li>
                <li><a href="">Boutique</a></li>
                <li class="active">Nos produits</li>
            </ul>
            
            <div class="row margin-bottom-40">
                <div class="sidebar col-md-3 col-sm-5">
                    <ul class="list-group margin-bottom-25 sidebar-menu">
                        <li class="list-group-item clearfix"><a href="products.php?id_cat=1"><i class="fa fa-angle-right"></i>Mode & Accessoires</a></li>
                        <li class="list-group-item clearfix"><a href="products.php?id_cat=2"><i class="fa fa-angle-right"></i>Décoration & Maison</a></li>
                        <li class="list-group-item clearfix"><a href="products.php?id_cat=3"><i class="fa fa-angle-right"></i>Produits de beauté & Bien-être</a></li>
                        <li class="list-group-item clearfix"><a href="products.php?id_cat=4"><i class="fa fa-angle-right"></i>Alimentation artisanale</a></li>
                        <li class="list-group-item clearfix"><a href="products.php?id_cat=5"><i class="fa fa-angle-right"></i>Instruments de musique & Culture</a></li>
                        <li class="list-group-item clearfix"><a href="products.php?id_cat=6"><i class="fa fa-angle-right"></i>Mobilier & Ameublement</a></li>
                        <li class="list-group-item clearfix"><a href="products.php?id_cat=7"><i class="fa fa-angle-right"></i>Artisanat divers & Cadeaux</a></li>
                    </ul>
                </div>
                
                <div class="col-md-9 col-sm-7">
                    <div class="row list-view-sorting clearfix">
                        <div class="col-md-2 col-sm-2 list-view">
                            <a href="javascript:;"><i class="fa fa-th-large"></i></a>
                            <a href="javascript:;"><i class="fa fa-th-list"></i></a>
                        </div>
                    </div>
                    
                    <div class="row product-list">
<?php
$conn = mysqli_connect("localhost", "root", "", "togartisans");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$id_cat = isset($_GET['id_cat']) ? intval($_GET['id_cat']) : 0;
$where = '';
if ($id_cat > 0) {
    $where = "WHERE id_cat = $id_cat";
}

$sql = "SELECT * FROM products $where";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while($produit = mysqli_fetch_assoc($result)) {
        echo '<div class="col-md-4 col-sm-6 col-xs-12">
                <div class="product-item">
                  <div class="pi-img-wrapper">
                    <img src="'.htmlspecialchars($produit['img_product']).'" class="img-responsive" alt="'.htmlspecialchars($produit['nom_product']).'">
                    <div>
                      <a href="'.htmlspecialchars($produit['img_product']).'" class="btn btn-default fancybox-button">Zoom</a>
                      <a href="products_details.php?id_product='.htmlspecialchars($produit['id_product']).'" class="btn btn-default fancybox-fast-view">Voir</a>
                      <a href="javascript:;" onclick="open3DViewer('.htmlspecialchars($produit['id_product']).', \''.htmlspecialchars(addslashes($produit['nom_product'])).'\', \''.htmlspecialchars($produit['prix_product']).'\')" class="btn btn-default btn-3d-view">3D</a>
                    </div>
                  </div>
                  <h3><a href="products_details.php?id_product='.htmlspecialchars($produit['id_product']).'">'.htmlspecialchars($produit['nom_product']).'</a></h3>
                  <div class="pi-price">'.htmlspecialchars($produit['prix_product']).' FCFA</div>
                  <a href="add_to_cart_form.php?id_product='.htmlspecialchars($produit['id_product']).'" class="btn btn-default add2cart">Panier</a>
                </div>
              </div>';
    }
} else {
    echo "<div class='col-md-12'>Aucun produit trouvé.</div>";
}
mysqli_close($conn);
?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Visualiseur 3D -->
    <div id="modal3D" class="modal-3d">
        <div class="modal-3d-content">
            <span class="close-modal" onclick="close3DViewer()">&times;</span>
            <h2 id="modal-product-name" style="color: #667eea; margin-bottom: 20px;"></h2>
            
            <div style="margin-bottom: 15px; text-align: center;">
                <button onclick="switchViewMode('3d')" class="btn btn-default" id="btn3d" style="margin-right: 10px; background: #667eea; color: white;">📦 Vue 3D</button>
                <button onclick="switchViewMode('ar')" class="btn btn-default" id="btnAr">📱 Réalité Augmentée</button>
            </div>
            
            <div id="viewer3d-modal"></div>
            
            <div id="arViewer-modal" style="display: none; width: 100%; height: 500px; border-radius: 10px; overflow: hidden;">
                <a-scene embedded arjs="sourceType: webcam; debugUIEnabled: false;">
                    <a-box position="0 0.5 0" rotation="0 45 0" color="#FF6B6B" shadow></a-box>
                    <a-plane position="0 0 0" rotation="-90 0 0" width="4" height="4" color="#7BC8A4" shadow></a-plane>
                    <a-camera-static/>
                </a-scene>
            </div>
            
            <div style="margin-top: 20px; display: flex; gap: 15px; flex-wrap: wrap; justify-content: center;" id="controls-modal">
                <div>
                    <label style="font-weight: bold;">Couleur: </label>
                    <input type="color" id="colorPicker-modal" value="#FF6B6B" onchange="changeColorModal()" style="cursor: pointer;">
                </div>
                <div>
                    <label style="font-weight: bold;">Rotation: <span id="rotationValue-modal">0°</span></label>
                    <input type="range" id="rotationSlider-modal" min="0" max="360" value="0" oninput="updateRotationModal()" style="width: 150px;">
                </div>
                <div>
                    <label style="font-weight: bold;">Taille: <span id="scaleValue-modal">100%</span></label>
                    <input type="range" id="scaleSlider-modal" min="50" max="200" value="100" oninput="updateScaleModal()" style="width: 150px;">
                </div>
                <button class="btn btn-default" onclick="resetViewModal()" style="background: #667eea; color: white;">🔄 Réinitialiser</button>
            </div>
            
            <div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 10px;">
                <p><strong>Prix:</strong> <span id="modal-product-price"></span></p>
                <p style="margin-top: 10px;"><em>💡 Utilisez la souris pour faire pivoter le modèle 3D</em></p>
            </div>
            
            <div id="arInstructions" style="display: none; margin-top: 15px; padding: 15px; background: #fff3cd; border-radius: 10px; border: 2px solid #ffc107;">
                <h4 style="color: #856404; margin-bottom: 10px;">📱 Instructions pour la Réalité Augmentée</h4>
                <ol style="margin-left: 20px; color: #856404;">
                    <li>Autorisez l'accès à votre caméra</li>
                    <li>Pointez votre appareil vers une surface plane</li>
                    <li>Le produit apparaîtra en 3D dans votre espace</li>
                    <li>Déplacez-vous pour voir le produit sous tous les angles</li>
                </ol>
            </div>
        </div>
    </div>

    <script>
        let modalScene, modalCamera, modalRenderer, modalMesh;
        let currentProductId = null;
        let isRotating = true;
        
        function open3DViewer(productId, productName, productPrice) {
            currentProductId = productId;
            document.getElementById('modal-product-name').textContent = productName;
            document.getElementById('modal-product-price').textContent = productPrice + ' FCFA';
            document.getElementById('modal3D').style.display = 'block';
            
            setTimeout(() => {
                init3DViewerModal();
            }, 100);
        }
        
        function close3DViewer() {
            document.getElementById('modal3D').style.display = 'none';
            if (modalRenderer) {
                modalRenderer.dispose();
            }
        }
        
        function init3DViewerModal() {
            const container = document.getElementById('viewer3d-modal');
            container.innerHTML = '';
            
            modalScene = new THREE.Scene();
            modalScene.background = new THREE.Color(0xf0f0f0);
            
            modalCamera = new THREE.PerspectiveCamera(75, container.clientWidth / container.clientHeight, 0.1, 1000);
            modalCamera.position.z = 5;
            
            modalRenderer = new THREE.WebGLRenderer({ antialias: true });
            modalRenderer.setSize(container.clientWidth, container.clientHeight);
            modalRenderer.shadowMap.enabled = true;
            container.appendChild(modalRenderer.domElement);
            
            const ambientLight = new THREE.AmbientLight(0xffffff, 0.6);
            modalScene.add(ambientLight);
            
            const directionalLight = new THREE.DirectionalLight(0xffffff, 0.8);
            directionalLight.position.set(5, 5, 5);
            directionalLight.castShadow = true;
            modalScene.add(directionalLight);
            
            const geometry = new THREE.CylinderGeometry(1, 0.8, 2, 32);
            const material = new THREE.MeshPhongMaterial({ color: 0xFF6B6B, shininess: 100 });
            modalMesh = new THREE.Mesh(geometry, material);
            modalMesh.castShadow = true;
            modalScene.add(modalMesh);
            
            animateModal();
            
            let isDragging = false;
            let previousMousePosition = { x: 0, y: 0 };
            
            container.addEventListener('mousedown', () => { 
                isDragging = true; 
                isRotating = false;
            });
            
            container.addEventListener('mousemove', (e) => {
                if (isDragging && modalMesh) {
                    const deltaMove = {
                        x: e.offsetX - previousMousePosition.x,
                        y: e.offsetY - previousMousePosition.y
                    };
                    modalMesh.rotation.y += deltaMove.x * 0.01;
                    modalMesh.rotation.x += deltaMove.y * 0.01;
                }
                previousMousePosition = { x: e.offsetX, y: e.offsetY };
            });
            
            container.addEventListener('mouseup', () => { 
                isDragging = false;
                isRotating = true;
            });
        }
        
        function animateModal() {
            requestAnimationFrame(animateModal);
            if (modalMesh && isRotating) {
                modalMesh.rotation.y += 0.005;
            }
            modalRenderer.render(modalScene, modalCamera);
        }
        
        function changeColorModal() {
            if (modalMesh) {
                modalMesh.material.color.set(document.getElementById('colorPicker-modal').value);
            }
        }
        
        function updateRotationModal() {
            const value = document.getElementById('rotationSlider-modal').value;
            document.getElementById('rotationValue-modal').textContent = value + '°';
            if (modalMesh) {
                modalMesh.rotation.y = (value * Math.PI) / 180;
                isRotating = false;
            }
        }
        
        function updateScaleModal() {
            const value = document.getElementById('scaleSlider-modal').value;
            document.getElementById('scaleValue-modal').textContent = value + '%';
            if (modalMesh) {
                const scale = value / 100;
                modalMesh.scale.set(scale, scale, scale);
            }
        }
        
        function resetViewModal() {
            document.getElementById('rotationSlider-modal').value = 0;
            document.getElementById('scaleSlider-modal').value = 100;
            document.getElementById('rotationValue-modal').textContent = '0°';
            document.getElementById('scaleValue-modal').textContent = '100%';
            if (modalMesh) {
                modalMesh.rotation.set(0, 0, 0);
                modalMesh.scale.set(1, 1, 1);
            }
            isRotating = true;
        }
        
        function switchViewMode(mode) {
            const btn3d = document.getElementById('btn3d');
            const btnAr = document.getElementById('btnAr');
            
            if (mode === '3d') {
                document.getElementById('viewer3d-modal').style.display = 'block';
                document.getElementById('arViewer-modal').style.display = 'none';
                document.getElementById('controls-modal').style.display = 'flex';
                document.getElementById('arInstructions').style.display = 'none';
                
                btn3d.style.background = '#667eea';
                btn3d.style.color = 'white';
                btnAr.style.background = '';
                btnAr.style.color = '';
            } else {
                document.getElementById('viewer3d-modal').style.display = 'none';
                document.getElementById('arViewer-modal').style.display = 'block';
                document.getElementById('controls-modal').style.display = 'none';
                document.getElementById('arInstructions').style.display = 'block';
                
                btnAr.style.background = '#667eea';
                btnAr.style.color = 'white';
                btn3d.style.background = '';
                btn3d.style.color = '';
            }
        }
        
        // Fermer la modal en cliquant en dehors
        window.onclick = function(event) {
            const modal = document.getElementById('modal3D');
            if (event.target == modal) {
                close3DViewer();
            }
        }
    </script>

    <!-- BEGIN STEPS -->
    <div class="steps-block steps-block-red">
      <div class="container">
        <div class="row">
          <div class="col-md-4 steps-block-col">
            <i class="fa fa-truck"></i>
            <div>
              <h2>Livraison rapide</h2>
              <em>Livraison rapide partout</em>
            </div>
            <span>&nbsp;</span>
          </div>
          <div class="col-md-4 steps-block-col">
            <i class="fa fa-gift"></i>
            <div>
              <h2>Dons & Cadeaux</h2>
              <em>Supportez les artisans locaux</em>
            </div>
            <span>&nbsp;</span>
          </div>
          <div class="col-md-4 steps-block-col">
            <i class="fa fa-phone"></i>
            <div>
              <h2>Disponible 24h/24</h2>
              <em>Support en ligne</em>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- END STEPS -->

    <?php include 'footer.php'; ?>

    <!-- Scripts existants -->
    <script src="assets/plugins/jquery.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-migrate.min.js" type="text/javascript"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="assets/plugins/fancybox/source/jquery.fancybox.pack.js" type="text/javascript"></script>
    <script src="assets/plugins/owl.carousel/owl.carousel.min.js" type="text/javascript"></script>
    <script src="assets/corporate/scripts/layout.js" type="text/javascript"></script>
    
    <script type="text/javascript">
        jQuery(document).ready(function() {
            Layout.init();    
            Layout.initOWL();
            Layout.initTwitter();
        });
    </script>
</body>
</html>