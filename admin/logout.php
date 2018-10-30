<?
unset($_SESSION['memberId']); 
unset($_SESSION['memberName']);
unset($_SESSION['memberCode']);
unset($_SESSION['memberCompany']);
unset($_SESSION['memberLevel']);
session_destroy(); 
?>

<script>location.href="admin.php";</script>