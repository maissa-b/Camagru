<h1>New password page</h1>

<div class="form_block">
	<form action="http://localhost:8080/index.php?page=new_password" method="post">
		<div>
			<label>Login</label>
			<input type="text" name="username" maxlength="255">
		</div>

		<div>
			<label>New password</label>
			<input type="password" name="password" maxlength="255">
		</div>

		<div>
			<label>Confirm new password</label>
			<input type="password" name="confirm_password" maxlength="255">
		</div>

		<div>
			<input type="text" name="reset_token" style="display: none;" value="<?php echo $_GET['token'] ?>">
			<input type="text" name="reset_email" style="display: none;" value="<?php echo $_GET['email'] ?>">
		</div>

		<button type="submit">Reset password</button>

	</form>
</div>