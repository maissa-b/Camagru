<h1>Login page</h1>

<div class="form_block">
	<form action="http://localhost:8080/index.php?page=login" method="post">
		<div>
			<label>Login</label>
			<input type="text" name="username" maxlength="255">
		</div>

		<div>
			<label>Password</label>
			<input type="password" name="password" maxlength="255">
		</div>
		<span><button type="submit">Log-in !</button></span>
	</form>
	<form action="http://localhost:8080/index.php?page=reset_password" method="post">
		<button type="submit">Did you forget your password?</button>	
	</form>
</div>