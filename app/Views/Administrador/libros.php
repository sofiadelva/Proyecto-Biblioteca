<?php echo $this->extend('plantilla'); ?>

<?php $this->section('contenido'); ?>

<h3 class="my-3" id="titulo">Libros</h3>

            <a href="<?=base_url('libros/new'); ?>" class="btn btn-success">Agregar</a>

            <table class="table table-hover table-bordered my-3" aria-describedby="titulo">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Título</th>
                        <th scope="col">Autor</th>
                        <th scope="col">Editorial</th>
                        <th scope="col">Cantidad Total</th>
                        <th scope="col">Cantidad Disponibles</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Categoría</th>
                        <th scope="col">Opciones</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>12345</td>
                        <td>JUAN PEREZ</td>
                        <td>0123456789</td>
                        <td>JUANPEREZ@DOMINIO.COM</td>
                        <td>RECURSOS HUMANOS</td>
                        <td>
                            <a href="edita.html" class="btn btn-warning btn-sm me-2">Editar</a>

                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                data-bs-target="#eliminaModal" data-bs-id="1">Eliminar</button>
                        </td>
                    </tr>

                </tbody>
            </table>





<?php $this->endsection(); ?>