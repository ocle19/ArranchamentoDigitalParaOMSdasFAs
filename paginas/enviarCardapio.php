<style>
    .inputDate {
        display: block;
        width: 250px;
        height: 40px;
        padding: .375rem .75rem;
        font-size: 2rem;
        font-weight: 400;
        line-height: 1.5;
        color: #212529;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        -webkit-appearance: none;
        -moz-appearance: none;
        margin: 0 auto;
        border-radius: .25rem;
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    }

    .inputTipo {
        display: block;
        width: 250px;
        height: 40px;
        padding: .375rem .75rem;
        font-size: 2rem;
        font-weight: 400;
        line-height: 1.5;
        color: #212529;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        margin: 0 auto;
        border-radius: .25rem;
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    }

    .labelInputDate {
        display: inline-block;
        position: relative;
        top: 10px;
        left: -90px;
        bottom: -20px;
        height: 100%;
        padding: 1rem .75rem;
        pointer-events: none;
        border: 1px solid transparent;
        transform-origin: 0 0;
        transition: opacity .1s ease-in-out, transform .1s ease-in-out;
    }
</style>
<form id="form_enviar_cardapio" class="container" method="POST" style="background-color: #dcd9d3; height: 450px; text-align: center">
    <h3 for="dodiia" class="">Enviar/atualizar o cardápio da semana</h3>
    <div>
        <label class="labelInputDate">Arquivo PDF</label>
        <input type="file" class="inputTipo" id="pdf" accept="application/pdf" />

        <label class="labelInputDate">Cardápio dos</label>
        <select class="hidden-print inputTipo" name="tipo">
            <option value="cb_sd">Cabos e Soldados</option>
            <option value="of_st_sgt">Oficiais, STs e SGTs</option>
        </select>
        <input type="hidden" name="acao" value="enviar_cardapio">
        <button type="button" name="button" id="btn_enviar_cardapio" class="btn btn-info  hidden-print" style="margin-top:10px; width:250px">Enviar cardápio</button>
        .

    </div>
    <?php if (EXIBIR_CARDAPIO) { ?>
        <br><br><center>
            <a type="button" href="<?php echo CARDAPIO_CB_SD ?>" target="A_BLANK" class="btn btn-success btn-lg btn-block" style="width:370px">VER CARDÁPIO DA SEMANA CB/SD</a>
            <a type="button" href="<?php echo CARDAPIO_OF_ST_SGT ?>" target="A_BLANK" class="btn btn-success btn-lg btn-block" style="width:370px">VER CARDÁPIO DA SEMANA OF/ST/SGT </a>
        </center>
    <?php } ?>
</form>