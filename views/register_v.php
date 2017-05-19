<h1>Register page</h1>

<div class="form_block">
	<form action="http://localhost:8080/index.php?page=register" method="post">
		<div>
			<label>Login</label>
			<input type="text" name="username" maxlength="255">
		</div>

		<div>
			<label>Email</label>
			<input type="text" name="email" maxlength="255">
		</div>

		<div>
			<label>Password</label>
			<input type="password" name="password" maxlength="255">
		</div>

		<div>
			<label>Confirm password</label>
			<input type="password" name="confirm_password" maxlength="255">
		</div>

		<button type="submit">Send registation mail</button>

	</form>
</div>