<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- BOOTSTRAP-CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">

    <!-- CSS-APP -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/app.css'); ?>">

    <!-- FONTAWESOME -->
    <link rel="stylesheet" href="<?php echo base_url('assets/fontawesome/css/all.css'); ?>">

    <title><?php echo $title; ?></title>
</head>

<body class="gf-montserrat">
    <div class="d-flex">
        <sidebar class="d-flex flex-column border-end border-light">
            <div class="text-center p-2">
                <h2>Toque Divino</h2>
            </div>
            <div class="text-dark p-2">
                <ul class="nav flex-column">
                    <li class="nav-item border-bottom d-flex flex-row justify-content-between align-items-center">
                        <a class="nav-link link-dark" href="#collapseSystem" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="collapseSystem">
                            <i class="bi bi-gear m-2"></i>
                            Sistema
                        </a>
                        <i class="bi bi-caret-down-fill mr-4"></i>
                    </li>
                    <div class="collapse" id="collapseSystem">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a href="#" class="nav-link link-dark mx-5">
                                    <i class="bi bi-person"></i>
                                    Usuários
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link link-dark mx-5">
                                    <i class="fas fa-guitar"></i>
                                    Formações
                                </a>
                            </li>
                        </ul>
                    </div>
                    <li class="nav-item border-bottom">
                        <a class="nav-link link-dark" href="#contracts">
                            <i class="bi bi-file-text m-2"></i>
                            Contratos
                        </a>
                    </li>
                </ul>
            </div>
        </sidebar>
        <main>
            <header class="d-flex flex-row justify-content-between p-3 bg-white">
                <div class="d-block">
                    <h3>Dashboard</h3>
                </div>
                <div class="d-block">
                    <a href="http://" target="_blank" class="btn btn-outline-dark"><i
                            class="bi bi-power m-2"></i>Logout</a>
                </div>
            </header>
            <div id="content" class="d-flex flex-row justify-content-around p-3">
                <span id="data"></span>
            </div>
        </main>
    </div>

    <!-- BOOTSTRAP-JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous">
    </script>

    <script>
    function twoDigits(d) {
        if (0 <= d && d < 10) return "0" + d.toString();
        if (-10 < d && d < 0) return "-0" + (-1 * d).toString();
        return d.toString();
    }

    Date.prototype.toMysqlFormat = function() {
        return this.getUTCFullYear() + "-" + twoDigits(1 + this.getUTCMonth()) + "-" + twoDigits(this
        .getUTCDate()) + " " + twoDigits(this.getUTCHours()) + ":" + twoDigits(this.getUTCMinutes()) + ":" +
            twoDigits(this.getUTCSeconds());
    };

    // var date = new Date()
    // var printDate = document.querySelector('#data')
    // printDate.innerHTML = date.toMysqlFormat()

    arrTeste = new Map()
    arrTeste.set('arr1', parseFloat(10))
    arrTeste.set('arr2', parseFloat(15))
    </script>
</body>

</html>