<?php
session_start();
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: /Music-BOOKING/index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Equipo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include '../layouts/navbar_in.php';?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">Añadir Nuevo Equipo</div>
                <div class="card-body">
                    <?php if (isset($_SESSION['add_equipment_error'])): ?>
                        <div class="alert alert-danger"> <?php echo $_SESSION['add_equipment_error']; unset($_SESSION['add_equipment_error']); ?> </div>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['add_equipment_success'])): ?>
                        <div class="alert alert-success"> <?php echo $_SESSION['add_equipment_success']; unset($_SESSION['add_equipment_success']); ?> </div>
                    <?php endif; ?>
                    <form action="/Music-BOOKING/app/controllers/add_equipment.php" method="POST">
                        <div class="mb-3">
                            <label for="nombre_equipo" class="form-label">Nombre del Equipo</label>
                            <input type="text" class="form-control" id="nombre_equipo" name="nombre_equipo" maxlength="100" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="precio_dia" class="form-label">Precio por Día ($)</label>
                            <input type="number" class="form-control" id="precio_dia" name="precio_dia" step="0.01" min="0">
                        </div>
                        <div class="mb-3">
                            <label for="categoria" class="form-label">Categoría</label>
                            <select class="form-select" id="categoria" name="categoria">
                                <option value="">Selecciona una categoría</option>
                                <option value="Instrumento">Instrumento</option>
                                <option value="Micrófono">Micrófono</option>
                                <option value="Clabeado/conexión">Clabeado/conexión</option>
                                <option value="Amplificadores">Amplificadores</option>
                                <option value="Modulos de efectos">Modulos de efectos</option>
                                <option value="Otros">Otros</option>
                            </select>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="disponible_prestamo" name="disponible_prestamo" value="1" checked>
                            <label class="form-check-label" for="disponible_prestamo">Disponible para préstamo</label>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="/Music-BOOKING/app/views/admin/admin_home.php" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-success">Añadir Equipo</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>