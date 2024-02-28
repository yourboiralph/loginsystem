<template>
    <div class="body">
        <div class="login-form">
            <h2>Login</h2>
            <form @submit.prevent="handleLogin()">
                <input type="text" name="email" placeholder="Email" v-model="state.user.email">
                <p>{{ state.errors && state.errors._data && state.errors._data.errors && state.errors._data.errors.email && state.errors._data.errors.email[0]}}</p>
                <br>
                <input type="password" name="password" placeholder="Password" v-model="state.user.password">
                <p>{{ state.errors && state.errors._data && state.errors._data.errors && state.errors._data.errors.password && state.errors._data.errors.password[0]}}</p><br>
                <button type="submit">Login</button>
            </form>
        </div>
    </div>
</template>

<style scoped>
        .body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-form {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 2px 10px rgba(0,0,0,0.1);
            width: 300px;
        }
        .login-form h2 {
            margin-top: 0;
        }
        .login-form input[type="text"], 
        .login-form input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .login-form button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .login-form button:hover {
            background-color: #0056b3;
        }
</style>
  


<script setup>

const state = reactive({
    errors: null,
    user:{
        email: null,
        password: null,
    }
})

async function handleLogin(){
    const params = {
        email: state.user.email,
        password: state.user.password,
    }

    try {
        const response = await $fetch(`http://127.0.0.1:8000/api/auth/login`, {
        method: 'POST',
        body: params
  })

  if (response.data){
    localStorage.setItem('_token', response.data.token);
    navigateTo('/dashboard');
  }
    }
    catch (error){
        state.errors = error.response;
        console.log('error', error);
    }
}
</script>