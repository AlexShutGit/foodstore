const initRegisterButton = () => {
    const registerButton = document.querySelector('.registerButton');
    const username = document.getElementById('username');
    const login = document.getElementById('login');
    const password = document.getElementById('password');
    registerButton?.addEventListener('click', (event) => {
        event.preventDefault();
        fetch('/register-post', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                username: username.value,
                login: login.value,
                password: password.value,
            })
        })
        .then(response => {
            if(response.redirected) {
                location.href = '/login';
            } else {
                return response.json()
            }
        })
        .then(data => {
            if (data.error) {
                document.querySelector('.error').innerText = data.error;
            } else {
                location.href='/login';
            }
        });
    });
}

const initLoginButton = () => {
    const loginButton = document.querySelector('.login-button');
    const login = document.getElementById('login');
    const password = document.getElementById('password');
    loginButton?.addEventListener('click', (event) => {
        event.preventDefault();
        fetch('/login-post', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                login: login.value,
                password: password.value,
            })
        })
        .then(response => {
            if(response.status === 200) {
                location.href = '/';
            } else {
                return response.json();
            }
        })
        .then(data => {
            if (data.error) {
                document.querySelector('.error').innerText = data.error;
            } else {
                location.href = '/login';
            }
        });
    });
}

const initLogoutButton = () => {
    const logoutButton = document.querySelector('.logout-button');
    logoutButton?.addEventListener('click', (event) => {
        event.preventDefault();
        fetch('/logout-post', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
        })
        .then(response => {
                location.href = '/login';
        })
    });
}

initLogoutButton();
initLoginButton();
initRegisterButton();
