   document.addEventListener("DOMContentLoaded", function () {

      var producto;
      MostrarProductos();

      //Boton que vuelve a la página principal
      $('#btnFinalizar').click(function () {
        window.location = '../index.php';
      });

      //Boton que muestra el diálogo de agregar
      $('#btnAgregar').click(function () {
        LimpiarFormulario();
        $('#btnConfirmarAgregar').prop("disabled", false);
        $('#btnConfirmarAgregar').show();
        $('#btnModificar').hide();
        $('#btnBorrar').hide();
        $("#ModalEditar").modal();
      });


      //Botones que permiten agregar, borrar y modificar una fila de la tabla.
      $('#btnConfirmarAgregar').click(function () {
        RecolectarDatosFormulario();
        if (!EntradaFormularioCorrecto())
          return;
        $("#ModalEditar").modal('hide');
        EnviarInformacion("agregar");
      });

      $('#btnBorrar').click(function () {
        $("#ModalEditar").modal('hide');
        $("#ModalConfirmarBorrar").modal();
      });

      $('#btnConfirmarBorrado').click(function () {
        $("#ModalConfirmarBorrar").modal('hide');
        RecolectarDatosFormulario();
        $("#ModalEditar").modal('hide');
        EnviarInformacion("borrar");
      });

      $('#btnModificar').click(function () {
        RecolectarDatosFormulario();
        if (!EntradaFormularioCorrecto())
          return;
        $("#ModalEditar").modal('hide');
        EnviarInformacion("modificar");
      });
      //******************************************************* 

      function MostrarProductos() {
        $.ajax({
          type: 'GET',
          url: 'procesar.php?accion=listar',
          success: function (productos) {
          
            let filas = '';
            for (let producto of productos) {

              filas += '<tr><td>' + producto[1] + '</td><td>' + producto[2] + '</td><td>' + producto[3] + '</td><td>' + producto[4] + '</td>';
              filas += '<td><a class="btn btn-primary botoneditar" role="button" href="#" data-codigo="' + producto[0] + '">Edita?</a> </td></tr>';
            }
            $('#datos').html(filas);
            //Boton que muestra el diálogo de modificar y borrar
            $('.botoneditar').click(function () {
              $('#Codigo').val($(this).get(0).dataset.codigo);
              
              RecuperarProducto("recuperar");
              $('#btnConfirmarAgregar').hide();
              $('#btnModificar').show();
              $('#btnBorrar').show();
              $("#ModalEditar").modal();
            });

          },
          error: function () {
            alert("hay un error...")
          }
        });
      }

      



      //Funciones AJAX para enviar y recuperar datos del servidor
      //******************************************************* 
      function EnviarInformacion(accion) {
        $.ajax({
          type: 'POST',
          url: 'procesar.php?accion=' + accion,
          data: producto,
          success: function (msg) {
            MostrarProductos();
          },
          error: function () {
            location.reload();
          }
        });
      }

      function RecuperarProducto(accion, producto=null) {
        $.ajax({
          type: 'POST',
          url: 'procesar.php?accion=' + accion,
          data: RecolectarDatosFormulario(),
          success: function (datos) {
            $('#id_producto').val(datos[0][0])
            $('#Codigo').val(datos[0][1]);
            $('#Descripcion').val(datos[0][2]);
            $('#CodigoCategoria').val(datos[0][3]);
            $('#Precio').val(datos[0][4]);

          
          },
          error: function () {
            alert("Hay un error ..");
          }
        });
      }
      //******************************************************* 

      function RecolectarDatosFormulario() {
         producto = {
          id_codigo: $('#id_producto').val()===undefined?'':$('#id_producto').val(),
          nombre: $('#Codigo').val(),
          descripcion: $('#Descripcion').val(),
          precio: $('#Precio').val(),
          cantidad: $('#CodigoCategoria').val()
        };
        return producto;
      }

      function LimpiarFormulario() {
        $('#Codigo').val('');
        $('#Descripcion').val('');
        $('#Precio').val('');
        $('#CodigoCategoria').val('');
      }

      function EntradaFormularioCorrecto() {
        if (producto['descripcion'] == '') {
          alert("No Puede estar vacía la descripción");
          return false;
        }
        if (producto['precio'] == '') {
          alert("No Puede estar vacío el precio");
          return false;
        }
        return true;
      }

     

    });    