<div class="card">
    <div class="card-body">

        <h6 class="mb-3">Paso 2 de 5 - Servicios</h6>

        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>Servicio</th>
                    <th class="text-center">Seleccionar</th>
                    <th class="text-end">Precio</th>
                </tr>
            </thead>

            <tbody id="services-body">

            <tfoot>
                <tr>
                    <td colspan="2">Subtotal</td>
                    <td class="text-end" id="subtotal">₡0.00</td>
                </tr>
                <tr>
                    <td colspan="2">IVA (13%)</td>
                    <td class="text-end" id="iva">₡0.00</td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Total</strong></td>
                    <td class="text-end"><strong id="total">₡0.00</strong></td>
                </tr>
            </tfoot>
        </table>

        <div class="d-flex justify-content-between mt-4">
            <button class="btn btn-outline-dark" onclick="prevStep()">Atrás</button>
            <button class="btn btn-dark" onclick="nextStep()">Siguiente</button>
        </div>

    </div>
</div>