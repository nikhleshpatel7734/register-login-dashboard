$(document).ready(function() {
    $("#myForm").on("submit", function(event) {
      event.preventDefault();
      var formData = $(this).serialize();
      $.ajax({
        type: "POST",
        url: "server.php",
        data: formData,
        success: function(data) {
          if (data === "success") {
            alert("Data saved successfully!");
          } else {
            alert("Error: " + data);
          }
        }
      });
    });
  });