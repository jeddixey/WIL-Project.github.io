

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./Styles/sign-login.css">
    <script src="https://kit.fontawesome.com/ffc82079a3.js" crossorigin="anonymous"></script>
    <title>Sign In</title>
</head>
<body>
    
    <header>
        <div class="logo"><img src="./Images/logo.png" alt="">estate</div>
        <nav>
            <ul class="left">
                <li><a href="./home.php">Home</a></li>
                <li><a href="./listings.php">Listings</a></li>
                <li><a href="./about-us.html">About</a></li>
                <li><a href="./contact.html">Contact</a></li>
                <div class="nav-right"><li><a href="./sign-login.html">Sign in</a></li></div>
            </ul>
        </nav>
    </header>

    <div class="container">
        <div class="form-box">
            <h1 id="title">Sign Up</h1>
            <form id="authForm" method="POST" action="register.php">
                <div class="input-group">
                    <div class="input-field" id="nameField">
                        <i class="fa-solid fa-user"></i>
                        <input type="text" placeholder="Name" name="fName" id="fName">
                    </div>
                    <div class="input-field">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" placeholder="Email" name="email" id="email" required>
                    </div>
                    <div class="input-field">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" placeholder="Password" name="password" id="password" required>
                    </div>
                    <p>Lost Password <a href="">Click Here!</a></p>
                </div>
                <div class="btn-field">
                    <button type="submit" id="signupBtn" name="signupBtn">Sign Up</button>
                    <button type="button" id="signinBtn" class="disable">Sign In</button>
                </div> 
            </form>
        </div>
    </div>

    <script>
        const signupBtn = document.getElementById("signupBtn");
        const signinBtn = document.getElementById("signinBtn");
        const nameField = document.getElementById("nameField");
        const title = document.getElementById("title");
        const authForm = document.getElementById("authForm");

        // Switch to Sign In
        signinBtn.onclick = function() {
            if (signupBtn.classList.contains("disable")) {
                // Allow submitting Sign In form
                authForm.action = "signin.php"; // Change this to your sign-in action
                authForm.submit();
            } else {
                // Switch forms
                nameField.style.maxHeight = "0";
                title.innerHTML = "Sign In";
                signupBtn.classList.add("disable");
                signinBtn.classList.remove("disable");
                // Clear fields for sign in
                document.getElementById("fName").value = ""; // Clear name field
            }
        };

        // Switch to Sign Up
        signupBtn.onclick = function(event) {
            if (!signupBtn.classList.contains("disable")) {
                // Allow the form to submit
                return true;
            } else {
                event.preventDefault(); // Prevent form submission if disabled
                // Switch forms
                nameField.style.maxHeight = "60px";
                title.innerHTML = "Sign Up";
                signupBtn.classList.remove("disable");
                signinBtn.classList.add("disable");
            }
        };
    </script>
</body>
</html>
