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
<form id="form_gerar_relatorio_entre_periodos" class="container" method="POST" style="background-color: #dcd9d3; height: 450px; text-align: center">
    <h3 for="dodiia" class="">Selecione o periodo desejado</h3>
    <div>
        <label class="labelInputDate">Data Inicial</label>
        <input class="hidden-print inputDate" type="date" name="iniciodata" id="iniciodata" size="5" maxlength="10" />

        <label class="labelInputDate">Data Final</label>
        <input class="hidden-print inputDate" type="date" name="fimdata" id="fimdata" size="5" maxlength="10" />
        
        <label class="labelInputDate">Conteúdo</label>
        <select class="hidden-print inputTipo" name="tipo">
            <option value="quantitativo">Apenas quantitativo</option>
            <option value="completo">Militares e Quantitativo</option>
        </select>
        <p>Obs: o relatório com os militares e quantitativo poderá demorar mais...</p>
        <input type="hidden" name="acao" value="relatorio_entre_periodos">
        <button type="button" name="button" id="btn_gerar_relatorio_entre_periodos" class="btn btn-info  hidden-print" style="margin-top:10px; width:250px">Gerar relatório</button>

    </div>
</form>