<?php
	class User
	{
		public function isAuthenticated()
		{
			if(!empty($_SESSION['user_id'])):
				return true;
			else:
				return false;
			endif;
		}
		public function Logout()
		{
			
			session_destroy();
			session_start();
		

			echo '<div class="modal fade" id="smallModal" tabindex="-1" role="dialog" aria-labelledby="smallModalLabel" aria-hidden="true">
										<div class="modal-dialog modal-sm">
											<div class="modal-content">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
													<h4 class="modal-title" id="smallModalLabel">Notifikácia</h4>
												</div>
												<div class="modal-body">
													<p>Boli ste úspešne odhlásený.</p>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-default" data-dismiss="modal">Zavrieť</button>
												</div>
											</div>
										</div>
									</div>';
			echo "<script>$(function () {
					$('#smallModal').modal('show');
					});</script>";
			
		}
		public function isAdmin()
		{
			if($this->isAuthenticated() and $_SESSION['admin']==1){
				return true;
			}
		}

		public function isUser()
		{
			if($this->isAuthenticated() and $_SESSION['admin']==0){
				return true;
			}
		}

		public function returnUserLogin($user_id) {
			if($user_id == 0)
				return "Anonýmný používateľ";
			$queryString = "select username from user where 1 and id = '" . $user_id . "' limit 1;";		
			$ResultQ = mysqli_query($connect,$queryString);
			if($ResultQ) {
				if(mysqli_num_rows($ResultQ) == 1) {
					$RowQ = mysqli_fetch_assoc($ResultQ);
					return $RowQ['username'];
				}
				else
					return false;
			}
		}
		
				public function returnUserFullname($user_id,$connect) {
			if($user_id == 0)
				return "Anonýmný používateľ";
			$queryString = "select fullname from user where 1 and id = '" . $user_id . "' limit 1;";		
			$ResultQ = mysqli_query($connect,$queryString);
			if($ResultQ) {
				if(mysqli_num_rows($ResultQ) == 1) {
					$RowQ = mysqli_fetch_assoc($ResultQ);
					return $RowQ['fullname'];
				}
				else
					return false;
			}
		}
		
		public function Authenticate($username=NULL, $passphrase=NULL,$connect)
		{
			$queryString = "select osobne_cislo from pouzivatelia where 1 AND login = '" . mysqli_real_escape_string($connect,$username) . "' AND heslo = '" . mysqli_real_escape_string($connect,$passphrase) . "' ;";		
			$ResultQ = mysqli_query($connect,$queryString);
			if($ResultQ){//000
				if(mysqli_num_rows($ResultQ)==1){//001
					$RowQ = mysqli_fetch_assoc($ResultQ);
					//	overime si heslo
					$queryString = "select * from pouzivatelia where 1 and osobne_cislo = '" . $RowQ['osobne_cislo'] . "' and heslo = '" . mysqli_real_escape_string($connect,$passphrase) . "' limit 1;";
					$ResultR = mysqli_query($connect,$queryString);
					if($ResultR){//002
						if(mysqli_num_rows($ResultR)==1){//003
							$RowR = mysqli_fetch_assoc($ResultR);
							
							
							
							//	nastavime session
							$_SESSION['user_id'] = $RowR['osobne_cislo'];
							$_SESSION['prava'] = $RowR['prava'];
							
							$_SESSION['username'] = $RowR['login'];
							
							
						}//003
						else{//003b
						echo '<div class="modal fade" id="smallModal" tabindex="-1" role="dialog" aria-labelledby="smallModalLabel" aria-hidden="true">
										<div class="modal-dialog modal-sm">
											<div class="modal-content">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
													<h4 class="modal-title" id="smallModalLabel">Notifikácia</h4>
												</div>
												<div class="modal-body">
													<p>Bolo zadané nesprávne heslo.</p>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-default" data-dismiss="modal">Zavrieť</button>
												</div>
											</div>
										</div>
									</div>';
			echo "<script>$(function () {
					$('#smallModal').modal('show');
					});</script>";
						}//003b
					}//002
                       
					else{//002b
						print(mysqli_error());
					}//002b
				}//001
				else{//001b
					
			echo '<div class="modal fade" id="smallModal" tabindex="-1" role="dialog" aria-labelledby="smallModalLabel" aria-hidden="true">
										<div class="modal-dialog modal-sm">
											<div class="modal-content">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
													<h4 class="modal-title" id="smallModalLabel">Notifikácia</h4>
												</div>
												<div class="modal-body">
													<p>Zadaný užívateľ sa nenachádza v databáze užívateľov alebo ma zakázaný prístup.</p>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-default" data-dismiss="modal">Zavrieť</button>
												</div>
											</div>
										</div>
									</div>';
			echo "<script>$(function () {
					$('#smallModal').modal('show');
					});</script>";
				}//001b
			}//000
			else{
				print(mysqli_error());
			}
			
			
			/*
			$queryString = "select id from user where 1 email = '" . mysqli_real_escape_string($connect,$username) . "' and active = '1';";		
			$ResultQ = mysqli_query($connect,$queryString);
			if($ResultQ):
				if(mysqli_num_rows($ResultQ)==1):
					$RowQ = mysqli_fetch_assoc($ResultQ);
					
					//	overime si heslo
					echo $queryString = "select * from user where 1 and id = '" . $RowQ['id'] . "' and pwd = '" . mysqli_real_escape_string($connect,$passphrase) . "' limit 1;";
					$ResultR = mysqli_query($connect,$queryString);
					if($ResultR):
						if(mysqli_num_rows($ResultR)==1):
							$RowR = mysqli_fetch_assoc($ResultR);
							
							
							
							//	nastavime session
							$_SESSION['user_id'] = $RowR['id'];
							$_SESSION['fullname'] = $RowR['fullname'];
							$_SESSION['admin'] = $RowR['admin'];
							
							$_SESSION['email'] = $RowR['email'];
							
							if($_SESSION['admin'] == 1 ) { 
									header("Location:" . ROOTDIR . "/setup/index.php?module=menu"); 
									exit; 
							}
							elseif($_SESSION['admin'] == 0 ) { 
								header("Location:" . ROOTDIR . "/"); 
								$_SESSION['_message'] = "Boli ste úspešne prihlásený."; 
								exit; 
							}
							else { $_SESSION['_message'] = "Chyba v prihlasovaní.";  }
						
						else:
							$_SESSION['_message'] = "Bolo zadané nesprávne heslo.";
						endif;
					else:
						print(mysql_error());
					endif;
				else:
					$_SESSION['_message'] = "Zadaný užívateľ sa nenachádza v databáze užívateľov alebo ma zakázaný prístup.";					
				endif;			
			else:
				print(mysqli_error());
			endif;
			*/
		}
	}
?>
