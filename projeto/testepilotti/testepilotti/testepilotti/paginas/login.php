<form class="form-login" id="form-login" method="post">
    E-mail:
    <input type="text" name="email" id="email" class="email" placeholder="E-mail...">
    Senha:
    <input type="password" name="senha" id="senha" class="senha" placeholder="Senha">
    <input type="submit" value="Entrar" name="login">
    <span>Caso não tenha conta. <a href="registro">Registre-se.</a></span>
</form>

<div id="msg"></div>

<script>
    const form = document.getElementById('form-login');
    const msg = document.getElementById('msg');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(form);

        const resp = await fetch('/api/login.php', {
            method: 'POST',
            body: formData
        });

        const data = await resp.json();

        if(data.sucesso){
            msg.textContent = 'Login realizado com sucesso!';
            window.location.href = "/home";

        }else{
            msg.textContent = data.erro || "Erro ao registrar.";
        }
    });
</script>