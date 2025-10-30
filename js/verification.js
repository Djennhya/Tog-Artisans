document.addEventListener('DOMContentLoaded', function() {

  const emailForm = document.getElementById('emailForm');
  const codeForm = document.getElementById('codeForm');
  const responseDiv = document.getElementById('response');

  // 📨 Envoi du code à l'email
  emailForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const email = document.getElementById('email').value;

    const res = await fetch('send_code.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ email })
    });

    const data = await res.json();
    responseDiv.innerHTML = data.message;

    if (data.status === 'success') {
      emailForm.style.display = 'none';
      codeForm.style.display = 'block';
    }
  });

  // ✅ Vérification du code
  codeForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const code = document.getElementById('code').value;

    const res = await fetch('verify_code.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ code })
    });

    const data = await res.json();
    responseDiv.innerHTML = data.message;

    if (data.status === 'success') {
      codeForm.style.display = 'none';
    }
  });

});
