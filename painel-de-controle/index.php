<?php
session_start();
$isAuth = isset($_SESSION['user']);
if (!$isAuth) {
  header('Location: ../entrar');
}

$id = $_SESSION['user'];
$name = $_SESSION['name'];
$picture = $_SESSION['picture'];
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link type="text/plain" rel="author" href="../humans.txt" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2.0" />
  <meta name="theme-color" content="#0070C7" />
  <title>Brainy Water - Painel de Controle</title>

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

<body onload="startup();" style="min-height: 100vh; display: flex; flex-direction: column;">
  <header class="header">
    <img class="header__background" src="../assets/images/icons/main-header.svg" />
    <div class="header__content">
      <img class="header__logout-icon" src="../assets/images/icons/logout.svg" onclick="logout();" alt="Sair" />
      <h1>Painel de Controle</h1>
      <img class="header__profile-pic" src="../<?php echo $picture ?>" id="user-picture" class="header" onclick="goToProfile();" alt="<?php echo $name ?>" />
    </div>
  </header>

  <main class="control-panel">

  </main>

</body>

<script>
  // <img src = "../assets/images/icons/email.svg" / >
  function showModal(id, img, value) {
    let markup = `<div class="modal" id="modal" style="display: block">
                  <div class="modal__container">
                    <div class="modal__header">
                      <h2 class="subtitle">Alterar nome</h2>
                    </div>
                    <div class="modal__content">
                      <form class="form" style="margin: 2.4rem 0;">
                        <div class="input-group">
                          <span>Nome do sensor</span>
                          <div>
                            <label for="name">
                              ${img == 1 ? '<img src="../assets/images/icons/reservoir-blue.svg" />' : '<img src="../assets/images/icons/flow-blue.svg" />'}
                            </label>
                            <input type="text" value="${value}" name="sensor-name" id="sensor-name" />
                          </div>
                        </div>
                      </form>
                    </div>
                    <div class="modal__footer">
                      <button style="height: 4.8rem; margin-right: 1.2rem;" onclick="closeModal(1)" class="btn__outline" type="button">Cancelar</button>
                      <button style="height: 4.8rem; margin-left: 1.2rem;" onclick="updateSensorName(${id}, '${value}')" class="btn__primary" type="button">Alterar</button>
                    </div>
                  </div>
                </div>`;
    body = document.getElementsByTagName('body')[0];
    body.insertAdjacentHTML('beforeend', markup);
  }

  function closeModal() {
    var modal = document.querySelector("#modal");
    modal.remove();
  }

  window.onclick = function(event) {
    var modal = document.querySelector("#modal");
    if (event.target == modal) {
      modal.remove();
    }
  }

  var homeContainer = document.querySelector('.control-panel');
  var busy = false;

  function logout() {
    window.location.href = './logout.php';
  }

  function goToProfile() {
    window.location.href = '../perfil/';
  }

  function startup() {
    getUserMessages();
  }

  function getUserMessages() {
    fetch('./messages.json').then(response => {
      return response.json();
    }).then(data => {
      var randomNumber = getRandomArbitrary(1, data.length);
      var message = data[randomNumber - 1].message;
      let markup = `<h2 class="control-panel__title">
                      ${message} <br/>
                      <span class="u-text-black u-text-display"><?php echo $name ?></span>
                    </h2>`;
      homeContainer.insertAdjacentHTML('afterbegin', markup);
    }).catch(err => {
      console.error(err);
    });
    getTips();
  }

  function getTips() {
    fetch('./tips.json').then(response => {
      return response.json();
    }).then(data => {
      var randomNumber = getRandomArbitrary(1, data.length);
      var message = data[randomNumber - 1].description;
      let markup = `<div class="tip">
                      <div class="tip__header">
                        <img class="tip__lamp" src="../assets/images/icons/lamp.svg" />
                        <span style="margin-left: 0.8rem;">Dica</span>
                        <img onclick="closeTip();" class="tip__close" src="../assets/images/icons/close.svg" />
                      </div>
                      <p>${message}</p>
                    </div>`;
      homeContainer.insertAdjacentHTML('beforeend', markup);
    }).catch(err => {
      console.error(err);
    });
    getSensors();
  }

  function removeAllSensors() {
    var sensors = document.querySelectorAll('.sensor');
    sensors.forEach(sensor => {
      sensor.remove();
    });
  }

  function updateSensorName(id, value) {
    if (!busy) {
      busy = true;
      const inputName = document.querySelector("#sensor-name");
      if (inputName) {
        const name = inputName.value;
        if (name == value) {
          alert('O nome do sensor precisa ser diferente para ser alterado.');
        } else {
          fetch('./update-sensor.php', {
              method: 'POST',
              headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
              },
              body: JSON.stringify({
                sensor: id,
                name: name
              })
            })
            .then(response => {
              if (response.status == 200) {
                removeAllSensors();
                getSensors();
                closeModal();
              } else {
                alert('Algum problema ocorreu ao tentar alterar o nome do sensor. Tente novamente mais tarde!');
              }
            })
            .catch(error => console.error(error));
        }
      }
    }
    busy = false;
  }

  function closeTip() {
    document.querySelector('.tip').remove();
  }

  function getRandomArbitrary(min, max) {
    return Math.floor((Math.random() * max) + min);
  }

  function getSensors() {
    if (!busy) {
      busy = true;
      fetch('./get-sensors.php')
        .then(response => response.json())
        .then(response => {
          if (response.status == 200) {
            response.sensors.forEach(sensor => {
              var type = sensor.type == 'caixa' ? 1 : 2;
              let markup = `<div class="sensor ${type == 1 ? 'sensor--secondary' : ''}" >
                  <div class="sensor__header">
                    ${type == 1 ? '<img class="sensor__icon" src="../assets/images/icons/reservoir.svg" />' : '<img class="sensor__icon" src="../assets/images/icons/flow.svg" />'}
                    <span style="margin-left: 0.8rem;">${sensor.name}</span>
                    <img class="sensor__edit" src="../assets/images/icons/pencil.svg" onclick="showModal(${sensor.id}, ${type}, '${sensor.name}')" />
                  </div>
                  <h3>Última leitura (${sensor.date}):</h3>
                  <div class=" sensor__content">
                    <img class="sensor__droplet" src="../assets/images/icons/droplet.svg" />
                    <span style="margin-left: 0.8rem;">${sensor.last_reading} litros</span>
                  </div>
                </div>`;
              homeContainer.insertAdjacentHTML('beforeend', markup);
            });
          } else {
            alert('Algum problema ocorreu ao tentar carregar os sensores. Tente novamente mais tarde!');
          }
        })
        .catch(error => console.error(error));
    }
    busy = false;
  }
</script>

</html>