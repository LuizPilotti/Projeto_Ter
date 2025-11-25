<form class="form-registro" id="form-registro" method="post">
    E-mail:
    <input type="text" name="email" id="email" class="email" placeholder="E-mail...">
    Senha:
    <input type="password" name="senha" id="senha" class="senha" placeholder="Senha">
    <input type="submit" value="Criar conta" name="registro" id="register">
    <a href="login">Já possuo conta.</a>
</form>

<div id="msg"></div>

<script>
    const form = document.getElementById('form-registro');
    const msg = document.getElementById('msg');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(form);

        const resp = await fetch('/api/usuario.php', {
            method: 'POST',
            body: formData
        });

        const data = await resp.json();

        if(data.sucesso){
            msg.textContent = 'Usuário criado com sucesso!';
        }else{
            msg.textContent = data.erro || "Erro ao registrar.";
        }
    });
</script>