<?php
session_start();
$isAuth = isset($_SESSION['user']);
if (!$isAuth) {
  header('Location: ../entrar');
}

$id = $_SESSION['user'];
$name = $_SESSION['name'];
$picture = $_SESSION['picture'];
$email = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link type="text/plain" rel="author" href="../humans.txt" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2.0" />
  <meta name="theme-color" content="#0070C7" />
  <title>Brainy Water - Perfil</title>

  <!-- Meta Tags -->
  <meta name="description" content="A melhor forma para você monitorar seu consumo de água e ajudar o meio ambiente." />
  <meta name="language" content="PT" />
  <meta name="keywords" content="Brainy Water, IOT, Monitoramento, Monitoramento de Água" />
  <meta name="robot" content="Index, Follow" />
  <meta name="rating" content="general" />
  <meta name="distribution" content="global" />

  <!-- iOS meta tags & icons -->
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <meta name="apple-mobile-web-app-status-bar-style" content="white" />
  <meta name="apple-mobile-web-app-title" content="Brainy Water" />
  <link rel="apple-touch-icon" sizes="192x192" href="../assets/images/icons/icon-192x192.png" />

  <!-- Manifest -->
  <link rel="manifest" href="../manifest.json" />

  <!-- Favicon -->
  <link rel="shortcut icon" href="../assets/images/icons/favicon.png" />
  <link rel="apple-touch-icon" href="../assets/images/icons/favicon.png" />

  <!-- Webfonts -->
  <link rel="dns-prefetch" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;700&family=Nunito:wght@700;900&display=swap" rel="stylesheet" />

  <!-- CSS -->
  <link rel="stylesheet" href="../assets/css/style.css" />

</head>

<body style="min-height: 100vh; display: flex; flex-direction: column;">
  <header class="header">
    <img loading="lazy" class="header__background" src="../assets/images/icons/main-header.svg" />
    <div class="header__content">
      <img loading="lazy" class="header__logout-icon" src="../assets/images/icons/logout.svg" onclick="logout();" alt="Sair" />
      <h1>Painel de Controle</h1>
      <img loading="lazy" class="header__home-icon" src="../assets/images/icons/home.svg" id="user-picture" class="header" onclick="goToControlPanel();" alt="<?php echo $name ?>" />
    </div>
  </header>

  <main class="profile">
    <div class="card">
      <h3 class="card__title">Alterar perfil</h3>
      <img class="profile__image" src="../<?php echo $picture ?>" alt="<?php echo $name ?>">

      <form style="width: 100%;" class="form form--primary" onsubmit="updateProfile(event, this);">
        <div class="input-group">
          <span>Nome</span>
          <div>
            <label for="name">
              <img loading="lazy" src="../assets/images/icons/person-icon.svg" alt="Seu nome" />
            </label>
            <input type="text" placeholder="Informe o seu nome" value="<?php echo $name ?>" name="name" id="name" />
          </div>
        </div>
        <div class="input-group">
          <span>E-mail</span>
          <div>
            <label for="email">
              <img loading="lazy" src="../assets/images/icons/email-icon.svg" alt="Seu E-mail" />
            </label>
            <input type="text" placeholder="Informe o seu E-mail" value="<?php echo $email ?>" name="email" id="email" />
          </div>
        </div>

        <button class="btn__primary" type="submit">Salvar</button>
      </form>
    </div>
    <div class="card">
      <h3 class="card__title" style="margin-bottom: 2.4rem;">Alterar senha</h3>

      <form style="width: 100%;" class="form form--primary" onsubmit="updatePassword(event, this);">
        <div class="input-group">
          <span>Senha antiga</span>
          <div>
            <label for="lock">
              <img loading="lazy" src="../assets/images/icons/lock.svg" alt="Sua senha" />
            </label>
            <input type="password" placeholder="Informe sua senha antiga" name="oldPassword" id="oldPassword" />
            <img loading="lazy" class="input-group-second-icon" src="../assets/images/icons/hide-password.svg" alt="Mostrar senha" onclick="togglePasswordVisibility('#oldPassword')" />
          </div>
        </div>
        <div class="input-group">
          <span>Senha nova</span>
          <div>
            <label for="lock">
              <img loading="lazy" src="../assets/images/icons/lock.svg" alt="Sua senha" />
            </label>
            <input type="password" placeholder="Informe sua nova senha" name="newPassword" id="newPassword" />
            <img loading="lazy" class="input-group-second-icon" src="../assets/images/icons/hide-password.svg" alt="Mostrar senha" onclick="togglePasswordVisibility('#newPassword')" />
          </div>
        </div>
        <button class="btn__primary" type="submit">Salvar</button>
      </form>
    </div>
  </main>

</body>

<script>
  if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('../service-worker.js')
      .then(reg => console.log('Service worker successfully installed 😍'))
      .catch(err => console.error('Something went wrong while trying to install the service worker 😢', err));
  }

  var busy = false;

  function togglePasswordVisibility(id) {
    var passwordInput = document.querySelector(id);
    if (passwordInput.type === "password") {
      passwordInput.type = "text";
    } else {
      passwordInput.type = "password";
    }
  }

  function logout() {
    window.location.href = '../helpers/logout.php';
  }

  function goToControlPanel() {
    window.location.href = '../painel-de-controle/';
  }

  function updatePassword(event, form) {
    if (!busy) {
      busy = true;
      event.preventDefault();
      const inputOldPassword = form.oldPassword;
      const inputNewPassword = form.newPassword;
      if (inputOldPassword && inputNewPassword) {
        const oldPassword = inputOldPassword.value;
        const newPassword = inputNewPassword.value;
        fetch('./update-password.php', {
            method: 'POST',
            headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              newPassword: newPassword,
              oldPassword: oldPassword
            })
          })
          .then(response => {
            if (response.status == 200) {
              alert('Senha alterada com sucesso.');
              inputOldPassword.value = "";
              inputNewPassword.value = "";
            } else {
              alert('Algum problema ocorreu ao tentar alterar sua senha. Tente novamente mais tarde!');
            }
          })
          .catch(error => console.error(error));
      }
      busy = false;
    }
  }

  function updateProfile(event, form) {
    if (!busy) {
      busy = true;
      event.preventDefault();
      const inputName = form.name;
      const inputEmail = form.email;
      if (inputName && inputEmail) {
        console.log(inputName, inputEmail);
        const name = inputName.value;
        const email = inputEmail.value;
        if (email != "<?php echo $email ?>" || name != "<?php echo $name ?>") {
          fetch('./update-email-and-name.php', {
              method: 'POST',
              headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
              },
              body: JSON.stringify({
                email: email,
                name: name
              })
            })
            .then(response => {
              if (response.status == 200) {
                alert('Nome e Email alterados.');
              } else {
                alert('Algum problema ocorreu ao tentar alterar seus dados. Tente novamente mais tarde!');
              }
            })
            .catch(error => console.error(error));
        }
      }
      busy = false;
    }
  }
</script>

</html>