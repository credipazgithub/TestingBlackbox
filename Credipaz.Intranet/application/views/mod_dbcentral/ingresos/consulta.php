<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container-flex marco form-subdiario">
    <div class="form-row">
        <div class="col-12 p-2">
            <img src="./assets/credipaz/img/small.png" style="height:35px;margin-right:10px;" />
        </div>
    </div>
    <div class="form-row">
        <div class="col-6 p-1 pt-3">
            <h4><?php echo lang('m_salary_request');?></h4>
        </div>
        <div class="col-6 p-1 pt-3 m-0">
            <div class="filtros">
                <div style='padding-right:5px;display:inline-block;'>
                    <label>DNI</label> <input id="dni" name="dni" type="number" class="form-control number dni validate" />
                </div>
                <div style='padding-right:5px;display:inline-block;'>
                    <label>Sexo</label>
					<select id="sexo" name="sexo" class="form-control sexo validate">
					   <option selected value=''><?php echo lang('p_select_combo');?></option>
					   <option value='F'>Femenino</option>
					   <option value='M'>Masculino</option>
					</select>
                </div>

               <div style='padding-right:5px;display:inline-block;'>
                    <button class="btn btn-sm btn-primary btn-raised btn-execute" type="button">Buscar</button>
                </div>
            </div>
        </div>
        <div class="col-12 p-1 m-0">
            <div class="card resultados p-2" style="display:none;"></div>
        </div>
    </div>
</div>

<script>
    $.getScript('./application/views/mod_dbcentral/ingresos/consulta.js', function() {
 
	});
</script>
