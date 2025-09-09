<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Auth Fortify</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body>
    <h1>Test Auth Fortify + Sanctum (Stateful)</h1>

    <h2>Register</h2>
    <input type="text" id="reg_name" placeholder="Name">
    <input type="email" id="reg_email" placeholder="Email">
    <input type="password" id="reg_password" placeholder="Password">
    <input type="password" id="reg_password_confirmation" placeholder="Password Confirmation">
    <button onclick="register()">Register</button>

    <h2>Login</h2>
    <input type="email" id="login_email" placeholder="Email">
    <input type="password" id="login_password" placeholder="Password">
    <button onclick="login()">Login</button>

    <h2>User Info</h2>
    <button onclick="me()">Me</button>
    <pre id="user_info"></pre>

    <h2>Logout</h2>
    <button onclick="logout()">Logout</button>

    <script>
        axios.defaults.withCredentials = true;

        async function getCsrfToken() {
            await axios.get('/sanctum/csrf-cookie');
        }

        async function register() {
            await getCsrfToken();
            const name = document.getElementById('reg_name').value;
            const email = document.getElementById('reg_email').value;
            const password = document.getElementById('reg_password').value;
            const password_confirmation = document.getElementById('reg_password_confirmation').value;

            try {
                const res = await axios.post('/register', { name, email, password, password_confirmation });
                alert('Registered: ' + JSON.stringify(res.data.user));
            } catch (err) {
                alert(JSON.stringify(err.response.data));
            }
        }

        async function login() {
            await getCsrfToken();
            const email = document.getElementById('login_email').value;
            const password = document.getElementById('login_password').value;

            try {
                const res = await axios.post('/login', { email, password });
                alert('Logged in: ' + JSON.stringify(res.data.user));
            } catch (err) {
                alert(JSON.stringify(err.response.data));
            }
        }

        async function me() {
            try {
                const res = await axios.get('/user');
                document.getElementById('user_info').innerText = JSON.stringify(res.data, null, 2);
            } catch (err) {
                alert(JSON.stringify(err.response.data));
            }
        }

        async function logout() {
            try {
                const res = await axios.post('/logout');
                alert(res.data.message);
                document.getElementById('user_info').innerText = '';
            } catch (err) {
                alert(JSON.stringify(err.response.data));
            }
        }
    </script>
</body>
</html>
