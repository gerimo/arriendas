<?php if ($form->hasErrors()): ?>
    <div class="flash_error">
        <strong>Error</strong>: El nombre de usuario o contrase–a no son v&aacute;lidos.
    </div>
<?php endif; ?>
<div style="padding-left: 20px; width: 370px; border: solid; border-color: #2E6E9E; border-width: 1px; font-family:Verdana; color: #2E6E9E;">
<div style="text-align: left;"><h3>Acceso Backend Arriendas.cl</h3></div>
<div class="login">
        <form style="font-size:12px;" action="<?php echo url_for('@sf_guard_signin') ?>" method="post">
            <table>
            <tr><td><label class="unico" for="signin_username">
                Nombre de usuario
            </label></td><td>
            <?php echo $form['username'] ?>
            </td>
            </tr>
            <tr>
            <td>
            <label class="unico" for="signin_password">
                Contrase&ntilde;a
            </label></td>
            <td>
            <?php echo $form['password'] ?>
            </td>
            </tr>
            
            <tr>
            <td>
            <label for="signin_remember">
                Recordarme
            </label>
            </td>
            <td>
            <?php echo $form['remember']->render() ?>
            <button style="background-color: #4E9EEE;" type="submit">Iniciar Sesi&oacute;n</button>
            </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                                
                </td>
            </tr>
            </table>

            <?php echo $form['_csrf_token'] ?>
    </form>
</div>
</div>