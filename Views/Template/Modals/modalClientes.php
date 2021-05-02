<!-- Modal -->
<div class="modal fade" id="modalFormCliente" tabindex="-1" role="dialog" aria-hidden="true">

  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header headerRegister">
        <h5 class="modal-title">Nuevo Cliente</h5>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
              <form id="formCliente" name="formCliente" class="form-horizontal">
                <input type="hidden" name="idUsuario" id="idUsuario" value="">
                <p class="text-primary">Los campos con asteriscos (<span class="required">*</span>) son obligatorios.</p>
                

                <div class="form-row">
                  <div class="form-group col-md-4">
                    <label for="txtIdentificacion">Identificación <span class="required">*</span></label>
                    <input type="text" class="form-control" id="txtIdentificacion" name="txtIdentificacion"  required="">
                  </div>
                  <div class="form-group col-md-4">
                    <label for="txtNombre">Nombres <span class="required">*</span></label>
                    <input type="text" class="form-control valid validText" id="txtNombre" name="txtNombre" required="">
                  </div>
                  <div class="form-group col-md-4">
                    <label for="txtApellido">Apellidos <span class="required">*</span></label>
                    <input type="text" class="form-control valid validText" id="txtApellido" name="txtApellido" required="">
                  </div>
                </div>

                <div class="form-row">
                  <div class="form-group col-md-4">
                    <label for="txtTelefono">Telefono <span class="required">*</span></label>
                    <input type="text" class="form-control valid validNumber" id="txtTelefono" name="txtTelefono" required="">
                  </div>
                  <div class="form-group col-md-4">
                    <label for="txtEmail">Email <span class="required">*</span></label>
                    <input type="text" class="form-control valid validEmail" id="txtEmail" name="txtEmail" required="">
                  </div>
                  <div class="form-group col-md-4">
                    <label for="txtPassword">Password</label>
                    <input type="Password" class="form-control" id="txtPassword" name="txtPassword">
                  </div>
                </div>
                <hr>
                <p class="text-primary">Datos Fiscales</p>
                <div class="form-row">
                 
                  <div class=" form- group col-md-6">
                    <label>Identificación Tributaria <span class="required">*</span></label>
                    <input class="form-control" type="text" name="txtNit" id="txtNit" required="">
                  </div>
                  <div class="form-group col-md-6">
                    <label>Nombre Fiscal <span class="required">*</span></label>
                    <input class="form-control" type="text" name="txtNombreFiscal" id="txtNombreFiscal" required="">
                  </div>
                  <div class="form-group col-md-12">
                    <label>Dirección Fiscal <span class="required">*</span></label>
                    <input class="form-control" type="text" name="txtDirFiscal" id="txtDirFiscal" required="">
                  </div>
                </div>

                <div class="form-row">

                </div>
                
                <div class="tile-foote">
                  <button id="btnActionForm" class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i><span id="btnText">Guardar</span></button>&nbsp;&nbsp;&nbsp;

                  <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cerrar</button>&nbsp;&nbsp;&nbsp;
                </div>
              </form>
      </div>
    </div>
  </div>
</div>

<!------------------------------------------------------------------------------------------------------------------------------------------------------------------------->

<!-- Modal -->
<div class="modal fade" id="modalViewCliente" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header header-primary">
        <h5 class="modal-title" id="titleModal">Datos del cliente</h5>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <table class="table table-bordered">
            <tbody>
              <tr>
                <td>Identificacion:</td>
                <td id="celIdentificacion">7877878787</td>
              </tr>
              <tr>
                <td>Nombres:</td>
                <td id="celNombre">Luis</td>
              </tr>
              <tr>
                <td>Apellidos:</td>
                <td id="celApellidos">Morales</td>
              </tr>
              <tr>
                <td>Telefono:</td>
                <td id="celTelefono">67677677</td>
              </tr>
              <tr>
                <td>Email (Usuario):</td>
                <td id="celEmail">lemo@gmail.com</td>
              </tr>
              <tr>
                <td>Identificacion Tributaria:</td>
                <td id="celIde">CF</td>
              </tr>
              <tr>
                <td>Nombre Fiscal:</td>
                <td id="celNomFiscal">Luis</td>
              </tr>
              <tr>
                <td>Direccion Fiscal:</td>
                <td id="celDirFiscal">Ciudad</td>
              </tr>
              <tr>
                <td>Fecha registro:</td>
                <td id="celFechaRegistro">7-9-90</td>
              </tr>
            </tbody>
          </table>    
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>