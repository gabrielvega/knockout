<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

<script>
$( document ).ready(function() {
  
  $.post( "ws/rest.php", { email: "gaveho@gmail.com", password: "1234" })
  .done(function( data ) {
    console.log(data);
    
    $( "p" ).text(data.message);
  },"json");
    
  });

</script>
</head>
<body>
  
<p>Waiting...</p>
</body>
</html>