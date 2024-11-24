<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

require_once 'config.php';

?>

<?php require_once 'head.php'; ?>	
<body class="bg-gray-800 text-white">
<?php require_once 'header.php'; ?>	
<main class="py-10"> 
        <div class="container mx-auto">




        </div>
</main>	
<?php require_once 'footer.php'; ?>			
</body>
</html>
