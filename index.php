<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>

<button id="mycity">
  123456
</button>
<div></div>

<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

<script>
  const div = document.querySelector('div')
  $("#mycity").click(function() {
      $.ajax({
        url: 'http://fongmin.byethost7.com/work-product/exhibits-list.php',
        type: 'post',
        dataType: 'json',
        success: function(data) {
          console.log(data);
        },
        error: function(data) {
          alert("系統目前無法連接後台資料庫")
        }
      })
    })
</script>


</body>

</html>