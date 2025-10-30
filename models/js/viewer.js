// --- Scene de base ---
const container = document.getElementById('viewer');

const scene = new THREE.Scene();
scene.background = new THREE.Color(0xf0f0f0);

// Camera
const camera = new THREE.PerspectiveCamera(45, container.clientWidth / container.clientHeight, 0.1, 1000);
camera.position.set(0, 1.2, 3);

// Renderer
const renderer = new THREE.WebGLRenderer({ antialias: true });
renderer.setSize(container.clientWidth, container.clientHeight);
renderer.setPixelRatio(window.devicePixelRatio);
container.appendChild(renderer.domElement);

// Lumières
const ambientLight = new THREE.AmbientLight(0xffffff, 0.8);
scene.add(ambientLight);

const dirLight = new THREE.DirectionalLight(0xffffff, 0.6);
dirLight.position.set(5, 10, 7.5);
scene.add(dirLight);

// Controls
const controls = new THREE.OrbitControls(camera, renderer.domElement);
controls.enableDamping = true;
controls.dampingFactor = 0.1;
controls.target.set(0, 0.5, 0);

// Chargement du modèle si présent
if (typeof modele3D !== 'undefined' && modele3D) {
  const loader = new THREE.GLTFLoader();
  loader.load(
    'models/' + modele3D,
    function (gltf) {
      const model = gltf.scene;
      // Centrer et ajuster l'échelle si besoin
      model.position.y = -0.5;
      model.scale.set(1.2, 1.2, 1.2);
      scene.add(model);
    },
    undefined,
    function (error) {
      console.error('Erreur de chargement du modèle 3D:', error);
    }
  );
} else {
  // Objet de secours (cube) si pas de modèle
  const geo = new THREE.BoxGeometry(1,1,1);
  const mat = new THREE.MeshStandardMaterial({ metalness:0.2, roughness:0.7 });
  const cube = new THREE.Mesh(geo, mat);
  cube.position.y = 0;
  scene.add(cube);
}

// Animation
function animate() {
  requestAnimationFrame(animate);
  controls.update();
  renderer.render(scene, camera);
}
animate();

// Gestion du redimensionnement
window.addEventListener('resize', () => {
  renderer.setSize(container.clientWidth, container.clientHeight);
  camera.aspect = container.clientWidth / container.clientHeight;
  camera.updateProjectionMatrix();
});
