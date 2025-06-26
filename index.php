<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Calculadora IMC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .invalid-feedback {
            display: none;
        }
        input.is-invalid + .invalid-feedback {
            display: block;
        }
    </style>
</head>
<body class="container mt-4">

<h4 class="alert alert-info text-center">Calculadora de IMC</h4>
<div class="row">
    <div class="col-md-4">
        <form method="GET" id="formIMC" novalidate>
            <div class="mb-3">
                <label for="height" class="form-label">Agrega tu altura (m)</label>
                <input type="text" name="height" id="height" class="form-control" required inputmode="decimal"
                    value="<?php echo isset($_GET['height']) ? htmlspecialchars($_GET['height']) : ''; ?>">
                <div class="invalid-feedback">Solo se permiten números positivos (ej. 1.65)</div>
            </div>
            <div class="mb-3">
                <label for="weight" class="form-label">Agrega tu peso (kg)</label>
                <input type="text" name="weight" id="weight" class="form-control" required inputmode="decimal"
                    value="<?php echo isset($_GET['weight']) ? htmlspecialchars($_GET['weight']) : ''; ?>">
                <div class="invalid-feedback">Solo se permiten números positivos (ej. 60.5)</div>
            </div>
            <div class="mb-3">
                <input type="submit" value="Calcular" class="btn btn-success w-100">
            </div>
        </form>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Resultados</div>
            <div class="card-body">
                <?php
                $mostrarImagen = false;
                $imgPath = "";
                if (isset($_GET["height"]) && isset($_GET["weight"])) {
                    $height = floatval($_GET["height"]);
                    $weight = floatval($_GET["weight"]);

                    if ($height > 0 && $weight > 0) {
                        $imc = round($weight / ($height * $height), 2);

                        $categorias = [
                            ['max' => 18.49, 'info' => 'Tu peso es bajo', 'img' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTAU0qpJuO5Y0qhd84b4bCyfIG_D0hgPRa3og&s'],
                            ['max' => 24.99, 'info' => 'Tu peso es normal', 'img' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSsOs-AhTINbZY_pmzKNQglMcowNXXfaNVVjA&s'],
                            ['max' => 29.99, 'info' => 'Tienes sobrepeso', 'img' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS6u3L3xBt2O_9zGRkBwLBcPWZxjaUfd4LLjg&s'],
                            ['max' => 34.99, 'info' => 'Tienes obesidad nivel 1', 'img' => 'https://manmedicalinstitute.com/wp-content/uploads/2021/04/tipos-obesidad.jpg'],
                            ['max' => 39.99, 'info' => 'Tienes obesidad nivel 2', 'img' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR0kp6a2BVQhkb_4PAht77YXQ2Hr_X0PvvGRg&s'],
                        ];

                        $info = "Tienes obesidad nivel 3";
                        $imgPath = "img/ob3.png";

                        foreach ($categorias as $cat) {
                            if ($imc <= $cat['max']) {
                                $info = $cat['info'];
                                $imgPath = $cat['img'];
                                break;
                            }
                        }

                        echo "
                            <script>
                                Swal.fire({
                                    title: '¡Éxito!',
                                    text: '¡Cálculo generado!',
                                    icon: 'success'
                                });
                            </script>
                        ";

                        echo "<h5>Tu IMC es: <strong>$imc</strong></h5>";
                        echo "<p>$info</p>";
                        echo "<form method='GET'><button class='btn btn-secondary mt-3' type='submit'>Reiniciar</button></form>";

                        $mostrarImagen = true;
                    } else {
                        echo "<div class='alert alert-danger'>La altura y el peso deben ser mayores a 0.</div>";
                    }
                } else {
                    echo "<p>Introduce tus datos para calcular tu IMC.</p>";
                }
                ?>
            </div>
        </div>
    </div>

    <div class="col-md-4 text-center">
        <?php
        $imgProvisional = "https://felipebernalcirujano.com/wp-content/uploads/2024/03/IMC_1-1024x470-1.png";
        ?>
        <div class="card">
            <div class="card-header">IMC</div>
            <div class="card-body">
                <img src="<?php echo $mostrarImagen ? $imgPath : $imgProvisional; ?>" class="img-fluid rounded" alt="">
            </div>
        </div>
    </div>
</div>

<script>
function validarCampo(campo) {
    const valor = campo.value.trim();
    const esValido = /^[0-9]+(\.[0-9]+)?$/.test(valor);
    if (!esValido || parseFloat(valor) <= 0) {
        campo.classList.add('is-invalid');
        return false;
    } else {
        campo.classList.remove('is-invalid');
        return true;
    }
}

document.getElementById('height').addEventListener('input', function () {
    validarCampo(this);
});
document.getElementById('weight').addEventListener('input', function () {
    validarCampo(this);
});

document.getElementById('formIMC').addEventListener('submit', function (e) {
    const alturaValida = validarCampo(document.getElementById('height'));
    const pesoValido = validarCampo(document.getElementById('weight'));
    if (!alturaValida || !pesoValido) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Datos inválidos',
            text: 'Revisa que los campos sean números y superior a 0.'
        });
    }
});
</script>

</body>
</html>
