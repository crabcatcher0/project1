<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <header>
        <nav class="navbar">
            <a class="logo" href="#">HostelDiscover</a>
            
        </nav>
    </header>

    <div class="payment-info">
        <h2>Payment</h2>
        <p>
        To complete your payment, we kindly ask you to visit our office in person. Our dedicated staff will assist you through the payment process and address any queries you may have.
      </p>

    <p>
        We appreciate your cooperation and look forward to providing you with a seamless payment experience.
    </p>

    <p>
        If you have any further inquiries or need assistance, please feel free to contact our customer support team.
    </p>
    <button class="pay-contact-button" id="contactButton">Contact Us</button>
    <button class="go-back-button" onclick="goBack()">Go Back</button>
    </div>
     
    <script>
  document.getElementById("contactButton").addEventListener("click", function() {
    window.location.href = "../contact/contact.php";
  });
  function goBack() {
            window.history.back();
        }
</script>

</body>
</html>
