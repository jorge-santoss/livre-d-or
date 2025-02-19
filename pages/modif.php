<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <link rel="stylesheet" href="../css/ind.css">
    <script src="https://kit.fontawesome.com/ca3234fc7d.js" crossorigin="anonymous"></script>
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&display=swap" rel="stylesheet">
    <title>modification du profil</title>
</head>

<body>
<header>
        <div class="navbar">
            
            <ul class="links">
                <li><a href="../index.php"><i class="fa-solid fa-house"></i></a></li>
                <li><a href="modif.php"><i class="fa-solid fa-user-pen"></i></a></li>
                <li><a href="#"><i class="fa-solid fa-book"></i></a></li>
                <li><a href="#"><i class="fa-solid fa-pen"></i></a></li>
            </ul>
            <div class="box">
                <a href="#">
                    <input type="search" br placeholder="search...">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </a>
            </div>
            
            <div class="burger-menu-button">
                <i class="fa-solid fa-bars"></i>
            </div>
        </div>
        <div class="burger-menu open">
            <ul class="links">
                <li><a href="../index.php"><i class="fa-solid fa-house"></i></a></li>
                <li><a href="#"><i class="fa-solid fa-user-pen"></i></a></li>
                <li><a href="#"><i class="fa-solid fa-book"></i></a></li>
                <li><a href="#"></i></a></li>
                <div class="divider"></div>
                <div class="buttons-burger-menu">
                    <a href="#" class="action-button-user">
                        <i class="fa-solid fa-user"></i>
                    </a>
                </div>
            </ul>
        </div>
    </header>
    <script>
        const burgerMenuButton = document.querySelector('.burger-menu-button ')
        const burgerMenuButtonIcon = document.querySelector('.burger-menu-button i')
        const burgerMenu = document.querySelector('.burger-menu')

        burgerMenuButton.onclick = function () {
            burgerMenu.classList.toggle('open')
            const isOpen = burgerMenu.classList.contains('open')
            burgerMenuButtonIcon.classList = isOpen ? 'fa-solid fa-x' : 'fa-solid fa-bars'
        }
    </script>
<main>
    <div class="page_modif">
        <div class="info">
            
        </div>
        
    </div>
    </main>
    </body>
    </hmtl>