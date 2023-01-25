<?php

session_start();
if($_SESSION['rol']!=1 )
{
	header("location: ./");
} 
	include "../conexion.php";
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"?>
	<title>Lista Usuarios</title>
</head>
<body>
	<?php include "includes/header.php"?>
	<section id="container">

		<?php
			$busqueda = $_REQUEST['busqueda'];
			if(empty($busqueda)){
				header("location: lista_usuarios.php");
				mysqli_close($conection);
			}

		?>
		<h1>Lista de usuarios</h1>
		<a href="registro_usuario.php" class="btn_new">Crear usuario</a>
		
		<form action="buscar_usuario.php" method="get" class="form_search">
			<input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php echo $busqueda;?>">
			<input type="submit" value="Buscar" class="btn_search">
		</form>
	
		<table>
			<tr>
				<th>ID</th>
				<th>Nombre</th>
				<th>Correo</th>
				<th>Usuario</th>
				<th>Rol</th>
				<th>Acciones</th>
			</tr>

			<?php

			//paginador
			$rol ='';
			if($busqueda == 'administrador')
			{
				$rol = "or rol like '%1%'";
			}else if($busqueda == 'supervisor')
			{
				$rol = "or rol like '%2%'";
			}else if($busqueda == 'Vendedor')
			{
				$rol = "or rol like '%3%'";
			}

			$sql_registe = mysqli_query($conection, "SELECT count(*) as total_registro from usuario 
													where ( idusuario like '%$busqueda%' or 
															nombre like '%$busqueda%' or
															correo like '%$busqueda%' or
															usuario like '%$busqueda%'  
															$rol )
															and	status = 1 ");
			
			$result_regsiter = mysqli_fetch_array($sql_registe);
			
			$total_registro = $result_regsiter['total_registro'];


			$por_pagina = 2;

			if(empty($_GET['pagina']))
			{
				$pagina = 1;

			}else{
				$pagina = $_GET['pagina'];
			}

			$desde = ($pagina - 1 ) * $por_pagina;
			$total_pagina = ceil($total_registro / $por_pagina);


				$query = mysqli_query($conection,"SELECT u.idusuario, u.nombre, u.correo, u.usuario, r.rol FROM usuario u inner join rol r on u.rol=r.idrol
				 WHERE (
						u.idusuario like '%$busqueda%' or 
						u.nombre like '%$busqueda%' or
						u.correo like '%$busqueda%' or
						u.usuario like '%$busqueda%' or
						r.rol like '%$busqueda%')
					AND
					status= 1 order by u.idusuario 
					limit $desde,$por_pagina");
					mysqli_close($conection);
				$result = mysqli_num_rows($query);

				if($result >0){
					while($data=mysqli_fetch_array($query)){
			?>
			<tr>
				<td><?php echo $data["idusuario"]?></td>
				<td><?php echo $data["nombre"]?></td>
				<td><?php echo $data["correo"]?></td>
				<td><?php echo $data["usuario"]?></td>
				<td><?php echo $data["rol"]?></td>
				<td>
					<a class="link_edit" href="editar_usuario.php?id=<?php echo $data["idusuario"]?>">Editar</a>

					<?php
						if($data["idusuario"] != 1){
					?>
					|
					<a class="link_delete"href="eliminar_confirmar_usuario.php?id=<?php echo $data["idusuario"]?>">Eliminar</a>
				<?php }?>
					
				</td>
			</tr>
			<?php
					}
				}
			?>
			
			
			

		</table>

		<?php
		if($total_registro != 0)
		{


		?>
		<div class="paginador">
			<ul>
				<?php
					if($pagina != 1){


				?>
				<li><a href="?pagina=<?php echo 1;?>&busqueda=<?php echo $busqueda;?>">|<</a></li>
				<li><a href="?pagina=<?php echo $pagina-1;?>"><<</a></li>
				<?php
				}
					for ($i=1; $i <= $total_pagina; $i++)
						{
							if($i == $pagina)
							{
								echo '<li class="pagesSelected">'.$i.'</li>';
							}else{
								echo '<li><a href="?pagina='.$i.'$busqueda='.$busqueda.'">'.$i.'</a></li>';
							
							}
						}
						if($pagina != $total_pagina)
						{
					?>
				<li><a href="?pagina=<?php echo $pagina+1;?>&busqueda=<?php echo $busqueda;?>">>></a></li>
				<li><a href="?pagina=<?php echo $total_pagina;?>&busqueda=<?php echo $busqueda;?>">>|</a></li>
						<?php 
						}
						?>				
			</ul>
		</div>
	<?php } ?>
	</section>
	<?php include "includes/footer.php"?>
</body>
</html>







