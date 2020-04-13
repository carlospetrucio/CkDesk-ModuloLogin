<?php 

	// Retornar usuário pelo ID
	function getUserById($id){
		global $db;
		$query = "SELECT * FROM usuarios WHERE idusuarios=" . $id;
		$result = mysqli_query($db, $query);

		$user = mysqli_fetch_assoc($result);
		return $user;
    }
    
	function login(){
		global $db, $nomedeusuario, $errors;

		// Dados utilizados para realizar o login
		$email = e($_POST['email']);
		$senha = e($_POST['senha']);

		// Verifica se o formulário foi preenchido corretamente
		if (empty($email)) {
			array_push($errors, "Informe o e-mail");
		}
		if (empty($senha)) {
			array_push($errors, "Informe a senha");
		}

		// Tentar fazer login se não houver erros no formulário
		if (count($errors) == 0) {
			$senha = md5($senha);

			$query = "SELECT * FROM usuarios WHERE email_usuario='$email' AND senhadousuario_usuario='$senha' LIMIT 1";
			$results = mysqli_query($db, $query);

            if (mysqli_num_rows($results) == 1) { // Usuário não encontrado
                
				// Verifica se o usuário é um usuário comúm ou um administrador do sistema
				$logged_in_user = mysqli_fetch_assoc($results);
				if ($logged_in_user['perfildousuario'] == '1') {
					$_SESSION['usuarios'] = $logged_in_user;
					$_SESSION['success']  = "You are now logged in";
					header('location: index.php');
				}else{
					$_SESSION['usuarios'] = $logged_in_user;
					$_SESSION['success']  = "You are now logged in";

					header('location: index.php');
				}
			}else {
				array_push($errors, "Combinação de nome de usuário / senha incorreta");
			}
		}
    }
    
    function isLoggedIn()
	{
		if (isset($_SESSION['usuarios'])) {
			return true;
		}else{
			return false;
		}
	}

	function isAdmin()
	{
		if (isset($_SESSION['usuarios']) && $_SESSION['usuarios']['perfildousuario'] == '1' ) {
			return true;
		}else{
			return false;
		}
    }
    
    	// escape string
	function e($val){
		global $db;
		return mysqli_real_escape_string($db, trim($val));
	}

	function display_error() {
		global $errors;

		if (count($errors) > 0){
			echo '<div class="alert alert-danger" role="alert">';
				foreach ($errors as $error){
					echo $error .'<br>';
				}
			echo '</div>';
		}
	}