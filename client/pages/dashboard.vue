<template>
    <div>
      <div>
        <h1 class="greeting">Hello {{ state.user && state.user.name }}</h1>
        <button @click="handleSignout()" class="logout-btn">Logout</button>
      </div>
      <div class="user-list">Here are all the users!</div>
      <table class="user-table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in state.users" :key="user.id">
            <td>{{ user.name }}</td>
            <td>{{ user.email }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script setup>
  const state = reactive({
    users: [],
    user: null,
  });
  
  onMounted(() => {
    fetchUser();
    fetchUsers();
  });
  
  async function fetchUser() {
    try {
      const response = await $fetch('http://127.0.0.1:8000/api/user', {
        method: 'GET',
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('_token'),
        },
      });
  
      if (response.data) {
        state.user = response.data;
      }
    } catch (error) {
      state.errors = error.response;
      console.log('error', error);
    }
  }
  
  async function fetchUsers() {
    try {
      const usersResponse = await $fetch('http://127.0.0.1:8000/api/fetchUsers', {
        method: 'GET',
      });
  
      if (usersResponse.users) {
        state.users = usersResponse.users;
      }
    } catch (error) {
      state.errors = error.response;
      console.log('error', error);
    }
  }
  
  async function handleSignout() {
    try {
      const response = await $fetch('http://127.0.0.1:8000/api/user/logout', {
        method: 'POST',
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('_token'),
        },
      });
      localStorage.removeItem('_token');
      navigateTo('/');
    } catch (error) {
      state.errors = error.response;
      console.log('error', error);
    }
  }
  </script>
  
  <style scoped>
  .greeting {
    font-size: 24px;
    color: #333;
  }
  
  .logout-btn {
    padding: 8px 16px;
    background-color: #007bff;
    color: white;
    border: none;
    cursor: pointer;
  }
  
  .user-list {
    margin-top: 16px;
    font-size: 20px;
    color: #555;
  }
  
  .user-table {
    margin-top: 16px;
    border-collapse: collapse;
    width: 100%;
  }
  
  .user-table th, .user-table td {
    border: 1px solid #ddd;
    padding: 8px;
  }
  
  .user-table th {
    background-color: #f2f2f2;
  }
  
  .user-table td {
    text-align: center;
  }
  
  .user-table tr:nth-child(even) {
    background-color: #f2f2f2;
  }
  </style>
  