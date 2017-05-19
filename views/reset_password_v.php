<h1>Reset password page</h1>

<div class="form_block">
	<form action="http://localhost:8080/index.php?page=reset_password" method="post">
			<div>
				<label>Mail</label>
				<input type="text" name="email" maxlength="255">
			</div>

			<button type="submit" class="btn btn-primary"> Send re-init password</button>
	</form>
</div>