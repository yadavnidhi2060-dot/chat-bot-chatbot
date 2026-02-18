<?php
        $showAlert = false;
        $showError = false;
if($_SERVER["REQUEST_METHOD"]=="POST"){ 
    $action = $_POST['action'] ?? '';
    include "dbconnect.php";
    $username = $_POST['username'];
    $password =  $_POST['password'];
if ($action === 'register') {
    $cpassword = $_POST['cpassword'];
       // Check if user already exists
        $sql_exist = "SELECT * FROM hello WHERE username = '$username'";
        $result_exist = mysqli_query($conn, $sql_exist);
        $exists = (mysqli_num_rows($result_exist) > 0);
if(($password==$cpassword)&& !$exists){
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$sql = "INSERT INTO hello (username, password, dt) VALUES ('$username', '$hashedPassword', current_timestamp())";
$result=mysqli_query($conn,$sql);
//if($result){
 // $showAlert= true;
//}
//else{
 // $showError="password do not match";
//}}
      if ($result) {
                $showAlert = true;
            } else {
                $showError = "Error inserting user into database.";
            }
        } elseif ($exists) {
            $showError = "Username already exists.";
        } elseif ($password !== $cpassword) {
            $showError = "Passwords do not match.";
        } else {
            $showError = "Unknown error occurred.";
        }

    } 
 elseif ($action === 'login') {
        // Add your login logic here (e.g., check if username/password matches DB)
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Signup form</title>
    <style>
      @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap');

*{
font-family: "poppins", sans-serif;
margin: 0;
padding: 0;
box-sizing: border-box;
text-decoration: none;
list-style: none;
}
/* body styles */
 body {
    min-height: 100vh;
    background: linear-gradient(90deg,#e2e2e2,#c9d6ff);
    display: flex;
    flex-direction: column;
    align-items: center;
}


/*navigation*/
*{
    margin: 0px;
    padding: 0px;
    box-sizing: border-box;
}
 
  
 nav {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 60px;
    background-color: #ffffffef;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1vw 8vw;
    box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.15);
    z-index: 999;
}

 nav img {
    width: 70px;
    cursor:pointer;
 }
 nav .navigation{
    display: flex;
 }
 #menu-btn {
    width: 30px;
    height: 30px;
    display: none;
 }
 #menu-close {
    display: none;
 }

 nav .navigation ul {
    display: flex;
    justify-content: flex-end;
    align-items: normal;
 }

 nav .navigation ul li{
    list-style: none;
    margin-left: 30px;

 }
 
 nav .navigation ul li a{
    text-decoration: none;
    color: rgb( 21 ,21 ,100);
    font-size: 16px;
    font-weight: 500;
    transition: 0.3s ease;
 }

 nav .navigation ul li a:hover {
    color: #7494ec;
 }

    
 

/* container */
.container{
    position: relative;
    width: 850px;
    height: 550px;
    background: #fff;
    margin: 20px;
    border-radius: 30px;
    box-shadow: 0 0 30px rgba(0, 0, 0.2);
    overflow: hidden;
    margin-top: 120px;

}

.container h1{
    font-size: 36px;
    margin: -10px 0;
    margin-top: 100px;
}

.container p{
    font-size: 14.5px;
    margin: 15px 0;
}


form{
    width: 100%;
}

.form-box{
    position: absolute;
    right: 0;
    width: 50%;
    height: 100%;
    background: #fff;
    display: flex;
    align-items: center;
    color: #333;
    text-align: center;
    padding: 40px;
    z-index: 1;
    transition: 0.6s ease-in-out 1.2s;
    visibility: 0s 1s;

}
.container.active .form-box{
    right: 50%;
}

.form-box.Register{
    visibility: hidden;
}
.container.active .form-box.Login {
    opacity: 1;
    visibility: hidden;
    pointer-events: none;
}

.container.active .form-box.Register{
     visibility: visible;


}
.container:not(.active).form-box.Login {
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
}

.input-box{
    position: relative;
    margin: 30px 0;
}
.input-box input{
    width: 100%;
    padding: 13px 50px 13px 20px;
    background: #eee;
    border-radius: 8px;
    border: none;
    outline: none;
    font-size:  16px;
    color: #333;
    font-weight: 500;

}

.input-box input::placeholder{
    color: #888;
    font-weight: 400;

}

.input-box i{
    position: absolute;
    right: 20px;
    top:50%;
    transform: translateY(-50%);
    font-size: 20px;

}
.forgot-link{
    margin: -15px 0 15px;

}
.forgot-link a{
    font-size: 14.5px;
    color: #333;
}

.btn{
    width: 100%;
    height: 48px;
    background: #333;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border: none;
    outline: none;
    cursor: pointer;
    font-size: 16px;
    color: #fff;
    font-weight: 600;
}

.social-icons{
    display: flex;
    justify-content: center;
}

.social-icons a{
    display: inline-flex;
    padding: 10px;
    border: 2px solid #ccc;
    font-size: 24px;
    color: #333;
    margin: 0 8px;

}

.toggle-box{
    position: absolute;
    width: 100%;
    height: 100%;
}

.toggle-box::before{
    content: '';
    position: absolute;
    left: -250%;
    width: 300%;
    height: 100%;
    background: #333;
    border-radius: 150px;
    z-index: 2;
    transition: 1.8s ease-in-out;

    
}
.container.active .toggle-box::before{
    left:  50%;
}
.toggle-panel{
    position: absolute;
    width: 50%;
    height: 100%;
    color: #ccc;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    z-index: 2;
    transition: 0.6s ease-in-out;
}

.toggle-panel.toggle-left{
    left: 0;
    transition-delay: 1.2s;

}
.container.active .toggle-panel.toggle-left{
    left: -50%;
    transition-delay: 0.6s;
}
.toggle-panel.toggle-right{
    right: -50%;
    transition-delay: 0.6s;
}
 .container.active .toggle-panel.toggle-right {
    right: 0;
    transition-delay: 1.2s;
}


.toggle-panel p{
    margin-bottom: 20px;
}
.toggle-panel .btn{
width: 160px;
height: 46px;
background: transparent;
border: 2px solid#fff;
box-shadow: none;
}
.social-icons a:hover {
    background: #7494ec;
    color: #fff;
    border-color: #7494ec;
    border-radius: 50%;
    transition: 0.3s;
}
.btn:hover {
    background: #5c7ce0;
    transition: 0.3s;
}
.btn:hover {
    background: #fff;
    transition: 0.3s;
}
 
 
.navbar ul{
   list-style-type:none;
   background-color: hsl(0, 0%, 25%);
   padding: 0px;
   margin: 0px;
   overflow: hidden;
 }
.navbar a{
    color: white;
    text-decoration: none;
    padding: 15px;
    display: block;
    text-align: center;
}
.navbar a:hover{
    background-color: hsl(0, 0%, 10%);

}
.navbar li{
    float:left;
} 
.alert-container {
    position: fixed;
    top: 60px; /* same as navbar height */
    left: 0;
    width: 100%;
    z-index: 998;
    display: flex;
    justify-content: center;
    padding: 10px 0;
}

.alert {
    padding: 15px 25px;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 500;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    margin: 10px;
    min-width: 300px;
    text-align: center;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

    </style>
  <!--  <link rel="stylesheet" href="style.css ">-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
     

</head>
<body>
    <nav>
    <h1>Medilex</h1>
    <div class="navigation">
        <ul>          
            <li><a href=" #">Home </a></li> 
            <li><a href=" #"> Hosital </a></li> 
            <li><a href=" #"> Government</a></li> 
            <li><a href=" #"> Template</a></li>
            <li><a href=" #">  Login </a></li> 
        </ul>        
    </div>
</nav>
<!--if($showAlert) {
echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Success!</strong> You account is now created and you can login .
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>';}
if($showError){
echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>Error!</strong>'.$showError.'
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>';}
?>*/-->
 <div class="alert-container">
<?php if ($showAlert): ?>
  <div class="alert alert-success">
    <strong>Success!</strong> Your account is now created and you can login.
  </div>
<?php endif; ?>

<?php if ($showError): ?>
  <div class="alert alert-error">
    <strong>Error!</strong> <?= $showError ?>
  </div>
<?php endif; ?>
</div>
       
<main>
  <!-- container div continues here -->
    
    <div class="container">

       <!--Login Form--> 

        <div class="form-box Login">
            <form action="#" method="POST">
                    <input type="hidden" name="action" value="login">
                <h1>Login</h1>
                <div class="input-box">
                    <input type="text" name="username" placeholder="username" required>
                    <i class="fa-solid fa-user"></i>
                </div> 
                <div class="input-box">
                    <input type="password" name="password" placeholder="password" required>
                    <i class="fa-solid fa-lock"></i>
                </div>
                <div class="forgot-link">
                    <a href="#"> Forgot Password</a>
                </div>
                <button type="submit" class="btn">Login</button>
                <p>Or Login with social platforms</p>
                <div  class="social-icons">
                    <a href="#"><i class="fa-brands fa-google"></i></a>
                    <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#"><i class="fa-brands fa-github"></i></a>
                    <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
            </form>
        </div>
        <!--Register Form-->
        <div class="form-box Register"> 
            <form action="#" method="POST">
                <input type="hidden" name="action" value="register">
                <h1>Signup</h1>
                <div class="input-box">
                    <input type="text" name="username" placeholder="Username" required>
                    <i class="fa-solid fa-user"></i>
                </div> 
                <div class="input-box">
                   
                     <input type="password" class="form-control" id="password" name="password" placeholder=" Password">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <div class="input-box">
                     <input type="password" class="form-control" id="cpassword" name="cpassword" placeholder="Confirm Password">
                     <small id="emailHelp" class="form-text text-muted">Make sure to type the same pasword.</small>
                     <i class="fa-solid fa-lock"></i>
                </div>
               
                <button type="submit" class="btn">Register</button>
                <p>Or Register with social platforms</p>
                <div  class="social-icons">
                    <a href="#"><i class="fa-brands fa-google"></i></a>
                    <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#"><i class="fa-brands fa-github"></i></a>
                    <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
            </form>
        </div>
       <!--Toggle Box -->
      <div class="toggle-box">
          <!--Toggle Box Left-->
           <div class="toggle-panel toggle-left">
                 <h1>Hello,Welcome!</h1>
                 <p>Don't have an account?</p>
                 <button class="btn register-btn">Register</button>
           </div>
          <!--Toggle Box Right-->
            <div class="toggle-panel toggle-right">
                 <h1>Welcome Back!</h1>
                 <p>Already have an account?</p>
                 <button class="btn login-btn">Login</button>
            </div>
       </div>
    </div>
  </main>
</body>
<script>
  const container = document.querySelector('.container');
const registerBtn = document.querySelector('.register-btn');
const loginBtn = document.querySelector('.login-btn');
registerBtn.addEventListener('click', () => {
    container.classList.add('active');
});
loginBtn.addEventListener('click', () => {
    container.classList.remove('active');
}); 
  setTimeout(() => {
    const alertBox = document.querySelector('.alert-container');
    if (alertBox) alertBox.style.display = 'none';
  }, 4000);
</script>
</html>