<?php
// $dados['usuarioTipo'] vem do controller
$usuarioTipo = $dados['usuarioTipo'] ?? 'paciente';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="public/css/ajuda.css">
  <title>Ajuda</title>
</head>

<body>

  <header class="header">
    <div class="logo">
      <img src="public/assets/img/estetoscópio-agendmed.png" alt="logo AgendMed">
      <span>AgendMed</span>
    </div>

    <nav class="menu">
      <a href="#"><img src="public/assets/img/house.png" alt="Início">Início</a>
      <a href="#"><img src="public/assets/img/user.png" alt="Perfil">Perfil</a>
      <a href="#"><img src="public/assets/img/folder (2).png">Serviços</a>
      <a href="#"><img src="public/assets/img/about us.png" alt="Sobre nós">Sobre nós</a>
      <a href="index.php?url=ajuda/index"><img src="public/assets/img/help.png" alt="Ajuda">Ajuda</a>
      <a href="index.php?url=logout/index"><img src="public/assets/img/exit.png" alt="Sair">Sair</a>
    </nav>
  </header>

  <div class="container">

    <div class="form-image">
      <img src="public/assets/img/undraw_respond_o54z.svg" alt="Ajuda e Suporte">
    </div>

    <div class="form">
      <div class="form-header">
        <h1>Central de Ajuda</h1>
      </div>

      <p class="descricao">
        Precisa de suporte? Aqui você encontra respostas rápidas e pode falar diretamente com nossa equipe.
      </p>

      <div class="faq">
        <h2>Perguntas Frequentes</h2>
        <ul>
          <li><strong>Esqueci minha senha, o que devo fazer?</strong> Clique em "Esqueci a senha" na tela de login.</li>
          <li><strong>Onde altero meus dados?</strong> Na área de perfil.</li>
          <li><strong>Como agendar uma consulta?</strong> Área do paciente → Agendar Consulta.</li>
          <li><strong>Como cancelar uma consulta?</strong> Vá em agendamentos e clique em cancelar.</li>
          <li><strong>Erro no agendamento?</strong> Cancele e refaça com dados corretos.</li>
          <li><strong>Posso marcar exames?</strong> Ainda não. Futuro promissor.</li>
        </ul>
      </div>

      <div class="help-button">
        <a href="https://wa.me/558488413075" target="_blank">
          Falar no WhatsApp
        </a>
      </div>

    </div>
  </div>

</body>

</html>