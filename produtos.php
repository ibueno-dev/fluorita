<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nosso Cardápio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/produtos.css"> </head>
<body>

    <div class="container my-5">
        <h1 class="text-center mb-5 text-white">Conheça nosso Cardápio</h1>

        <div id="lista-produtos" class="row g-4">
            </div>
    </div>


    <template id="produto-template">
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card h-100 product-card">
                <img src="" class="card-img-top" alt="Imagem do Produto">
                <div class="card-body d-flex flex-column text-center">
                    <h5 class="card-title"></h5>
                    <p class="product-price mb-2"></p>
                    <p class="card-text small"></p>
                    <hidden class="product-id"></hidden>
                </div>                
            </div>
        </div>
    </template>


    <div class="modal fade" id="imagem-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="" id="imagem-modal-grande" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    <div class="container my-5">
        <div class="text-center">
            <a href="login.php" class="btn btn-outline-primary btn-lg">Acessar Administração</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/listar_produtos.js"></script>
</body>
</html>