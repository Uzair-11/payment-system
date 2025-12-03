<!DOCTYPE html>
<html>
<head>
    <title>AtlasPay - Start</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #090d1f, #0e1530, #0a1228);
            background-size: 300% 300%;
            animation: gradientMove 12s ease infinite;
            color: #e6eaf3;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        @keyframes gradientMove {
            0% {background-position: 0% 50%;}
            50% {background-position: 100% 50%;}
            100% {background-position: 0% 50%;}
        }

        .container {
            text-align: center;
            background: rgba(255,255,255,0.05);
            padding: 30px;
            border-radius: 15px;
            width: 400px;
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.1);
            box-shadow: 0 0 25px rgba(124,92,255,0.2);
        }

        h1 {
            margin-bottom: 20px;
            color: #b8a4ff;
        }

        .role-btn {
            display: block;
            padding: 14px;
            margin: 12px 0;
            background: rgba(124,92,255,0.15);
            border: 1px solid rgba(124,92,255,0.4);
            color: #e6eaf3;
            font-size: 18px;
            border-radius: 10px;
            text-decoration: none;
            transition: 0.3s;
        }

        .role-btn:hover {
            background: rgba(124,92,255,0.3);
            transform: translateY(-2px);
        }

    </style>
</head>
<body>

<div class="container">
    <h1>Welcome to AtlasPay</h1>
    <p>Select how you want to login</p>

    <a class="role-btn" href="user/user-login.php">User Login</a>
    <a class="role-btn" href="merchant/merchant-login.php">Merchant Login</a>
    <a class="role-btn" href="bank/bank-login.php">Bank Login</a>
    <a class="role-btn" href="admin/admin-login.php">Admin Login</a>
</div>

</body>
</html>
